<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Models\View\Elsa\VwPr;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class PrExport implements FromView,WithColumnFormatting
{
    protected $start;
    protected $end;
    protected $subject;

    function __construct($start,$end,$subject) {
            $this->start = $start;
            $this->end = $end;
            $this->subject = $subject;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function view(): View
    {
        $inventory = VwPr::where('dept',Auth::user()->dept)->orderBy('pr_number', 'asc')->get();
        return view('elsa-assist/pr-export', [
            'datas' => $inventory
        ]);
    //    echo $this->start." 00:00:00".$this->end." 23:59:59".$this->subject;
    }
}