<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Exports\CompletenessComponent\ComponentListUnit;
use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialHasilOlah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ListComponent2ProductNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-component-unit') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    // COMPONENT LIST TO UNIT
    public function index(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-component-unit')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->id,
                        'menu'  => 'Report - List Component to Product Number',
                        'date'  => $date->toDateString()
                    ],
                    [
                        'time'  => $date->toTimeString()
                    ]
                );
    
                if ($request->kolom != null) {
                    $request->session()->put('kolom_list_unit', str_replace("%20", " ", htmlspecialchars($request->kolom)));
                }
                if ($request->hasil != null) {
                    $request->session()->put('hasil_list_unit', $request->hasil);
                }
    
                if ($request->reset == 1) {
                    $request->session()->forget('kolom_list_unit');
                    $request->session()->forget('hasil_list_unit');
                    return redirect('report-component-unit');
                }
                
                $data_list = MaterialHasilOlah::select('PLNBEZ as product_number', 'DESC_PLNBEZ as product_description', 'GroupProduct as group_product', 'MATNR as material_number', 'MAKTX as material_description', 'MTART as material_type', 'MATKL as material_group')->groupBy('MATNR', 'PLNBEZ');
                if ($request->session()->get('hasil_list_unit') != null) {
                    $db         = $data_list->whereIn($request->session()->get('kolom_list_unit'), $request->session()->get('hasil_list_unit'));
                    switch ($request->session()->get('kolom_list_unit')) {
                        case "PLNBEZ":
                            $nama_kolom = 'Product Number';
                            break;
                        case "AUFNR":
                            $nama_kolom = 'Production Order';
                            break;
                        case "GroupProduct":
                            $nama_kolom = 'Group Product';
                            break;
                        case "MATNR":
                            $nama_kolom = 'Material Number';
                            break;
                        case "MTART":
                            $nama_kolom = 'Material Type';
                            break;
                        case "MATKL":
                            $nama_kolom = 'Material Group';
                            break;
                        default:
                          echo "Component List to Product Number";
                    }
                    $title      = 'Component List Filter By '. $nama_kolom;
                } else {
                    $db         = $data_list;
                    $title      = 'Component List to Product Number';
                }
    
                $data = array(
                    'db'                    => $db->paginate(10),
                    'title'                 => $title,
                    'kolom'                 => $request->kolom,
                    'hasil'                 => $request->hasil,
                    'actionmenu'            => $this->PermissionActionMenu('report-component-unit'),
                    'actionmenu_pro'        => $this->PermissionActionMenu('production-order')
                );
                return view('completeness-component/report/list_component_unit')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function componentListUnitDownload(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-component-unit')->v == 1) {
                $kolom    = $request->session()->get('kolom_list_unit');
                $filter   = $request->session()->get('hasil_list_unit');
                return Excel::download(new ComponentListUnit($kolom, $filter), 'ComponentListToUnit.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function ComponentUnitOption(Request $request)
    {
        if ($request->component_unit_kolom != null) {
            $option = MaterialHasilOlah::select($request->component_unit_kolom)->distinct()->orderBy($request->component_unit_kolom, 'ASC')->get();
            foreach ($option as $opt) {
                $c[] = $opt[$request->component_unit_kolom];
            }
            echo json_encode($c);
        }      
    }

}
