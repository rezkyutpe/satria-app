<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('status-material') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function StatusInit()
    {
        try{
            if ($this->PermissionActionMenu('status-material')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Master Data Status',
                    'date'  => $date->toDateString()
                ],[
                    'time'  => $date->toTimeString()
                ]);
                $status = Status::get();
                $data = array(
                    'title'     => 'Status',
                    'status'    => $status,
                    'actionmenu'=> $this->PermissionActionMenu('status-material')
                );
                return view('completeness-component/master-data/status')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
