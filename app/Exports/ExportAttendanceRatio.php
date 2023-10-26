<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ExportAttendanceRatio implements FromView, WithEvents
{
    function __construct($year, $month, $company, $tanggal, $departement, $nameDepartement)
    {
        $this->year = $year;
        $this->month = $month;
        $this->company = $company;
        $this->tanggal = $tanggal;
        $this->departement = $departement;
        $this->nameDepartement = $nameDepartement;
    }

    public function view(): View
    {
        $controller = new Controller();
        $response = $controller->getAttendanceRatio($this->year, $this->month, $this->company, $this->departement);
        if (!empty($response) && $response['status'] == true) {
            $attendanceRatio = $response['safety'];
        } else {
            return redirect()->back()->with('err_message', 'Attendance Ratio Not Found');
        }
        return view('user-management/attendance-ratio-export', [
            'data' => $attendanceRatio
        ]);
    }

    public function registerEvents(): array
    {
        $header1 = 'Attendance Ratio ' .$this->tanggal;
        $header2 = $this->nameDepartement;
        $totalMP = 0;
        $totalHariKerja = 0;
        $totalMandays = 0;
        $totalAbsentism = 0;
        $attendanceRatio = 0;
        $controller = new Controller();
        $response = $controller->getAttendanceRatio($this->year, $this->month, $this->company, $this->departement);
        if (!empty($response) && $response['status'] == true) {
            if($response['mp'] > 0){
                $totalMP = $response['mp'];
                $totalHariKerja = $controller->getTotalWeekdaysInMonth($this->year.'-'.$this->month);
                $totalMandays = $totalHariKerja * $totalMP;
                $totalAbsentism = $totalMandays - array_sum(array_column($response['safety'], 'PRS'));
                $attendanceRatio = round(($totalAbsentism / $totalMandays) * 100);
            }
        }else{
            return redirect()->back()->with('err_message', 'Attendance Ratio Not Found');
        }

        return [
            AfterSheet::class => function (AfterSheet $event) use ($header1, $header2, $totalMP, $totalHariKerja, $totalMandays, $totalAbsentism, $attendanceRatio) {
                $sheet = $event->sheet;

                //Mulai cetak dari ROW 7
                $sheet->getDelegate()->insertNewRowBefore(1, 7);

                // Tulis judul dan beri format bold
                $sheet->setCellValue('A1', $header1);
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->setCellValue('A2', $header2);
                $sheet->getStyle('A2')->getFont()->setBold(true);

                // Gabungkan kolom A sampai F di paling atas
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A2:F2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $lastRow = $sheet->getHighestRow();
                $columnRange = 'A8:F' . $lastRow;
                $columnRange2 = 'F9:F' . $lastRow;
                $columnRange3 = 'A1:F' . $lastRow;

                // Set perataan teks rata kiri untuk seluruh kolom A sampai F
                $sheet->getStyle($columnRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle($columnRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle($columnRange)->getAlignment()->setWrapText(true);

                //Set all border dari kolom A sampai F
                $sheet->getStyle($columnRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Set perataan teks rata kanan untuk seluruh kolom F
                $sheet->getStyle($columnRange2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Set warna putih dari kolom A sampai F
                $sheet->getStyle($columnRange3)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle($columnRange3)->getFill()->getStartColor()->setARGB('fafafa');

                for ($row = 9; $row <= $lastRow; $row++) {
                    // Cek apakah nilai kolom F bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('F' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('F' . $row, $value);
                    }else{
                        $sheet->setCellValue('F' . $row, "\u{00A0}0");
                    }
                }
                
                // Set lebar kolom A sampai F dan bold header
                $sheet->getColumnDimension('A')->setWidth(13);
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(25);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getStyle('A8')->getFont()->setBold(true);
                $sheet->getStyle('B8')->getFont()->setBold(true);
                $sheet->getStyle('C8')->getFont()->setBold(true);
                $sheet->getStyle('D8')->getFont()->setBold(true);
                $sheet->getStyle('E8')->getFont()->setBold(true);
                $sheet->getStyle('F8')->getFont()->setBold(true);

                //Set tengah kolom A sampai F
                $sheet->getStyle('A8:F8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                //Set Total EMP sampai Attendance Ratio
                
                //Set total emp
                $sheet->setCellValue('A4', 'Total EMP');
                $sheet->getStyle('A4')->getFont()->setBold(true);
                $sheet->setCellValue('A5', $totalMP);
                $sheet->getStyle('A5')->getFont()->setBold(true);
                $sheet->mergeCells('A4:B4');
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->mergeCells('A5:B6');
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A4:B6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                //Set total hari kerja
                $sheet->setCellValue('C4', 'Total Hari Kerja');
                $sheet->getStyle('C4')->getFont()->setBold(true);
                $sheet->setCellValue('C5', $totalHariKerja);
                $sheet->getStyle('C5')->getFont()->setBold(true);
                $sheet->getStyle('C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->mergeCells('C5:C6');
                $sheet->getStyle('C5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('C4:C6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                //Set total mandays
                $sheet->setCellValue('D4', 'Total Mandays');
                $sheet->getStyle('D4')->getFont()->setBold(true);
                $sheet->setCellValue('D5', $totalMandays);
                $sheet->getStyle('D5')->getFont()->setBold(true);
                $sheet->getStyle('D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->mergeCells('D5:D6');
                $sheet->getStyle('D5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('D4:D6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                //Set total absentism
                $sheet->setCellValue('E4', 'Total Absentism');
                $sheet->getStyle('E4')->getFont()->setBold(true);
                $sheet->setCellValue('E5', $totalAbsentism);
                $sheet->getStyle('E5')->getFont()->setBold(true);
                $sheet->getStyle('E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->mergeCells('E5:E6');
                $sheet->getStyle('E5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('E4:E6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                //Set total attendance ratio
                $sheet->setCellValue('F4', 'Total Attendance Ratio');
                $sheet->getStyle('F4')->getFont()->setBold(true);
                $sheet->setCellValue('F5', $attendanceRatio . ' %');
                $sheet->getStyle('F5')->getFont()->setBold(true);
                $sheet->getStyle('F4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->mergeCells('F5:F6');
                $sheet->getStyle('F5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('F4:F6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
