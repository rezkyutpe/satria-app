<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Http\Controllers\Controller;
use App\Models\View\VwErrorLogs;
use Exception;

class LogErrorCCRController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('log-error-ccr') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    public function index()
    {
        try{
            if ($this->PermissionActionMenu('log-error-ccr')->r == 1) {
                $title      = 'Log Error';

                $log = VwErrorLogs::where('apps', 13)->where('created_at', '>', '2022-10-07 00:00:00')->orderBy('created_at', 'DESC')->get();
                // dd($log->toArray());

                $data       = array(
                    'data'       => $log,
                    'title'     => $title,
                    'actionmenu'=> $this->PermissionActionMenu('log-error-ccr')
                );
                return view('completeness-component/report/log_error/index_error_log')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
