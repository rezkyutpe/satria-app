<?php

namespace App\Exports\POTracking;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwHistoryall;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwVendorFavorite;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class VnFvDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{


    protected $searchbystatus;
    protected $datefilter;
    protected $searchbytype;
    protected $searchbyvendor;

    function __construct($datefilter,$searchbystatus, $searchbytype, $searchbyvendor) {

            $this->searchbystatus   = $searchbystatus;
            $this->datefilter    = $datefilter;
            $this->searchbytype   = $searchbytype;
            $this->searchbyvendor     = $searchbyvendor;


    }

    public function headings(): array
    {
        return [
            "Vendor","Vendor Code","PO Number","Item Number","Material","Description","Currency","NettPrice","TotalQuantity","TotalAmountForeign","TotalAmountIDR","PO ReleaseDate"
            ];
    }

    public function collection()
    {

        if($this->datefilter){
                $date         = explode(" - ", $this->datefilter);
                $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
        }
        if(!empty($this->searchbystatus == "New PO")){
               $status = VwnewpoAll::select('Number')->distinct()->get()->toArray() ;
        }elseif(!empty($this->searchbystatus == "Ongoing" )){
                $status = VwongoingAll::select('Number')->distinct()->get()->toArray() ;
        }elseif(!empty($this->searchbystatus == "History")){
                $status = VwHistoryall::select('Number')->distinct()->get()->toArray() ;
        }else{
                $status = VwPo::select('Number')->distinct()->get()->toArray() ;
        }
        if($this->datefilter!= null && $this->searchbystatus!= null && $this->searchbytype!= null && $this->searchbyvendor!= null ){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereIn('vw_vendor_favorite.Number',$status)->whereIn('vw_vendor_favorite.VendorName',$this->searchbyvendor)->where('vw_vendor_favorite.VendorType',$this->searchbytype)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif( $this->datefilter!= null && $this->searchbystatus!= null && $this->searchbytype!= null ){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereIn('vw_vendor_favorite.Number',$status)->where('vw_vendor_favorite.VendorType',$this->searchbytype)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif($this->datefilter!= null  && $this->searchbystatus!= null ){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereIn('vw_vendor_favorite.Number',$status)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif( $this->datefilter!= null && $this->searchbytype!= null ){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->where('vw_vendor_favorite.VendorType',$this->searchbytype)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif($this->searchbystatus!= null  && $this->searchbytype!= null){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereIn('vw_vendor_favorite.Number',$status)->where('vw_vendor_favorite.VendorType',$this->searchbytype)
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif( $this->searchbytype!= null ){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->where('vw_vendor_favorite.VendorType',$this->searchbytype)
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif( $this->searchbyvendor!= null ){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereIn('vw_vendor_favorite.VendorName',$this->searchbyvendor)
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif($this->datefilter!= null){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }elseif($this->searchbystatus!= null){
            $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->whereIn('vw_vendor_favorite.Number',$status)
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }else{
             $getData = VwVendorFavorite::selectRaw('vw_vendor_favorite.VendorName,vw_vendor_favorite.VendorCode,vw_vendor_favorite.Number,vw_vendor_favorite.ItemNumber,vw_vendor_favorite.Material,vw_vendor_favorite.Description,vw_vendor_favorite.Currency,vw_vendor_favorite.NetPrice,vw_vendor_favorite.TotalQuantity,sum(vw_vendor_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_vendor_favorite.Currency != "IDR" THEN vw_vendor_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_vendor_favorite.TotalAmount) END as totals,vw_vendor_favorite.ReleaseDate')
            ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_vendor_favorite.Currency')
            ->groupBy('vw_vendor_favorite.Material','vw_vendor_favorite.Currency','vw_vendor_favorite.VendorName','vw_vendor_favorite.Number')
            ->orderBy('Material','DESC')
            ->orderBy('Currency','DESC')->get();
        }


        $sql = $getData;
        return collect($sql);
    }
}
