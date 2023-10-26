<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\View\CompletenessComponent\VwProductionOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-product') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    // PRODUCT FAVORITE
    public function index(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-product')->r == 1) {
                $date       = Carbon::now();
                $tanggal    = null;
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->id,
                        'menu'  => 'Report - Product Favorite',
                        'date'  => $date->toDateString()
                    ],
                    [
                        'time'  => $date->toTimeString()
                    ]
                );
    
                $data_material   = VwProductionOrder::select('product_number', 'product_description', 'group_product', DB::raw('SUM(quantity) as quantity'))
                    ->groupBy('product_number')
                    ->orderBy('quantity', 'DESC')
                    ->limit(10);
    
                if (isset($request->query()['search'])) {
                    $tanggal    = htmlspecialchars($request->query()['search']);
                    $date       = explode(" - ", $tanggal);
                    $start_date = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $end_date   = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    $material   = $data_material->whereBetween('sch_start_date', [$start_date, $end_date])->get();
                    $title      = 'Product Favorite from ' . Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('d-m-Y') . ' to ' . Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('d-m-Y');
                } else {
                    $title      = 'Product Favorite';
                    $material   = $data_material->get();
                }
    
                $data       = array(
                    'material'      => $material,
                    'tanggal'       => $tanggal,
                    'title'         => $title,
                    'actionmenu'    => $this->PermissionActionMenu('report-product'),
                    'actionmenu_pro'=> $this->PermissionActionMenu('production-order')
                );
                return view('completeness-component/report/product_favorite')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        } 
    }
}
