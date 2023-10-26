<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Http\Controllers\Controller;
use App\Exports\CompletenessComponent\ProductionOrder\GroupProduct;
use App\Exports\CompletenessComponent\ProductionOrder\GroupProductStatus;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\ProductionOrder;
use App\Models\Table\CompletenessComponent\ReportGI;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class DashboardController extends Controller
{
    public $stat_ongoing = ['CRTD', 'REL', 'DLV', 'PDLV'];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('completeness-component') == 0) {
                return redirect()->back()->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function Dashboard_v1()
    {
        try{
            if ($this->PermissionActionMenu('completeness-component')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Dashboard',
                    'date'  => $date->toDateString()
                ],[
                    'time'  => $date->toTimeString()
                ]);
    
                $groupProduct = VwProWithSN::select('GroupProduct', DB::raw("COUNT(AUFNR) as total"))->whereIn('STAT', $this->stat_ongoing)->where('XLOEK', 0)->groupBy('GroupProduct')->get();
                
                $data_groupProduct = [];
                
                foreach ($groupProduct as $chart) {
                    $data_groupProduct[] = [
                        "name"  => $chart->GroupProduct,
                        "y"     => (int) $chart->total,
                        "key"  => str_replace("&", "^", str_replace(" ", "_", $chart->GroupProduct))
                    ];
                }
                unset($groupProduct);
                unset($date);
                
                $data = array(
                    'chart'  => json_encode($data_groupProduct),
                    'title' => 'Dashboard',
                    'title_product' => 'Group Product',
                );
                return view('completeness-component/dashboard/index_v1')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function Dashboard()
    {
        try{
            if ($this->PermissionActionMenu('completeness-component')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Dashboard',
                    'date'  => $date->toDateString()
                ],[
                    'time'  => $date->toTimeString()
                ]);
                
                $status_ongoing = ['CRTD', 'REL', 'PDLV', 'DLV'];
                foreach ($status_ongoing as $status) {
                    $data_per_status    = [];

                    // get jumlah SN pada tiap group product
                    $groupProductStatus = VwProWithSN::select('group_product', DB::raw("COUNT(production_order) as total"), 'status')->distinct()->where('deletion_flag', 0)->where('status', $status)->groupBy('group_product')->orderBy('group_product', 'ASC')->get();

                    foreach ($groupProductStatus as $chart) {
                        $data_per_status[] = (object)[
                            "name"      => $chart->group_product,
                            "y"         => (int) $chart->total,
                            "drilldown" => str_replace("&", "^", str_replace(" ", "_", $chart->group_product)).'-'.$status
                        ];
                    }

                    // data untuk dashboard group product by status
                    $data_all_status_ongoing[] = (object)[
                        "name" => $status,
                        "data" => $data_per_status
                    ];

                    // get data group product on going
                    $group_product = ProductionOrder::select('GroupProduct')->distinct()->where('STAT', $status)->orderBy('GroupProduct', 'ASC')->get();
                    
                    // Data untuk dashboard Report GI Component
                    foreach ($group_product as $gp) {
                        $data_gi_ongoing_status = [];

                        $data_gi =  ReportGI::leftJoin('production_order', function($join)
                        {
                            $join->on('report_gi.production_order', '=', 'production_order.AUFNR');
                        })
                        ->select(
                            'GroupProduct', 
                            'STAT', 
                            DB::raw('SUM(gi_zcom/req_qty_zcom)*100 / COUNT(production_order) AS ZCOM'), 
                            DB::raw('SUM(gi_zbup/req_qty_zbup)*100 / COUNT(production_order) AS ZBUP'), 
                            DB::raw('SUM(gi_zcns/req_qty_zcns)*100 / COUNT(production_order) AS ZCNS'), 
                            DB::raw('SUM(gi_zraw/req_qty_zraw)*100 / COUNT(production_order) AS ZRAW'))
                        ->where('GroupProduct', $gp->GroupProduct)
                        ->where('STAT', $status)
                        ->groupBy('GroupProduct', 'STAT')
                        ->get();
                        
                        foreach ($data_gi as $gi) {
                            // ZCOM
                            $data_gi_ongoing_status[] = (object)[
                                'color' => '#B4FF9F',
                                'id'    => 'ZCOM'.'-'.str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct)).'-'.$gi->STAT,
                                'name'  => 'ZCOM',
                                'y'     => round($gi->ZCOM, 2),
                                'url'   => url("report-gi-component?status=".$gi->STAT."&group_product_dashboard=".str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct))),
                            ];
                            // ZBUP
                            $data_gi_ongoing_status[] = (object)[
                                'color' => '#F9FFA4',
                                'id'    => 'ZBUP'.'-'.str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct)).'-'.$gi->STAT,
                                'name'  => 'ZBUP',
                                'y'     => round($gi->ZBUP, 2),
                                'url'   => url("report-gi-component?status=".$gi->STAT."&group_product_dashboard=".str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct))),
                            ];
                            // ZCNS
                            $data_gi_ongoing_status[] = (object)[
                                'color' => '#FFD59E',
                                'id'    => 'ZCNS'.'-'.str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct)).'-'.$gi->STAT,
                                'name'  => 'ZCNS',
                                'y'     => round($gi->ZCNS, 2),
                                'url'   => url("report-gi-component?status=".$gi->STAT."&group_product_dashboard=".str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct))),
                            ];
                            // ZRAW
                            $data_gi_ongoing_status[] = (object)[
                                'color' => '#FFA1A1',
                                'id'    => 'ZRAW'.'-'.str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct)).'-'.$gi->STAT,
                                'name'  => 'ZRAW',
                                'y'     => round($gi->ZRAW, 2),
                                'url'   => url("report-gi-component?status=".$gi->STAT."&group_product_dashboard=".str_replace("&", "^", str_replace(" ", "_", $gi->GroupProduct))),
                            ];
                        }
                        $data_gi_all[] = [
                            'tooltip'    => (object)['pointFormat' => 'GI Percentage: {point.y} %<br/>'],
                            'dataLabels' => (object)[
                                'enabled'   => true,
                                'format'    => '{point.y:.f}%' 
                            ],
                            'id'         => str_replace("&", "^", str_replace(" ", "_", $gp->GroupProduct)).'-'.$status,
                            'name'       => $gp->GroupProduct.'-'.$status,
                            'data'       => $data_gi_ongoing_status
                        ];
                    }
                }
        
                $data = array(
                    'dataStatusOnGoing'     => json_encode($data_all_status_ongoing),
                    'dataGIOnGoing'         => json_encode($data_gi_all),
                    'title'                 => 'Dashboard',
                    'title_product'         => 'Group Product',
                );
                return view('completeness-component/dashboard/index')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function GroupProductDetail(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('completeness-component')->r == 1 && $this->PermissionActionMenu('completeness-component')->v == 1) {
                // untuk data pada dashboard group product by status bagian label 
                if ($request->groupProduct != null) {
                    // replace ^ menjadi & , %20 dan _ menjadi spasi
                    $group_product        = str_replace('^', "&", str_replace("%20", " ", str_replace("_", " ", htmlspecialchars($request->groupProduct))));
                    // get data pro berdasarkan group product
                    $detail_Product_DB    = VwProWithSN::select('production_order', 'product_number', 'product_description', 'serial_number', 'status', 'group_product', 'date_status_created', 'quantity', 'sch_start_date', 'sch_finish_date', 'persen', 'persen_gi')
                        ->where("group_product", $group_product)
                        ->whereIn('status', $this->stat_ongoing)
                        ->get();
                    $data                 = array(
                        'db'                    => $detail_Product_DB,
                        'title'                 => "Production Order On Going - Group Product : ". $group_product,
                        'group_product'         => $group_product,
                        'status'                => 0,
                        'actionmenu_pro'        => $this->PermissionActionMenu('production-order'),
                        'actionmenu-report-gi'  => $this->PermissionActionMenu('report-gi-component'),
                    );
                    return view('completeness-component/dashboard/detail_group_product')->with('data', $data);
                }
                // untuk data pada dashboard group product by status bagian per status
                elseif ($request->drilldown != null) {
                    $parameter            = explode('-', $request->drilldown);
                    // replace ^ menjadi & , %20 dan _ menjadi spasi
                    $groupProduct         = str_replace('^', "&", str_replace("%20", " ", str_replace("_", " ", htmlspecialchars($parameter[0]))));
                    $status               = $parameter[1];
                    $detail_Product_DB    = VwProWithSN::select('production_order', 'product_number', 'product_description', 'serial_number', 'status', 'group_product', 'date_status_created', 'quantity', 'sch_start_date', 'sch_finish_date', 'persen', 'persen_gi')
                        ->where("group_product", $groupProduct)
                        ->where('status', $status)
                        ->get();
                    $data                 = array(
                        'db'                    => $detail_Product_DB,
                        'title'                 => "Production Order On Going - Group Product : ". $groupProduct. " - Status : ".$status,
                        'group_product'         => $groupProduct,
                        'status'                => $status,
                        'actionmenu_pro'        => $this->PermissionActionMenu('production-order'),
                        'actionmenu-report-gi'  => $this->PermissionActionMenu('report-gi-component'),
                    );
                    return view('completeness-component/dashboard/detail_group_product')->with('data', $data);
                }else {
                    return redirect()->back()->with('error', 'Access denied!');
                }
            }else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function GroupProductDownload(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-material-open')->v == 1) {
                
                $parameter            = explode('-', $request->filter);
                if ($parameter[1] == '0') {
                    $group_product = $parameter[0];
                    return Excel::download(new GroupProduct($group_product), 'ProductionOrderOnGoing-GroupProduct.xlsx');
                } else {
                    $group_product  = $parameter[0];
                    $status         = $parameter[1];
                    return Excel::download(new GroupProductStatus($group_product, $status), 'MaterialOpenShortlist-GroupProduct-Status.xlsx');
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
