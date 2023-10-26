<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Models\View\Elsa\VwTicketHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class TicketExport implements FromView, WithColumnFormatting
{
    protected $start;
    protected $end;
    protected $startdef;
    protected $enddef;
    protected $subject;

    function __construct($start, $end, $startdef, $enddef, $subject)
    {
        $this->start = $start;
        $this->end = $end;
        $this->startdef = $startdef;
        $this->enddef = $enddef;
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
        if (isset($this->start) && $this->subject == 1) {

            $ticket = VwTicketHistory::where('dept', Auth::user()->dept)->whereIn('subject', $this->subject)->whereBetween('created_at', [$this->start . " 00:00:00", $this->end . " 23:59:59"])->orderBy('created_at', 'asc')->get();
        } else if (isset($this->start)) {
            $ticket = VwTicketHistory::where('dept', Auth::user()->dept)->whereBetween('created_at', [$this->start . " 00:00:00", $this->end . " 23:59:59"])->orderBy('created_at', 'asc')->get();
        } else if (isset($this->subject)) {
            $ticket = VwTicketHistory::where('dept', Auth::user()->dept)->whereIn('subject', $this->subject)->orderBy('created_at', 'asc')->get();
        } else {
            $ticket = VwTicketHistory::where('dept', Auth::user()->dept)->whereBetween('created_at', [$this->startdef . " 00:00:00", $this->enddef . " 23:59:59"])->orderBy('created_at', 'desc')->get();
        }
        return view('dashboard/ticket-export', [
            'datas' => $ticket
        ]);
        //    echo $this->start." 00:00:00".$this->end." 23:59:59".$this->subject;
    }
}
