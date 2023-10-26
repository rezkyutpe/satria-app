<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\Inventory;
use App\Models\Table\CompletenessComponent\LogHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('material-ccr') == 0) {
                return redirect()->back()->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function MaterialInit()
    {
        try{
            if ($this->PermissionActionMenu('material-ccr')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Master Data Material',
                    'date'  => $date->toDateString()
                ],[
                    'time'  => $date->toTimeString()
                ]);

                $material = Inventory::select('material_number', 'material_description', 'material_type', 'material_group', 'base_unit')->distinct()->get();

                $data = array(
                    'title' => 'Material',
                    'material' => $material,
                    'actionmenu' => $this->PermissionActionMenu('material-ccr')
                );
                return view('completeness-component/master-data/material')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
      
}
