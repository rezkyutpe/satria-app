<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Exports\CompletenessComponent\ProductionOrder\History;
use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\Md_komentar;
use App\Models\View\CompletenessComponent\VwMaterialListHistory;
use App\Models\View\CompletenessComponent\VwMaterialUnitHistory;
use App\Models\View\CompletenessComponent\VwProductionOrder;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class HistoryController extends Controller
{
    public $status_history = ['TECO', 'CLSD'];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('production-order-history') == 0) {
                return redirect()->back()->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function POHistoryInit()
    {
        try{
            if ($this->PermissionActionMenu('production-order-history')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Index Production Order History',
                    'date'  => $date->toDateString()
                ], [
                    'time'  => $date->toTimeString()
                ]);
                
                $detail_Product_DB    = VwProWithSN::select('production_order', 'product_number', 'product_description', 'serial_number', 'status', 'group_product', 'date_status_created', 'quantity', 'sch_start_date', 'sch_finish_date', 'persen', 'persen_gi')
                        ->whereIn('status', $this->status_history)
                        ->get();
                
                $data           = array(
                    'db'            => $detail_Product_DB,
                    'title'         => 'Production Order - History',
                    'actionmenu'    => $this->PermissionActionMenu('production-order-history'),
                );
                return view('completeness-component/production-order/index_history')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function detailPRO(Request $request)
    {
        try{
            $pro    = VwProductionOrder::select('production_order', 'nip', 'nama_creator', 'product_number', 'product_description', 'group_product', 'sch_start_date', 'sch_finish_date', 'create_date_pro', 'act_release', 'quantity', 'status', 'date_status_created')->where('production_order', htmlspecialchars($request->id))->first();
            $sn     = VwProWithSN::select('serial_number', 'persen')->where('production_order', htmlspecialchars($request->id))->get();
            $data   = array(
                'data_pro'      => $pro,
                'serial_number' => $sn,
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function detailSN(Request $request)
    {
        try{
            $sn    = VwProWithSN::select('production_order', 'serial_number', 'nip', 'nama_creator', 'product_number', 'product_description', 'group_product', 'sch_start_date', 'sch_finish_date', 'create_date_pro', 'act_release', 'quantity', 'status', 'date_status_created')->where('serial_number', htmlspecialchars($request->id))->first();
            
            $data   = array(
                'data_sn'      => $sn
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function ProductionOrderPlanningHistory($pro)
    {
        try{
            if ($this->PermissionActionMenu('production-order-history')->r == 1) {
                $date = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'          => Auth::user()->id,
                    'menu'          => 'Production Order History',
                    'description'   => $pro,
                    'date'          => $date->toDateString()
                ], [
                    'time'          => $date->toTimeString()
                ]);
                
                $desc       = VwProductionOrder::select('production_order', 'product_number', 'quantity', 'product_description')
                    ->whereIn('status', $this->status_history)
                    ->where('production_order', $pro)
                    ->first();
                if (!empty($desc)) {
                    $sn     = VwProWithSN::select('production_order', 'serial_number', 'sch_start_date', 'quantity', 'persen')
                        ->whereIn('status', $this->status_history)
                        ->where('production_order', $pro)
                        ->orderBy('sch_start_date', 'asc')
                        ->orderBy('production_order', 'asc')
                        ->orderBy('serial_number', 'asc')
                        ->paginate(5);
                    $sn_all = VwProWithSN::select('production_order', 'serial_number')
                        ->whereIn('status', $this->status_history)
                        ->where('production_order', $pro)
                        ->orderBy('sch_start_date', 'asc')
                        ->orderBy('production_order', 'asc')
                        ->orderBy('serial_number', 'asc')
                        ->get();
                    $i      = 0;
                    foreach ($sn_all as $ss) {
                        $dataSN = [
                            'production_order'     => $ss->production_order,
                            'serial_number'        => $ss->serial_number,
                            'sn_index'  => $i++
                        ];
                        $datall[] = $dataSN;
                    }
        
                    $material   = VwMaterialListHistory::select('id', 'material_number', 'material_description', 'material_type', 'requirement_quantity', 'good_issue', 'requirement_date', 'quantity', 'komentar')
                        ->where('production_order', $pro)
                        ->get();
                    $komentar = Md_komentar::select('id', 'komentar')->where('status',1)->get();
                    
                    $data   = array(
                        'apps'                  => $desc,
                        'sn'                    => $sn,
                        'sn_all'                => $datall,
                        'material'              => $material,
                        'komentar'              => $komentar,
                        'title'                 => 'Planning Production Order History',
                        'actionmenu'            => $this->PermissionActionMenu('production-order-history'),
                        'actionmenu_komentar'   => $this->PermissionActionMenu('ccr-komentar')
                    );
                    return view('completeness-component/production-order/planning_history')->with('data', $data);
                }else {
                    return redirect()->back()->with('error', 'Production Order Not Found in Production Order History')->with('title', 'PRO Not Found!');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function PlanningUnitHistory($matnum)
    {
        try{
            if ($this->PermissionActionMenu('production-order-history')->r == 1) {
                $date = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'          => Auth::user()->id,
                    'menu'          => 'Product Number History',
                    'description'   => $matnum,
                    'date'          => $date->toDateString()
                ], [
                    'time'          => $date->toTimeString()
                ]);

                $pro            = VwProWithSN::select('production_order', 'product_number', 'product_description', 'quantity')
                    ->where('product_number', $matnum)
                    ->whereIn('status', $this->status_history)
                    ->where('deletion_flag', 0)
                    ->groupBy('production_order')
                    ->orderBy('sch_start_date', 'ASC')
                    ->orderBy('serial_number', 'ASC')
                    ->get();
                if (!empty($pro)) {
                    $sn             = VwProWithSN::select('production_order', 'serial_number', 'sch_start_date', 'quantity', 'persen')
                        ->whereIn('status', $this->status_history)
                        ->where('product_number', $matnum)
                        ->orderBy('sch_start_date', 'asc')
                        ->orderBy('production_order', 'asc')
                        ->orderBy('serial_number', 'asc')
                        ->paginate(5);
                    $sn_all         = VwProWithSN::select('production_order', 'serial_number')->whereIn('status', $this->status_history)->where('product_number', $matnum)->orderBy('sch_start_date', 'asc')->orderBy('production_order', 'asc')->orderBy('serial_number', 'asc')->get();
                    $i              = 0;
                    foreach ($sn_all as $ss) {
                        $dataSN = [
                            'production_order'     => $ss->production_order,
                            'serial_number'        => $ss->serial_number,
                            'sn_index'              => $i++
                        ];
                        $datall[] = $dataSN;
                    }
        
                    foreach ($sn as $sernr) {
                        $production_order[] = $sernr->production_order;
                    }
                    $material       = VwMaterialUnitHistory::distinct()
                        ->where('product_number', $matnum)
                        ->whereNotNull('material_number')
                        ->get();
                    $material_list  = VwMaterialListHistory::select('id', 'production_order', 'quantity', 'material_number', 'requirement_date', 'requirement_quantity', 'good_issue', 'komentar')
                        ->distinct()
                        ->whereIn('production_order', $production_order)
                        ->where('product_number', $matnum)
                        ->WhereNotNull('material_number')
                        ->orderBy('requirement_date', 'ASC')
                        ->get();
        
                    $data           =
                        array(
                            'apps'                  => $pro,
                            'sn'                    => $sn,
                            'sn_all'                => $datall,
                            'material'              => $material,
                            'material_list'         => $material_list,
                            'title'                 => 'Planning Unit History',
                            'actionmenu'            => $this->PermissionActionMenu('production-order-history'),
                            'actionmenu_komentar'   => $this->PermissionActionMenu('ccr-komentar')
                        );
                    return view('completeness-component/production-order/planning_unit_history')->with('data', $data);
                }else {
                    return redirect()->back()->with('error', 'Product Number Not Found in Production Order History')->with('title', 'Product Number Not Found!');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function POHistoryDownload()
    {
        try{
            if ($this->PermissionActionMenu('production-order-history')->v == 1) {
                return Excel::download(new History(), 'ProductionOrder-History.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
