<?php

namespace App\Exports;

use App\Models\Table\Pajak\MstFakturPajak;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class DownloadExport implements FromView,WithColumnFormatting
{
    protected $date;
    protected $status;
    protected $id;

    function __construct($date,$status,$id) {
            $this->date = $date;
            $this->status = $status;
            $this->id = $id;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function view(): View
    {
        if (isset($this->date,$this->status)){
            
            $pajak = MstFakturPajak::where('export',$this->status)->where('date_scan', 'like', "%" . $this->date. "%")->orderBy('date_scan', 'asc')->get();
             MstFakturPajak::where('export',$this->status)->where('date_scan', 'like', "%" . $this->date. "%")
                    ->update([
                        'export' => 'Y',
                ]);
        }else if(isset($this->date)){
            $pajak = MstFakturPajak::where('date_scan', 'like', "%" . $this->date. "%")->orderBy('date_scan', 'asc')->get();
             MstFakturPajak::where('date_scan', 'like', "%" . $this->date. "%")
                    ->update([
                        'export' => 'Y',
                ]);
        } else if(isset($this->status)){
            $pajak = MstFakturPajak::where('export',$this->status)->orderBy('date_scan', 'asc')->get();
             MstFakturPajak::where('export',$this->status)
                ->update([
                        'export' => 'Y',
                ]);
        } else if(isset($this->id)){
            $pajak = MstFakturPajak::where('id',$this->id)->get();
             MstFakturPajak::where('id',$this->id)
                    ->update([
                        'export' => 'Y',
                ]);
        } else {
            $pajak = MstFakturPajak::orderBy('date_scan', 'desc')->get();

        }
        return view('pajak/faktur-management/export', [
            'datas' => $pajak
        ]);
      
    }
}