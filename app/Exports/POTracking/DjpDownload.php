<?php

namespace App\Exports\POTracking;
use Carbon\Carbon;
use App\Models\View\PoTracking\VwViewTicket;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DjpDownload implements FromView

{
    protected $id;
    function __construct($id) {

            $this->id       = $id;
    }

        public function view(): View
        {
            $kodex = ['A','D','S','W','Q','R','X'] ;
            if($this->id!= null ){
                $date         = explode(" - ", $this->id);
                $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                $data =  VwViewTicket::whereIn('status',$kodex)->whereBetween("DeliveryDate", [$awal, $akhir])->groupBy('Number','ItemNumber','TicketID')->orderBy('DeliveryDate','asc')->get();
            }else{
                $data =  VwViewTicket::whereIn('status',$kodex)->groupBy('Number','ItemNumber','TicketID')->orderBy('ID','DESC')->get();
            }
                return view("po-tracking/master/JadwalPengirimanExport", [
                'dB' => $data,

            ]);

        }

}
