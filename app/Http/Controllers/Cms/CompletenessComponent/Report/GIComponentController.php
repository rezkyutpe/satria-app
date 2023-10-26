<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\Table\CompletenessComponent\Md_komentar;
use App\Models\Table\CompletenessComponent\ReportGI;
use App\Models\Table\CompletenessComponent\ProductionOrder;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class GIComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-gi-component') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-gi-component')->r == 1) {
                $date = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Report - Good Issue Component',
                    'date'  => $date->toDateString()
                ], [
                    'time'  => $date->toTimeString()
                ]);
                
                // mengubah string menjadi array
                if (is_string($request->status)) {
                    $request->status = explode(" ", $request->status);
                }
                if (is_string($request->group_product_dashboard)) {
                    $gp = explode(" ", $request->group_product_dashboard);
                    $request->group_product = str_replace("_", " ", str_replace("^", "&", $gp));
                }

                // mengubah request menjadi session
                if ($request->status != null) {
                    $request->session()->put('kolom_status_gi', $request->status);
                }
                if ($request->group_product != null) {
                    $request->session()->put('group_product_gi', $request->group_product);
                }
                
                // reset == hapus session
                if ($request->reset == 1) {
                    $request->session()->forget('kolom_status_gi');
                    $request->session()->forget('group_product_gi');
                    return redirect('report-gi-component');
                }
    
                $database = ReportGI::leftJoin('production_order', function($join)
                        {
                            $join->on('report_gi.production_order', '=', 'production_order.AUFNR');
                        })
                        ->select(
                            'report_gi.*', 
                            'production_order.MATNR as material_number', 
                            'production_order.MAKTX as material_description', 
                            'production_order.STAT as status', 
                            'production_order.GroupProduct as group_product', 
                            'production_order.DATE_STAT_CREATED as date_status_created')
                        ->distinct();
                $grup_produk = str_replace("^", "&", str_replace("_", " ", $request->session()->get('group_product_gi')));

                if ($request->session()->get('kolom_status_gi') != null && $request->session()->get('group_product_gi') != null) {
                    $db = $database->whereIn('STAT', $request->session()->get('kolom_status_gi'))->whereIn('GroupProduct', $grup_produk)->get();
                } else {
                    if ($request->session()->get('kolom_status_gi') != null) {
                        $db = $database->whereIn('STAT', $request->session()->get('kolom_status_gi'))->get();
                    } elseif ($grup_produk != null) {
                        $db = $database->whereIn('GroupProduct', $grup_produk)->get();
                    } else {
                        $db = $database->get();
                    }
                }

                $group_product = MaterialTemporary::select('GroupProduct')->distinct()->orderBy('GroupProduct', 'ASC')->get();

                $data       = array(
                    'item'          => $db,
                    'group_product' => $group_product,
                    'title'         => 'Good Issue Component',
                    'actionmenu'    => $this->PermissionActionMenu('report-gi-component')
                );
                return view('completeness-component/report/gi_component/index')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function giComponentDetailPRO($pro, Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-gi-component')->r == 1) {
                $date = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'          => Auth::user()->id,
                    'menu'          => 'Report - Detail Good Issue Component by PRO',
                    'description'   => $pro,
                    'date'          => $date->toDateString()
                ], [
                    'time'          => $date->toTimeString()
                ]);
                
                $query      = ProductionOrder::select(
                                'AUFNR as production_order', 
                                'MATNR as product_number', 
                                'MAKTX as product_description', 
                                'PSMNG as quantity', 
                                'persen_gi', 
                                'persen_alokasi')
                            ->where('AUFNR', $pro)
                            ->first();
                
                if ($request->type == '') {
                    $mat_type = ['ZCOM', 'ZBUP', 'ZCNS', 'ZRAW'];
                    $persen = MaterialTemporary::select(DB::raw('SUM(ENMNG)*100/SUM(BDMNG) as gi, SUM(RESERVE)*100/SUM(BDMNG) as alokasi'))->where('AUFNR', $pro)->first();
                    $title = 'Detail Group Product - Production Order : '.$pro;
                } else {
                    $mat_type[] = $request->type;
                    if ($request->type == 'ZCOM') {
                        $tipe = DB::raw('gi_zcom*100/req_qty_zcom as gi, allocated_zcom*100/req_qty_zcom as alokasi');
                    }elseif ($request->type == 'ZBUP') {
                        $tipe = DB::raw('gi_zbup*100/req_qty_zbup as gi, allocated_zbup*100/req_qty_zbup as alokasi');
                    }elseif ($request->type == 'ZCNS') {
                        $tipe = DB::raw('gi_zcns*100/req_qty_zcns as gi, allocated_zcns*100/req_qty_zcns as alokasi');
                    }elseif ($request->type == 'ZRAW') {
                        $tipe = DB::raw('gi_zraw*100/req_qty_zraw as gi, allocated_zraw*100/req_qty_zraw as alokasi');
                    }
                    $persen = ReportGI::select($tipe)->where('production_order', $pro)->first();
                    $title = 'Detail Group Product - Production Order : '.$pro.' - Material Type : '. $request->type;
                }               
                
                if (!empty($query)) {
                    //  get SN berdasarkan paginate
                    $sn       = VwProWithSN::select('production_order', 'serial_number', 'sch_start_date', 'quantity', 'persen')
                        ->where('production_order', $pro)
                        ->orderBy('sch_start_date', 'asc')
                        ->orderBy('production_order', 'asc')
                        ->orderBy('serial_number', 'asc')
                        ->paginate(5);
                    // Menambahkan index SN dari hasil paginate SN
                    $sn_all   = VwProWithSN::select('production_order', 'serial_number')
                        ->where('production_order', $pro)
                        ->orderBy('sch_start_date', 'asc')
                        ->orderBy('production_order', 'asc')
                        ->orderBy('serial_number', 'asc')
                        ->get();
                    $i        = 0;
                    foreach ($sn_all as $ss) {
                        $dataSN = [
                            'production_order'     => $ss->production_order,
                            'serial_number'        => $ss->serial_number,
                            'sn_index'  => $i++
                        ];
                        $datall[] = $dataSN;
                    }
                    unset($dataSN);
                    unset($sn_all);
        
                    $material   = MaterialTemporary::leftJoin('comments_material', function($join)
                        {
                            $join->on('material_temporary.MATNR', '=', 'comments_material.material_number');
                            $join->on('material_temporary.AUFNR', '=', 'comments_material.production_order');
                        })
                        ->select(
                            'material_temporary.id', 
                            'material_temporary.AUFNR as production_order', 
                            'material_temporary.MATNR as material_number', 
                            'MAKTX as material_description', 
                            'MTART as material_type', 
                            'BDTER as requirement_date', 
                            'BDMNG as requirement_quantity', 
                            'ENMNG as good_issue', 
                            'RESERVE as allocated', 
                            'STOCK as stock', 
                            'komentar')
                        ->where('material_temporary.AUFNR', $pro)
                        ->whereIn('material_temporary.MTART', $mat_type)
                        ->WhereNotNull('material_temporary.MATNR')
                        ->orderBy('material_temporary.BDTER', 'ASC')
                        ->get();

                    $komentar = Md_komentar::select('id', 'komentar')->where('status',1)->get();
        
                    $data       = array(
                        'apps'                  => $query,
                        'persen'                => $persen,
                        'komentar'              => $komentar,
                        'sn'                    => $sn,
                        'sn_all'                => $datall,
                        'material'              => $material,
                        'title'                 => $title,
                        'actionmenu'            => $this->PermissionActionMenu('report-gi-component'),
                        'actionmenu_komentar'   => $this->PermissionActionMenu('ccr-komentar'),
                        'actionmenu_material'   => $this->PermissionActionMenu('material-ccr'),
                    );
                    return view('completeness-component/report/gi_component/detail_pro')->with('data', $data);
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

    public function giComponentDetailUnit($unit, Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-gi-component')->r == 1) {
                $status           = $request->session()->get('kolom_status_gi');
                $group_product    = $request->session()->get('group_product_gi');

                $desc             = MaterialTemporary::select('AUFNR as production_order', 'PLNBEZ as material_number', 'DESC_PLNBEZ as material_description', 'GAMNG as quantity')
                    ->groupBy('AUFNR')
                    ->orderBy('sch_start_date', 'ASC')
                    ->orderBy('AUFNR', 'ASC')
                    ->where('PLNBEZ', $unit);
                
                if ($group_product != null) {
                    $desc_gp      = $desc->whereIn('GroupProduct', $group_product);
                }else {
                    $desc_gp      = $desc;
                }
                
                if ($status == null) {
                    $description  = $desc_gp->get();
                } else {
                    $description  = $desc_gp->whereIn('STAT', $status)->get();
                }
                
                if (!empty($description)) {
                    $material = MaterialTemporary::select(
                            'MATNR as material_number', 
                            'MAKTX as material_description', 
                            'MTART as material_type', 
                            DB::raw('SUM(ENMNG) as sum_good_issue'), 
                            DB::raw('SUM(RESERVE) as sum_allocated'), 
                            DB::raw('SUM(BDMNG) as sum_requirement_quantity'))
                        ->where('PLNBEZ', $unit)
                        ->groupBy('PLNBEZ', 'MATNR')
                        ->orderBy('MTART', 'ASC');
                    
                    if ($status == null) {
                        $material_unit = $material->get();
                    } else {
                        $material_unit = $material->whereIn('STAT', $status)->get();
                    }

                    $data   = array(
                        'apps'                  => $description,
                        'material'              => $material_unit,
                        'title'                 => 'Report Good Issue Component by Unit',
                        'actionmenu'            => $this->PermissionActionMenu('report-gi-component'),
                        'actionmenu_material'   => $this->PermissionActionMenu('material-ccr')
                    );
                    return view('completeness-component/report/gi_component/detail_unit')->with('data', $data);
                } else {
                    return redirect()->back()->with('error', 'Product Number Not Found');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
