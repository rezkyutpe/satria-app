<?php

namespace App\Exports\POTracking;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Table\PoTracking\Po;
use App\Models\Table\PoTracking\Pdi;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class POCancelDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{

    protected $month;
    protected $years;
    protected $datefilter;
    protected $cancelby;
    protected $ponumber;

    function __construct($month, $years, $datefilter, $cancelby, $ponumber) {
            $this->month    = $month;
            $this->years   = $years;
            $this->datefilter    = $datefilter;
            $this->cancelby   = $cancelby;
            $this->ponumber     = $ponumber;

    }

    public function headings(): array
    {
        return [
            "Number","ItemNumber","Material","Description","Quantity","Delivery Date","Date","Release Date","Type","PO Creator","Name","Vendor Code","Vendor Type",
             "IsClosed"
            ];
    }

    public function collection()
    {
        if($this->datefilter == "Delivery Date"){
            $date = 'purchasingdocumentitem.DeliveryDate' ;
        }elseif($this->datefilter == "PO Date"){
            $date = 'po.Date' ;
        }elseif($this->datefilter == "PO ReleaseDate"){
            $date = 'po.ReleaseDate' ;
        }
        if($this->cancelby == "SAP"){
            $cancel = 'L' ;
        }elseif($this->cancelby == "PO Tracking"){
            $cancel = 'C' ;
        }
        if($this->month!= null && $this->cancelby!= null && $this->years!= null ){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereMonth($date, $this->month)->whereYear($date,$this->years)->whereIn('po.Number',$this->ponumber)->where('purchasingdocumentitem.IsClosed',$cancel)->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }elseif($this->cancelby != null && $this->ponumber!= null && $this->years!= null ){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereIn('po.Number',$this->ponumber)->where('purchasingdocumentitem.IsClosed',$cancel)->whereYear($date,$this->years)->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }elseif($this->cancelby != null && $this->ponumber!= null ){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereIn('po.Number',$this->ponumber)->where('purchasingdocumentitem.IsClosed',$cancel)->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }elseif($this->month!= null  && $this->years!= null){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereMonth($date, $this->month)->whereYear($date,$this->years)->whereIn('purchasingdocumentitem.IsClosed',['C','L'])->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
         }elseif( $this->cancelby != null ){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
           where('purchasingdocumentitem.IsClosed',$cancel)->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }elseif($this->ponumber!= null){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereIn('purchasingdocumentitem.IsClosed',['C','L'])->whereIn('po.Number',$this->ponumber)->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }elseif($this->years!= null){
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereIn('purchasingdocumentitem.IsClosed',['C','L'])->whereYear($date,$this->years)->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }else{
            $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select('po.Number','purchasingdocumentitem.ItemNumber','purchasingdocumentitem.Material','purchasingdocumentitem.Description',
            'purchasingdocumentitem.Quantity','purchasingdocumentitem.DeliveryDate','po.Date','po.ReleaseDate','po.Type','po.CreatedBy','uservendors.Name','uservendors.VendorCode','uservendors.VendorType',
            'purchasingdocumentitem.IsClosed')->
            whereIn('purchasingdocumentitem.IsClosed',['C','L'])->groupBy('po.Number','purchasingdocumentitem.ItemNumber')->get();
        }

        $sql = $getData;
        return collect($sql);
    }
}
