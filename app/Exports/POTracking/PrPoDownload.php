<?php

namespace App\Exports\POTracking;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Table\PoTracking\PO;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\View\PoTracking\VwLeadtimePRPO;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PrPoDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{

    protected $month;
    protected $years;
    protected $datefilter;
    protected $potype;
    protected $prnumber;

    function __construct($month, $years, $datefilter, $potype, $prnumber) {
            $this->month    = $month;
            $this->years   = $years;
            $this->datefilter    = $datefilter;
            $this->potype   = $potype;
            $this->prnumber     = $prnumber;

    }

    public function headings(): array
    {
        return [

            "PR Number","PR Item","PR Create Date","PR Release Date","PO Create Date","PO Release Date","PR Release to PO Create","PO Create to PO Release","PO Create to PO Release","Total Leadtime",
            "PO Number","Item Number","Material","Description","Quantity","Vendor","Vendor Code","PO Creator","PO Type"
            ];
    }

    public function collection()
    {

        if($this->datefilter == "PR Create"){
            $date = 'PRCreateDate' ;
        }elseif($this->datefilter == "PR Release"){
            $date = 'PRReleaseDate' ;
        }elseif($this->datefilter == "PO Create"){
            $date = 'Date' ;
        }elseif($this->datefilter == "PO Release"){
            $date = 'ReleaseDate' ;
        }

        if($this->month!= null && $this->potype!= null && $this->prnumber!= null && $this->years!= null ){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereMonth("vw_leadtime_pr_po.$date", $this->month)->whereYear("vw_leadtime_pr_po.$date",$this->years)
            ->whereIn('vw_leadtime_pr_po.Type',$this->potype)->whereIn('vw_leadtime_pr_po.PRNumber',$this->prnumber)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif( $this->potype!= null && $this->prnumber!= null && $this->years!= null ){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereYear("vw_leadtime_pr_po.$date",$this->years)->whereIn('vw_leadtime_pr_po.Type',$this->potype)
            ->whereIn('vw_leadtime_pr_po.PRNumber',$this->prnumber)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif($this->month!= null  && $this->years!= null && $this->potype!= null ){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereMonth("vw_leadtime_pr_po.$date", $this->month)->whereYear("vw_leadtime_pr_po.$date",$this->years)->whereIn('vw_leadtime_pr_po.Type',$this->potype)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif( $this->potype!= null && $this->prnumber!= null ){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereIn('vw_leadtime_pr_po.Type',$this->potype)->whereIn('vw_leadtime_pr_po.PRNumber',$this->prnumber)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif($this->month!= null  && $this->years!= null){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereMonth("vw_leadtime_pr_po.$date", $this->month)->whereYear("vw_leadtime_pr_po.$date",$this->years)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif( $this->potype!= null ){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereIn('vw_leadtime_pr_po.Type',$this->potype)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif( $this->years!= null ){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereYear("vw_leadtime_pr_po.$date",$this->years)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }elseif($this->prnumber!= null){
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')
            ->whereIn('vw_leadtime_pr_po.PRNumber',$this->prnumber)->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }else{
            $getData = VwLeadtimePRPO::select('vw_po.PRNumber','vw_po.PRItem','vw_po.PRCreateDate','vw_po.PRReleaseDate','vw_leadtime_pr_po.Date','vw_leadtime_pr_po.ReleaseDate','vw_leadtime_pr_po.PRCreateToPRRelease',
            'vw_leadtime_pr_po.PRReleaseToPOCreate','vw_leadtime_pr_po.POCreateToPORelease','vw_leadtime_pr_po.TotalLeadtime','vw_leadtime_pr_po.Number','vw_po.ItemNumber','vw_po.Material','vw_po.Description','vw_po.Quantity','vw_po.Vendor','vw_po.VendorCode','vw_po.PurchaseOrderCreator','vw_leadtime_pr_po.Type',
            )
            ->join('vw_po', 'vw_po.PRNumber', '=', 'vw_leadtime_pr_po.PRNumber')->groupBy('Number','ItemNumber','PRNumber')
            ->orderBy('PRNumber','ASC')
            ->orderBy('PRItem','ASC')->get();
        }

        $sql = $getData;
        return collect($sql);
    }
}
