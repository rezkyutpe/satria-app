<?php

namespace App\Exports\POTracking;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\View\PoTracking\VwKunjunganQc;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class KqcDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{


    protected $datefilter;
    protected $material;
    protected $vendor;
    protected $number;
    protected $searchby;

    function __construct($datefilter, $material, $vendor, $number, $searchby) {

            $this->datefilter   = $datefilter;
            $this->material    = $material;
            $this->vendor   = $vendor;
            $this->number     = $number;
            $this->searchby     = $searchby;


    }

    public function headings(): array
    {
        return [
            "Vendor Name","PO No","PO Item","Material No","Material Desc","Delivery Date Aggreed","Confirmed Date","Qty","Confirmed Qty","Planning QC","Req Date CCR"
            ];
    }

    public function collection()
    {
        if($this->searchby == "Planning QC"){
            $dates = 'DeliveryDate' ;
        }elseif($this->searchby == "Delivery Date"){
            $dates = 'PlanningQCDate' ;
        }else{
            $dates = 'req_date' ;
        }
        if($this->datefilter){
                $date         = explode(" - ", $this->datefilter);
                $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
        }

        if(($this->datefilter!= null ) && ($this->vendor!= null ) ){
            $data =  VwKunjunganQc::select('Vendor','Number','ItemNumber','Material','Description','DeliveryDate','ConfirmedDate','Quantity','ConfirmedQuantity','PlanningQCDate','req_date')->whereIn('Vendor',$this->vendor)->whereBetween("vw_kunjungan_qc.$dates", [$awal, $akhir])->orderBy('PlanningQCDate','DESC')->get();
          }elseif($this->datefilter!= null){
              $data =  VwKunjunganQc::select('Vendor','Number','ItemNumber','Material','Description','DeliveryDate','ConfirmedDate','Quantity','ConfirmedQuantity','PlanningQCDate','req_date')->whereBetween("vw_kunjungan_qc.$dates", [$awal, $akhir])->orderBy('PlanningQCDate','DESC')->get();
          }elseif($this->vendor!= null){
              $data =  VwKunjunganQc::select('Vendor','Number','ItemNumber','Material','Description','DeliveryDate','ConfirmedDate','Quantity','ConfirmedQuantity','PlanningQCDate','req_date')->whereIn('Vendor',$this->vendor)->orderBy('PlanningQCDate','DESC')->get();
           }elseif($this->number!= null ){
              $data =  VwKunjunganQc::select('Vendor','Number','ItemNumber','Material','Description','DeliveryDate','ConfirmedDate','Quantity','ConfirmedQuantity','PlanningQCDate','req_date')->whereIn('Number',$this->number)->orderBy('PlanningQCDate','DESC')->get();
          }elseif($this->material!= null){
              $data =  VwKunjunganQc::select('Vendor','Number','ItemNumber','Material','Description','DeliveryDate','ConfirmedDate','Quantity','ConfirmedQuantity','PlanningQCDate','req_date')->whereIn('Material',$this->material)->orderBy('PlanningQCDate','DESC')->get();
          }else{
              return redirect()->back()->with('err_message', 'Please Select Item!');
          }


        $sql = $data;
        return collect($sql);
    }
}
