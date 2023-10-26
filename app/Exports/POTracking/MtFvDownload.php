<?php

namespace App\Exports\POTracking;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwHistoryall;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwMaterialFavorite;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MtFvDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{


    protected $searchbystatus;
    protected $datefilter;
    protected $searchbytype;
    protected $searchbymaterial;

    function __construct($datefilter,$searchbystatus, $searchbytype, $searchbymaterial) {

            $this->searchbystatus   = $searchbystatus;
            $this->datefilter    = $datefilter;
            $this->searchbytype   = $searchbytype;
            $this->searchbymaterial     = $searchbymaterial;


    }

    public function headings(): array
    {
        return [
            "Material","Description","Currency","NettPrice","TotalQuantity","TotalAmountForeign","TotalAmountIDR","PO Number","Item Number","Vendor","Vendor Code","PO ReleaseDate"
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
        $getData =  VwMaterialFavorite::where('VendorCode',Auth::user()->email)->first();
        if (isset($getData)) {
            if($this->datefilter!= null && $this->searchbystatus!= null && $this->searchbytype!= null && $this->searchbymaterial!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereIn('vw_material_favorite.Number',$status)->whereIn('vw_material_favorite.Material',$this->searchbymaterial)->where('vw_material_favorite.VendorType',$this->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->datefilter!= null && $this->searchbystatus!= null && $this->searchbytype!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereIn('vw_material_favorite.Number',$status)->where('vw_material_favorite.VendorType',$this->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->datefilter!= null  && $this->searchbystatus!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereIn('vw_material_favorite.Number',$status)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->datefilter!= null && $this->searchbytype!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->where('vw_material_favorite.VendorType',$this->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->searchbystatus!= null  && $this->searchbytype!= null){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereIn('vw_material_favorite.Number',$status)->where('vw_material_favorite.VendorType',$this->searchbytype)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->searchbytype!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->where('vw_material_favorite.VendorType',$this->searchbytype)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->searchbymaterial!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereIn('vw_material_favorite.Material',$this->searchbymaterial)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->datefilter!= null){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->searchbystatus!= null){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->whereIn('vw_material_favorite.Number',$status)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }else{
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorCode',Auth::user()->email)->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }
         }else{
            if($this->datefilter!= null && $this->searchbystatus!= null && $this->searchbytype!= null && $this->searchbymaterial!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereIn('vw_material_favorite.Number',$status)->whereIn('vw_material_favorite.Material',$this->searchbymaterial)->where('vw_material_favorite.VendorType',$this->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->datefilter!= null && $this->searchbystatus!= null && $this->searchbytype!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereIn('vw_material_favorite.Number',$status)->where('vw_material_favorite.VendorType',$this->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->datefilter!= null  && $this->searchbystatus!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereIn('vw_material_favorite.Number',$status)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->datefilter!= null && $this->searchbytype!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorType',$this->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->searchbystatus!= null  && $this->searchbytype!= null){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereIn('vw_material_favorite.Number',$status)->where('vw_material_favorite.VendorType',$this->searchbytype)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->searchbytype!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->where('vw_material_favorite.VendorType',$this->searchbytype)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif( $this->searchbymaterial!= null ){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereIn('vw_material_favorite.Material',$this->searchbymaterial)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->datefilter!= null){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }elseif($this->searchbystatus!= null){
                $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->whereIn('vw_material_favorite.Number',$status)
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }else{
                 $getData = VwMaterialFavorite::selectRaw('vw_material_favorite.Material,vw_material_favorite.Description,vw_material_favorite.Currency,vw_material_favorite.NetPrice,vw_material_favorite.TotalQuantity,sum(vw_material_favorite.TotalAmount) as TotalAmount,CASE WHEN vw_material_favorite.Currency != "IDR" THEN vw_material_favorite.totalAmount * kurs.KursTengah ELSE sum(vw_material_favorite.TotalAmount) END as totals,vw_material_favorite.Number,vw_material_favorite.ItemNumber,vw_material_favorite.VendorName,vw_material_favorite.VendorCode,vw_material_favorite.ReleaseDate')
                ->leftjoin('kurs', 'kurs.MataUang', '=', 'vw_material_favorite.Currency')
                ->groupBy('vw_material_favorite.Material','vw_material_favorite.Currency','vw_material_favorite.VendorName')
                ->orderBy('Material','DESC')
                ->orderBy('Currency','DESC')->get();
            }
        }

        $sql = $getData;
        return collect($sql);
    }
}
