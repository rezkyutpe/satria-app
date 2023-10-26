<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\View\CompletenessComponent\VwLogUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class LogUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('log-ccr') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    // LOG USER
    public function index(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('log-ccr')->r == 1) {
                $date       = Carbon::now();
                $tanggal    = null;
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->id,
                        'menu'  => 'Report - Log User',
                        'date'  => $date->toDateString()
                    ],
                    [
                        'time'  => $date->toTimeString()
                    ]
                );
    
                if (isset($request->query()['search'])) {
                    $tanggal    = htmlspecialchars($request->query()['search']);
                    $date       = explode(" - ", $tanggal);
                    $start      = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $end        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    $title      = 'Log User History from ' . Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('d-m-Y') . ' to ' . Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('d-m-Y');
                } else {
                    $end        = $date->toDateString();
                    $start      = $date->modify('-3 month')->toDateString();
                    $title      = 'Log User History';
                }
                // User 120 Pak Heru, 91 Bayu
                $log        = VwLogUser::select('name', 'menu', 'date', 'time', 'description', 'division', 'department')->whereNotIn('user', [120, 91])->whereBetween('date', [$start, $end])->groupBy('user', 'menu', 'date')->orderBy('date', 'DESC')->orderBy('time', 'DESC')->get();
                $data       = array(
                    'tanggal'   => $tanggal,
                    'log_user'  => $log,
                    'title'     => $title,
                    'actionmenu'=> $this->PermissionActionMenu('log-ccr')
                );
                return view('completeness-component/report/log_user')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
