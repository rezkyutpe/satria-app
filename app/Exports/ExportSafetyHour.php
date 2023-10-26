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

class ExportSafetyHour implements FromView, WithEvents
{
    function __construct($year, $month, $company, $tanggal)
    {
        $this->year = $year;
        $this->month = $month;
        $this->company = $company;
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        $controller = new Controller();
        $response = $controller->getSafetyHour($this->year, $this->month, $this->company);
        if (!empty($response) && $response['status'] == true) {
            $safetyhour = $response['safety'];
        } else {
            return redirect()->back()->with('err_message', 'Safety Hour Not Found');
        }
        return view('user-management/safety-hour-export', [
            'data' => $safetyhour
        ]);
    }

    public function registerEvents(): array
    {
        $header = 'Safety Hour ' .ucfirst($this->company). ' ' .$this->tanggal;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($header) {
                $sheet = $event->sheet;

                //Mulai cetak dari ROW 2
                $sheet->getDelegate()->insertNewRowBefore(1);

                // Tulis judul dan beri format bold
                $sheet->setCellValue('A1', $header);
                $sheet->getStyle('A1')->getFont()->setBold(true);

                // Gabungkan kolom A sampai K di paling atas
                $sheet->mergeCells('A1:K1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $lastRow = $sheet->getHighestRow();
                $columnRange = 'E3:K' . $lastRow;
                $columnRange2 = 'A2:D' . $lastRow;

                // Set perataan teks rata kiri untuk seluruh kolom A sampai D
                $sheet->getStyle($columnRange2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Set perataan teks rata kanan untuk seluruh kolom E sampai K
                $sheet->getStyle($columnRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                for ($row = 3; $row <= $lastRow; $row++) {
                    // Cek apakah nilai kolom E bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('E' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('E' . $row, $value);
                    }else{
                        $sheet->setCellValue('E' . $row, "\u{00A0}0");
                    }

                    // Cek apakah nilai kolom F bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('F' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('F' . $row, $value);
                    }else{
                        $sheet->setCellValue('F' . $row, "\u{00A0}0");
                    }

                    // Cek apakah nilai kolom G bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('G' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('G' . $row, $value);
                    }else{
                        $sheet->setCellValue('G' . $row, "\u{00A0}0");
                    }

                    // Cek apakah nilai kolom H bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('H' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('H' . $row, $value);
                    }else{
                        $sheet->setCellValue('H' . $row, "\u{00A0}0");
                    }

                    // Cek apakah nilai kolom I bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('I' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('I' . $row, $value);
                    }else{
                        $sheet->setCellValue('I' . $row, "\u{00A0}0");
                    }

                    // Cek apakah nilai kolom J bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('J' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('J' . $row, $value);
                    }else{
                        $sheet->setCellValue('J' . $row, "\u{00A0}0");
                    }

                    // Cek apakah nilai kolom K bukan 0, lalu set nilai jika bukan 0
                    $value = $sheet->getCell('K' . $row)->getValue();
                    if ($value != 0) {
                        $sheet->setCellValue('K' . $row, $value);
                    }else{
                        $sheet->setCellValue('K' . $row, "\u{00A0}0");
                    }
                }
                
                // Set lebar kolom A sampai K dan bold header
                $sheet->getColumnDimension('A')->setWidth(9);
                $sheet->getColumnDimension('B')->setWidth(45);
                $sheet->getColumnDimension('C')->setWidth(67);
                $sheet->getColumnDimension('D')->setWidth(75);
                $sheet->getColumnDimension('E')->setWidth(8);
                $sheet->getColumnDimension('F')->setWidth(8);
                $sheet->getColumnDimension('G')->setWidth(8);
                $sheet->getColumnDimension('H')->setWidth(8);
                $sheet->getColumnDimension('I')->setWidth(8);
                $sheet->getColumnDimension('J')->setWidth(8);
                $sheet->getColumnDimension('K')->setWidth(8);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('B2')->getFont()->setBold(true);
                $sheet->getStyle('C2')->getFont()->setBold(true);
                $sheet->getStyle('D2')->getFont()->setBold(true);
                $sheet->getStyle('E2')->getFont()->setBold(true);
                $sheet->getStyle('F2')->getFont()->setBold(true);
                $sheet->getStyle('G2')->getFont()->setBold(true);
                $sheet->getStyle('H2')->getFont()->setBold(true);
                $sheet->getStyle('I2')->getFont()->setBold(true);
                $sheet->getStyle('J2')->getFont()->setBold(true);
                $sheet->getStyle('K2')->getFont()->setBold(true);
            },
        ];
    }
}
