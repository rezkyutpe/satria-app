<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Exports\CompletenessComponent\MaterialOpenShortlist\PO;
use App\Exports\CompletenessComponent\MaterialOpenShortlist\PRO;
use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Exception;

class MaterialOpenShortlistController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-material-open') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-material-open')->r == 1) {
                $date       = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->id,
                        'menu'  => 'Report - Material Open Shortlist',
                        'date'  => $date->toDateString()
                    ],
                    [
                        'time'  => $date->toTimeString()
                    ]
                );
    
                if ($request->kolom != null) {
                    $request->session()->put('kolom', $request->kolom);
                }

                if ($request->filter != null) {
                    $request->session()->put('filter', $request->filter);
                }

                if ($request->datefilter != null) {
                    $request->session()->put('datefilter', $request->datefilter);
                }

                if ($request->kolom1 != null) {
                    $request->session()->put('kolom1', $request->kolom1);
                }
                
                if ($request->filter1 != null) {
                    $request->session()->put('filter1', $request->filter1);
                }
        
                $data_material   = MaterialTemporary::where('MINUS_PLOTTING', '<', 0)
                    ->orderBy('BDTER', 'ASC')
                    ->orderBy('MATNR', 'ASC')
                    ->orderBy('MINUS_PLOTTING', 'ASC')
                    ->orderBy('PLNBEZ', 'ASC')
                    ->orderBy('AUFNR', 'ASC');
        
                if ($request->reset == 1) {
                    $request->session()->forget('kolom');
                    $request->session()->forget('filter');
                    $request->session()->forget('datefilter');
                    $request->session()->forget('kolom1');
                    $request->session()->forget('filter1');
                    return redirect('report-material-open');
                }
        
                if ($request->session()->get('kolom') == null && $request->session()->get('datefilter') == null) {
                    $date       = Carbon::now();
                    $end        = $date->modify('+14 days')->toDateString();
                    $material   = $data_material
                        ->select(
                            'PLNBEZ as product_number',
                            'DESC_PLNBEZ as product_description',
                            'AUFNR as production_order',
                            'MATNR as material_number',
                            'MAKTX as material_description',
                            'MEINS as base_unit',
                            'MTART as material_type',
                            'MATKL as material_group',
                            'BDTER as requirement_date',
                            'BDMNG as requirement_quantity',
                            'STOCK as stock',
                            'MINUS_PLOTTING as kekurangan_stock',
                            DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER <= '$end' GROUP BY MATNR) AS sum_kekurangan_stock"),
                            DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order")
                        )
                        ->where('BDTER', '<=', $end);
                    $title      = 'Material Open Shortlist before ' . Carbon::createFromFormat('Y-m-d', $end)->format('d-m-Y');
                } else {
                    if ($request->session()->get('kolom') != null && $request->session()->get('filter') != null) {
                        $material = $data_material->whereIn($request->session()->get('kolom'), $request->session()->get('filter'));
                    }
                    if ($request->session()->get('kolom1') != null && $request->session()->get('filter1') != null) {
                        $material = $data_material->whereIn($request->session()->get('kolom1'), $request->session()->get('filter1'));
                    }
        
                    if ($request->session()->get('datefilter') != null) {
                        $date         = explode(" - ", $request->session()->get('datefilter'));
                        $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]));
                        $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]));
                        $start_date   = $awal->modify('+14 days')->toDateString();
                        $end_date     = $akhir->modify('+14 days')->toDateString();

                        $material = $data_material
                            ->select(
                                'PLNBEZ as product_number',
                                'DESC_PLNBEZ as product_description',
                                'AUFNR as production_order',
                                'MATNR as material_number',
                                'MAKTX as material_description',
                                'MEINS as base_unit',
                                'MTART as material_type',
                                'MATKL as material_group',
                                'BDTER as requirement_date',
                                'STOCK as stock',
                                'MINUS_PLOTTING as kekurangan_stock',
                                DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER between '$start_date' AND '$end_date' GROUP BY MATNR) AS sum_kekurangan_stock"),
                                DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order")
                            )
                            ->whereBetween('BDTER', [$start_date, $end_date]);
                        $title      = 'Material Open Shortlist Between ' . Carbon::createFromFormat('Y-m-d', $start_date)->format('d-m-Y') . ' and ' . Carbon::createFromFormat('Y-m-d', $end_date)->format('d-m-Y');
                    } else {
                        $material = $data_material
                            ->select(
                                'PLNBEZ as product_number',
                                'DESC_PLNBEZ as product_description',
                                'AUFNR as production_order',
                                'MATNR as material_number',
                                'MAKTX as material_description',
                                'MEINS as base_unit',
                                'MTART as material_type',
                                'MATKL as material_group',
                                'BDTER as requirement_date',
                                'STOCK as stock',
                                'MINUS_PLOTTING as kekurangan_stock',
                                DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR GROUP BY MATNR) AS sum_kekurangan_stock"),
                                DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order")
                            );
                        $title      = 'Material Open Shortlist';
                    }
                }
                
                $data       = array(
                    'material'              => $material->paginate(10),
                    'title'                 => $title,
                    'actionmenu'            => $this->PermissionActionMenu('report-material-open'),
                    'actionmenu_pro'        => $this->PermissionActionMenu('production-order')
                );
                return view('completeness-component/report/material_open/pro')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function materialOpenShortlistDownload(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-material-open')->v == 1) {
                $kolom    = $request->session()->get('kolom');
                $filter   = $request->session()->get('filter');
                $kolom1   = $request->session()->get('kolom1');
                $filter1  = $request->session()->get('filter1');
                $date     = $request->session()->get('datefilter');
                return Excel::download(new PRO($kolom, $filter, $date, $kolom1, $filter1), 'MaterialOpenShortlist-PRO.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function MaterialOpenShortlistOption(Request $request)
    {
        // Untuk Option filter 1 pada Report Material Open Shortlist
        if ($request->kolom != null) {
            $db               = MaterialTemporary::select($request->kolom)->where('MINUS_PLOTTING', '<', 0)->distinct()->orderBy($request->kolom, 'ASC');
            if ($request->range_date != null) {
                $date         = explode(" - ", $request->range_date);
                $start_date   = Carbon::createFromFormat('d/m/Y', trim($date[0]))->modify('+14 days')->toDateString();
                $end_date     = Carbon::createFromFormat('d/m/Y', trim($date[1]))->modify('+14 days')->toDateString();
                $opsi         = $db->whereBetween('BDTER', [$start_date, $end_date])->get();
            }else {
                $opsi         = $db->get();
            }
            foreach ($opsi as $opt) {
                $b[]          = $opt[$request->kolom];
            }
            echo json_encode($b);
        }

        // Untuk Option filter 2 pada Report Material Open Shortlist
        if ($request->kolom_dua != null) { // untuk Material Open
            $db = MaterialTemporary::select($request->kolom_dua)->where('MINUS_PLOTTING', '<', 0)->whereIn($request->kolom_awal, $request->filter_awal)->distinct()->orderBy($request->kolom_dua, 'ASC');
            if ($request->range_date != null) {
                $date         = explode(" - ", $request->range_date);
                $start_date   = Carbon::createFromFormat('d/m/Y', trim($date[0]))->modify('+14 days')->toDateString();
                $end_date     = Carbon::createFromFormat('d/m/Y', trim($date[1]))->modify('+14 days')->toDateString();
                $opsi         = $db->whereBetween('BDTER', [$start_date, $end_date])->get();
            }else {
                $opsi   = $db->get();
            }
            if(empty($opsi->toArray())){
                $a = ["NULL"];
            }else {
                foreach ($opsi as $opt) {
                    $a[] = $opt[$request->kolom_dua];
                }
            }
            echo json_encode($a);
        }        
    }

    public function shortlistPO(Request $request)
    {
        if ($this->PermissionActionMenu('report-material-open')->r == 1) {
            $date       = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->id,
                    'menu'  => 'Report - Material Open Shortlist by PO',
                    'date'  => $date->toDateString()
                ],
                [
                    'time'  => $date->toTimeString()
                ]
            );

            if ($request->kolom_po != null) {
                $request->session()->put('kolom_po', $request->kolom_po);
            }

            if ($request->filter_po != null) {
                $request->session()->put('filter_po', $request->filter_po);
            }

            if ($request->datefilter_po != null) {
                $request->session()->put('datefilter_po', $request->datefilter_po);
            }

            if ($request->kolom1_po != null) {
                $request->session()->put('kolom1_po', $request->kolom1_po);
            }
            
            if ($request->filter1_po != null) {
                $request->session()->put('filter1_po', $request->filter1_po);
            }
    
            $data_material   = MaterialTemporary::Join('satria_potracking.vw_datapo_ccr', function($join)
                {
                    $join->on('material_temporary.MATNR', '=', 'satria_potracking.vw_datapo_ccr.Material');
                })
                ->where('MINUS_PLOTTING', '<', 0)
                ->groupBy('MATNR', 'Number', 'ItemNumber')
                ->orderBy('MATNR', 'ASC')
                ->orderBy('ConfirmedDate', 'ASC')
                ->orderBy('Number', 'ASC')
                ->orderBy('ItemNumber', 'ASC');
                
            if ($request->reset_po == 1) {
                $request->session()->forget('kolom_po');
                $request->session()->forget('filter_po');
                $request->session()->forget('datefilter_po');
                $request->session()->forget('kolom1_po');
                $request->session()->forget('filter1_po');
                return redirect('report-material-po');
            }
    
            if ($request->session()->get('kolom_po') == null && $request->session()->get('datefilter_po') == null) {
                $date       = Carbon::now();
                $end        = $date->modify('+14 days')->toDateString();
                $material   = $data_material
                    ->select(
                        'MATNR as material_number',
                        'MAKTX as material_description',
                        'MEINS as base_unit',
                        'MTART as material_type',
                        'MATKL as material_group',
                        'STOCK as stock',
                        DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER <= '$end' GROUP BY MATNR) AS sum_kekurangan_stock"),
                        DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order"),
                        'Name',
                        'Number',
                        'ItemNumber',
                        'OpenQuantity',
                        'ConfirmedDate',
                        'ReleaseDate',
                        'SecurityDate'
                    )
                    ->where('BDTER', '<=', $end);
    
                $title      = 'Material Open Shortlist PO before ' . Carbon::createFromFormat('Y-m-d', $end)->format('d-m-Y');
            } else {
                if ($request->session()->get('kolom_po') != null && $request->session()->get('filter_po') != null) {
                    $material = $data_material->whereIn($request->session()->get('kolom_po'), $request->session()->get('filter_po'));
                }
                if ($request->session()->get('kolom1_po') != null && $request->session()->get('filter1_po') != null) {
                    $material = $data_material->whereIn($request->session()->get('kolom1_po'), $request->session()->get('filter1_po'));
                }
    
                if ($request->session()->get('datefilter_po') != null) {
                    $date         = explode(" - ", $request->session()->get('datefilter_po'));
                    $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]));
                    $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]));
                    $start_date   = $awal->modify('+14 days')->toDateString();
                    $end_date     = $akhir->modify('+14 days')->toDateString();


                    $material = $data_material
                        ->select(
                            'MATNR as material_number',
                            'MAKTX as material_description',
                            'MEINS as base_unit',
                            'MTART as material_type',
                            'MATKL as material_group',
                            'STOCK as stock',
                            DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER between '$start_date' AND '$end_date' GROUP BY MATNR) AS sum_kekurangan_stock"),
                            DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order"),
                            'Name',
                            'Number',
                            'ItemNumber',
                            'OpenQuantity',
                            'ConfirmedDate',
                            'ReleaseDate',
                            'SecurityDate'
                        )
                        ->whereBetween('BDTER', [$start_date, $end_date]);
    
                    $title      = 'Material Open Shortlist PO Between ' . Carbon::createFromFormat('Y-m-d', $start_date)->format('d-m-Y') . ' and ' . Carbon::createFromFormat('Y-m-d', $end_date)->format('d-m-Y');
                } else {
                    $material = $data_material
                        ->select(
                            'MATNR as material_number',
                            'MAKTX as material_description',
                            'MEINS as base_unit',
                            'MTART as material_type',
                            'MATKL as material_group',
                            'STOCK as stock',
                            DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR GROUP BY MATNR) AS sum_kekurangan_stock"),
                            DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order"),
                            'Name',
                            'Number',
                            'ItemNumber',
                            'OpenQuantity',
                            'ConfirmedDate',
                            'ReleaseDate',
                            'SecurityDate'
                        );
                    $title      = 'Material Open Shortlist PO';
                }
            }
            // dd($material->get()->toArray());
            
            $data       = array(
                'material'              => $material->paginate(10),
                'title'                 => $title,
                'actionmenu'            => $this->PermissionActionMenu('report-material-po'),
                'actionmenu_pro'        => $this->PermissionActionMenu('production-order')
            );
            return view('completeness-component/report/material_open/po')->with('data', $data);
        } else {
            return redirect()->back()->with('error', 'Access denied!');
        }
    }
    
    public function materialOpenShortlistPODownload(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-material-open')->v == 1) {
                $kolom    = $request->session()->get('kolom_po');
                $filter   = $request->session()->get('filter_po');
                $kolom1   = $request->session()->get('kolom1_po');
                $filter1  = $request->session()->get('filter1_po');
                $date     = $request->session()->get('datefilter_po');
                return Excel::download(new PO($kolom, $filter, $date, $kolom1, $filter1), 'MaterialOpenShortlist-PO.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function materialOpenShortlistPOOption(Request $request)
    {
        if ($request->kolom != null) {
            $db               = MaterialTemporary::Join('satria_potracking.vw_datapo_ccr', function($join)
            {
                $join->on('material_temporary.MATNR', '=', 'satria_potracking.vw_datapo_ccr.Material');
            })
            ->distinct()
            ->select($request->kolom)
            ->where('MINUS_PLOTTING', '<', 0)
            ->orderBy($request->kolom, 'ASC');
            if ($request->range_date != null) {
                $date         = explode(" - ", $request->range_date);
                $start_date   = Carbon::createFromFormat('d/m/Y', trim($date[0]))->modify('+14 days')->toDateString();
                $end_date     = Carbon::createFromFormat('d/m/Y', trim($date[1]))->modify('+14 days')->toDateString();
                $opsi         = $db->whereBetween('BDTER', [$start_date, $end_date])->get();
            }else {
                $opsi         = $db->get();
            }

            if(empty($opsi->toArray())){
                $b = ["NULL"];
            }else {
                foreach ($opsi as $opt) {
                    $b[]          = $opt[$request->kolom];
                }
            }
            echo json_encode($b);
        }

        // Untuk Option filter 2 pada Report Material Open Shortlist PO
        if ($request->kolom_dua != null) { // untuk Material Open
            $db = MaterialTemporary::Join('satria_potracking.vw_datapo_ccr', function($join)
            {
                $join->on('material_temporary.MATNR', '=', 'satria_potracking.vw_datapo_ccr.Material');
            })
            ->distinct()
            ->select($request->kolom_dua)
            ->where('MINUS_PLOTTING', '<', 0)
            ->whereIn($request->kolom_awal, $request->filter_awal)
            ->orderBy($request->kolom_dua, 'ASC');
            
            if ($request->range_date != null) {
                $date         = explode(" - ", $request->range_date);
                $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]));
                $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]));
                $start_date   = $awal->modify('+14 days')->toDateString();
                $end_date     = $akhir->modify('+14 days')->toDateString();
                $opsi         = $db->whereBetween('BDTER', [$start_date, $end_date])->get();
            }else {
                $date   = Carbon::now();
                $end    = $date->modify('+14 days')->toDateString();
                $opsi   = $db->where('BDTER', '<=', $end)->get();
            }

            if(empty($opsi->toArray())){
                $a = ["NULL"];
            }else {
                foreach ($opsi as $opt) {
                    $a[] = $opt[$request->kolom_dua];
                }
            }
            echo json_encode($a);
        }  
    }

}
