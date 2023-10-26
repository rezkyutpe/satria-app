<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Report;

use App\Exports\CompletenessComponent\ReqDateComponent;
use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\View\CompletenessComponent\VwReqDateComponent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ReqDateComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-date-component') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            } 
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-date-component')->r == 1) {
                $date        = Carbon::now();
                $tanggal    = null;
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Report - Req. Date Component',
                    'date'  => $date->toDateString()
                ], [
                    'time'  => $date->toTimeString()
                ]);

                if ($request->datefilter != null) {
                    $request->session()->put('tanggal_req_date', $request->datefilter);
                }

                if ($request->reset == 1) {
                    $request->session()->forget('tanggal_req_date');
                    return redirect('report-date-component');
                }
    
                if ($request->session()->get('tanggal_req_date') != null) {
                    $tanggal = htmlspecialchars($request->session()->get('tanggal_req_date'));
                    $date  = explode(" - ", $tanggal);
                    $now   = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $end   = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    $title = 'Required Date Component Between ' . Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('d-m-Y') . ' and ' . Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('d-m-Y');
                } else {
                    $now      = $date->toDateString();
                    $end      = $date->modify('+14 days')->toDateString();
                    $title     = 'Required Date Component Between ' . Carbon::createFromFormat('Y-m-d', $now)->format('d-m-Y') . " and " . Carbon::createFromFormat('Y-m-d', $end)->format('d-m-Y');
                }
    
                $component = VwReqDateComponent::whereBetween('requirement_date', [$now, $end])->orderBy('requirement_date', 'ASC')->orderBy('reservation_number', 'ASC')->paginate(10);
                $data       = array(
                    'tanggal'       => $tanggal,
                    'component'     => $component,
                    'title'         => $title,
                    'actionmenu'    => $this->PermissionActionMenu('report-date-component')
                );
                return view('completeness-component/report/req_date_component')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function reqDateComponentDownload(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('report-date-component')->v == 1) {
                $reqDate    = $request->session()->get('tanggal_req_date');
                return Excel::download(new ReqDateComponent($reqDate), 'ReqDateComponent.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
