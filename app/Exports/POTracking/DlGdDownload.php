<?php

namespace App\Exports\POTracking;


use App\Models\View\PoTracking\VwStatusDelivery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;
class DlGdDownload implements FromView,WithColumnFormatting

{
    protected $searchbyvendor;
    protected $searchbytype;
    protected $status;

    function __construct($searchbytype, $searchbyvendor,$status) {
            $this->searchbytype    = $searchbytype;
            $this->searchbyvendor   = $searchbyvendor;
            $this->status   = $status;

    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_TEXT,

        ];
    }
    public function view(): View
    {
        if($this->status == 1){
            $ss = 'DESC';
        }else{
            $ss = 'ASC';
        }
        if($this->searchbyvendor!= null && $this->searchbytype!= null){
            $data = VwStatusDelivery::whereIn('VendorName',$this->searchbyvendor)->where('VendorType',$this->searchbytype)
            ->whereNotNull('VendorName')->orderBy('performance',"$ss")->orderBy('totalStatusDelivery','DESC')->limit(10)->get();
         }elseif($this->searchbyvendor!= null){
            $data = VwStatusDelivery::whereIn('VendorName',$this->searchbyvendor)->whereNotNull('VendorName')->orderBy('performance',"$ss")->orderBy('totalStatusDelivery','DESC')->limit(10)->get();
         }elseif($this->searchbytype!= null){
            $data = VwStatusDelivery::where('VendorType',$this->searchbytype)->whereNotNull('VendorName')->orderBy('performance',"$ss")->orderBy('totalStatusDelivery','DESC')->limit(10)->get();
         }else{
            $data = VwStatusDelivery::whereNotNull('VendorName')->orderBy('performance',"$ss")->orderBy('totalStatusDelivery','DESC')->limit(10)->get();
        }


        return view('po-tracking/master/StatusDeliveryExport', [
            'dB' => $data,
            'status' => $this->status,
        ]);

    }



}
