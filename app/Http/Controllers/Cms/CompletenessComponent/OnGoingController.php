<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Exports\CompletenessComponent\ProductionOrder\OnGoing;
use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\Inventory;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\Table\CompletenessComponent\Md_komentar;
use App\Models\View\CompletenessComponent\VwMaterialUnitOngoing;
use App\Models\View\CompletenessComponent\VwProductionOrder;
use App\Models\View\CompletenessComponent\VwProWithSN;
use App\Models\View\CompletenessComponent\VwTotalStock;
use App\Models\View\PoTracking\VwDataPOCCR;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class OnGoingController extends Controller
{
    public $status_ongoing = ['CRTD', 'REL', 'PDLV', 'DLV'];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('production-order') == 0) {
                return redirect()->back()->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function ProductionOrderInit()
    {
        try{
            $date   = Carbon::now();
            if ($this->PermissionActionMenu('production-order')->r == 1) {
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Index Production Order On Going',
                    'date'  => $date->toDateString()
                ], [
                    'time'  => $date->toTimeString()
                ]);
                
                $detail_Product_DB    = VwProWithSN::select('production_order', 'product_number', 'product_description', 'serial_number', 'status', 'group_product', 'date_status_created', 'quantity', 'sch_start_date', 'sch_finish_date', 'persen', 'persen_gi')
                        ->whereIn('status', $this->status_ongoing)
                        ->get();

                $data           = array(
                    'db'            => $detail_Product_DB,
                    'title'         => 'Production Order - On Going',
                    'actionmenu'    => $this->PermissionActionMenu('production-order')
                );
                return view('completeness-component/production-order/index_ongoing')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function ProductionOrderPlanning($pro)
    {
        try{
            if ($this->PermissionActionMenu('production-order')->r == 1) {
                $date = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'          => Auth::user()->id,
                    'menu'          => 'Production Order On Going',
                    'description'   => htmlspecialchars($pro),
                    'date'          => $date->toDateString()
                ], [
                    'time'          => $date->toTimeString()
                ]);

                $query      = VwProductionorder::select('production_order', 'product_number', 'product_description', 'quantity')->where('production_order', htmlspecialchars($pro))->first();
                if (!empty($query)) {
                    $pro_ongoing = VwProductionOrder::where('production_order', $pro)->whereIn('status', $this->status_ongoing)->first();
                    if (!empty($pro_ongoing)) {
                        //  get SN berdasarkan paginate
                        $sn         = VwProWithSN::select('production_order', 'serial_number', 'sch_start_date', 'quantity', 'persen')->whereIn('status', $this->status_ongoing)->where('production_order', htmlspecialchars($pro))->orderBy('sch_start_date', 'asc')->orderBy('production_order', 'asc')->orderBy('serial_number', 'asc')->paginate(5);
                        // Menambahkan index SN dari hasil paginate SN
                        $sn_all     = VwProWithSN::select('production_order', 'serial_number')->whereIn('status', $this->status_ongoing)->where('production_order', htmlspecialchars($pro))->orderBy('sch_start_date', 'asc')->orderBy('production_order', 'asc')->orderBy('serial_number', 'asc')->get();
                        $i = 0;
                        foreach ($sn_all as $ss) {
                            $dataSN = [
                                'production_order'  => $ss->production_order,
                                'serial_number'     => $ss->serial_number,
                                'sn_index'          => $i++
                            ];
                            $datall[] = $dataSN;
                        }
                        unset($dataSN);
                        unset($sn_all);
            
                        $material   = MaterialTemporary::select('material_temporary.id', 'material_temporary.AUFNR as production_order', 'GAMNG as quantity', 'material_temporary.MATNR as material_number', 'MAKTX as material_description', 'MEINS as base_unit', 'MTART as material_type', 'BDTER as requirement_date', 'BDMNG as requirement_quantity', 'ENMNG as good_issue', 'STOCK as stock', 'RESERVE as alokasi_stock', 'MINUS_PLOTTING as kekurangan_stock','total_open_qty', 'komentar')
                        ->leftJoin('satria_potracking.vw_sum_data_po_ccr', 'material_temporary.MATNR', '=', 'satria_potracking.vw_sum_data_po_ccr.material')
                        ->leftJoin('comments_material', function($join)
                         {
                             $join->on('material_temporary.MATNR', '=', 'comments_material.material_number');
                             $join->on('material_temporary.AUFNR', '=', 'comments_material.production_order');
                         })
                        ->where('material_temporary.AUFNR', htmlspecialchars($pro))
                        ->WhereNotNull('material_temporary.MATNR')
                        ->orderBy('BDTER', 'ASC')
                        ->get();

                        $komentar = Md_komentar::select('id', 'komentar')->where('status',1)->get();

                        $data       = array(
                            'apps'                  => $query,
                            'komentar'              => $komentar,
                            'sn'                    => $sn,
                            'sn_all'                => $datall,
                            'material'              => $material,
                            'title'                 => 'Planning Production Order',
                            'actionmenu'            => $this->PermissionActionMenu('production-order'),
                            'actionmenu_ticket'     => $this->PermissionActionMenu('ticket-component-ccr'),
                            'actionmenu_komentar'   => $this->PermissionActionMenu('ccr-komentar')
                        );
                        return view('completeness-component/production-order/planning_pro')->with('data', $data);
                    }else {
                        return redirect()->back()->with('error', 'View Production Order '.htmlspecialchars($pro).' in Production Order History Menu')->with('title', 'PRO Not Found in On Going!');
                    }
                }else {
                    return redirect()->back()->with('error', 'Production Order Not Found in Database')->with('title', 'PRO Not Found!');
                } 
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function detailMinus(Request $request)
    {
        try{
            if  ($request->matnrMinus != null) { // report Shortage
                $get        = Inventory::where('material_number', $request->matnrMinus)->first();
                $data      = MaterialTemporary::select('AUFNR', 'PLNBEZ', 'DESC_PLNBEZ', 'BDTER', 'BDMNG', 'ENMNG', 'RESERVE', 'MINUS_PLOTTING')
                    ->where('MATNR', $request->matnrMinus)
                    ->where('MINUS_PLOTTING', '<', 0)
                    ->orderBy('BDTER', 'ASC');
                if ($request->date != null) {
                    $date         = explode(" - ", $request->date);
                    $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]));
                    $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]));
                    $start_date   = $awal->modify('+14 days')->toDateString();
                    $end_date     = $akhir->modify('+14 days')->toDateString();
                    $minus        = $data->whereBetween('BDTER', [$start_date, $end_date])->get();
                }else {
                    $date       = Carbon::now();
                    $end_date   = $date->modify('+14 days')->toDateString();
                    $minus      = $data->where('BDTER', '<=', $end_date)->get();
                }
            } else {
                $get    = [];
                $minus  = [];
            }
            $data   = array(
                'desc'  => $get,
                'minus' => $minus
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function detailPlottingStock(Request $request)
    {
        try{
            if ($request->material_number != null) {
                $get        = Inventory::select('material_number', 'material_description')->where('material_number', $request->material_number)->first();
                $stock      = VwTotalStock::where('material_number', $request->material_number)->first();
                $minus      = MaterialTemporary::select('AUFNR as production_order', 'PLNBEZ as product_number', 'BDTER as requirement_date', 'BDMNG as requirement_quantity', DB::raw('ROUND(RESERVE, 3) as alokasi_stock, ROUND(RESTOCK, 3) as sisa_stock'))->where('MATNR', $request->material_number)->where('RESERVE', '>', 0)->orderBy('RESTOCK', 'DESC')->get();
            } else {
                $get    = [];
                $stock  = [];
                $minus  = [];
            }
            $data   = array(
                'desc'  => $get,
                'stock' => $stock,
                'minus' => $minus
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function ProductNumber($matnum)
    {
        try{
            if ($this->PermissionActionMenu('production-order')->r == 1) {
                $date = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'          => Auth::user()->id,
                    'menu'          => 'Product Number On Going',
                    'description'   => htmlspecialchars($matnum),
                    'date'          => $date->toDateString()
                ], [
                    'time'  => $date->toTimeString()
                ]);
                $description    = VwProWithSN::select('production_order', 'product_number', 'product_description', 'quantity')->groupBy('production_order')->whereIn('status', $this->status_ongoing)->where('deletion_flag', 0)->orderBy('sch_start_date', 'ASC')->orderBy('production_order', 'ASC')->where('product_number', htmlspecialchars($matnum))->get();
                if (!empty($description)) {
                    $sn             = VwProWithSN::select('production_order', 'serial_number', 'sch_start_date', 'quantity', 'persen')->whereIn('status', $this->status_ongoing)->where('product_number', htmlspecialchars($matnum))->orderBy('sch_start_date', 'asc')->orderBy('production_order', 'asc')->orderBy('serial_number', 'asc')->paginate(5);
                    $sn_all         = VwProWithSN::select('production_order', 'serial_number')->whereIn('status', $this->status_ongoing)->where('product_number', htmlspecialchars($matnum))->orderBy('sch_start_date', 'asc')->orderBy('production_order', 'asc')->orderBy('serial_number', 'asc')->get();
                    if ($sn_all->toArray() == []) {
                        return redirect()->back()->with('error', 'Product Number Not Found in Production Order On Going')->with('title', 'Product Number Not Found!');
                    } else {
                        $i = 0;
                        foreach ($sn_all as $ss) {
                            $dataSN = [
                                'production_order' => $ss->production_order,
                                'serial_number'    => $ss->serial_number,
                                'sn_index' => $i++
                            ];
                            $datall[] = $dataSN;
                        }
                        foreach ($sn as $sernr) {
                            $production_order[] = $sernr->production_order;
                        }
                        $material_unit      = VwMaterialUnitOngoing::leftJoin('satria_potracking.vw_sum_data_po_ccr', 'vw_material_unit_ongoing.material_number', '=', 'satria_potracking.vw_sum_data_po_ccr.material')
                            ->select('material_number', 'material_description', 'material_type', 'requirement_date', 'quantity', 'requirement_quantity', 'good_issue', 'stock', 'alokasi_stock', 'kekurangan_stock', 'total_open_qty')
                            ->where('product_number', htmlspecialchars($matnum))
                            ->get();
                        $material_list      = MaterialTemporary::
                            select('material_temporary.id', 'material_temporary.AUFNR as production_order', 'GAMNG as quantity', 'material_temporary.MATNR as material_number', 'BDTER as requirement_date', 'BDMNG as requirement_quantity', 'ENMNG as good_issue', 'RESERVE as alokasi_stock')
                            ->where('material_temporary.PLNBEZ', htmlspecialchars($matnum))
                            ->whereIn('material_temporary.AUFNR', $production_order)
                            ->WhereNotNull('material_temporary.MATNR')
                            ->get();
    
                        $data   = array(
                            'apps'          => $description,
                            'sn'            => $sn,
                            'sn_all'        => $datall,
                            'material'      => $material_unit,
                            'title'         => 'Planning Unit',
                            'material_list' => $material_list,
                            'actionmenu'            => $this->PermissionActionMenu('production-order'),
                            'actionmenu_komentar'   => $this->PermissionActionMenu('ccr-komentar')
                        );
                        return view('completeness-component/production-order/planning_unit_ongoing')->with('data', $data);
                    }         
                } else {
                    return redirect()->back()->with('error', 'Product Number Not Found in Production Order On Going')->with('title', 'Product Number Not Found!');
                }
                
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function ProductionOrderDownload()
    {
        try{
            if ($this->PermissionActionMenu('production-order')->v == 1) {
                return Excel::download(new OnGoing(), 'ProductionOrder-OnGoing.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    
    public function detailMinusUnit(Request $request)
    {
        try{
            $get        = MaterialTemporary::where('MATNR', $request->matnr)->where('PLNBEZ', $request->plnbez)->first();
            $first      = MaterialTemporary::select('id')->where('MATNR', $request->matnr)->where('PLNBEZ', $request->plnbez)->where('MINUS_PLOTTING', '<', 0)->orderBy('id', 'ASC')->first();

            if ($first == null) {
                $minus = [];
            } else {
                $minus      = MaterialTemporary::select('AUFNR', 'PLNBEZ', 'BDTER', 'BDMNG', DB::raw('ROUND(RESERVE, 3) as RESERVE, ROUND(RESTOCK, 3) as RESTOCK'))->where('MATNR', $get->MATNR)->where('RESERVE', '>', 0)->where('id', '<=', $first->id)->orderBy('RESTOCK', 'DESC')->get();
            }
            $data   = array(
                'desc'  => $get,
                'minus' => $minus
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
