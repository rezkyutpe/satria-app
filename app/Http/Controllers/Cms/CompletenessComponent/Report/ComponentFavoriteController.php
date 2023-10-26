<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialHasilOlah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ComponentFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-component-favorite') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    // COMPONENT FAVORITE
    public function index(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('report-component-favorite')->r == 1) {
                $date       = Carbon::now();
                $tanggal    = null;
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->id,
                        'menu'  => 'Report - Component Favorite',
                        'date'  => $date->toDateString()
                    ],
                    [
                        'time'  => $date->toTimeString()
                    ]
                );

                $data_material = MaterialHasilOlah::select('MATNR as material_number', 'MAKTX as material_description', 'MEINS as material_type', DB::raw('round(SUM(ENMNG), 3) as good_issue'))
                    ->where('STAT', '!=', 'DLFL')
                    ->groupBy('MATNR')
                    ->orderBy(DB::raw('SUM(ENMNG)'), 'DESC')
                    ->limit(10);
                
                if (isset($request->query()['search'])) {
                    $tanggal    = htmlspecialchars($request->query()['search']);
                    $date       = explode(" - ", $tanggal);
                    $start_date = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $end_date   = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');

                    $material   = $data_material->whereBetween('BDTER', [$start_date, $end_date])->get();
                    $title      = 'Component Favorite from ' . Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('d-m-Y') . ' to ' . Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('d-m-Y');
                } else {
                    $material   = $data_material->get();
                    $title      = 'Component Favorite';
                }

                $data       = array(
                    'tanggal'               => $tanggal,
                    'material'              => $material,
                    'title'                 => $title,
                    'actionmenu'            => $this->PermissionActionMenu('report-component-favorite')
                );
                return view('completeness-component/report/component_favorite')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        } 
    }
}
