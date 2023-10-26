<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\logCSV;
use Exception;

class LogCSVController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('log-csv-ccr') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    // LOG CSV
    public function index()
    {
        try{
            if ($this->PermissionActionMenu('log-csv-ccr')->r == 1) {
                $title      = 'Log CSV';

                $log = logCSV::orderBy('id', 'DESC')->where('start', '>', '2022-10-07 16:00:00')->limit(100)->get();

                $data       = array(
                    'csv'       => $log,
                    'title'     => $title,
                    'actionmenu'=> $this->PermissionActionMenu('log-ccr')
                );
                return view('completeness-component/report/log_csv')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
