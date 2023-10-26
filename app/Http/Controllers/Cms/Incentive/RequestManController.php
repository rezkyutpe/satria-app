<?php

namespace App\Http\Controllers\Cms\Incentive;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Http;
use PDF;
use App\Models\User;
use App\Models\Table\Incentive\Incentive;
use App\Models\Table\Incentive\AdjustmentHistory;
use App\Models\Table\Incentive\RequestInc;
use App\Models\View\Incentive\VwReqReport;
use App\Models\Table\Incentive\Customer;
use App\Models\Table\Incentive\CustType;
use App\Models\View\VwPermissionAppsMenu;
use App\Imports\IncentiveImport;

class RequestManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('requests-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function RequestMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('requests-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $requests = VwReqReport::where('sales',Auth::user()->email)->where('sales', 'like', "%" . $search. "%")->orderBy('updated_at', 'asc')->simplePaginate($paginate);
                $requests->appends(['search' => $search]);
            } else {
                $requests = VwReqReport::where('sales',Auth::user()->email)->orderBy('updated_at', 'asc')->simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($requests as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'requests' => $requests,
            'actionmenu' => $this->PermissionActionMenu('requests-management'),
            );

            return view('incentive/requests-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function RequestMgmtView($req)
    {
        if($this->PermissionActionMenu('requests-management')->v==1){
            
            $incentive = Incentive::where('request',$req)->where('sales',Auth::user()->email)->get();
            $requestinc = VwReqReport::where('id_req',$req)->where('sales',Auth::user()->email)->first();
            
            $no = 1;
            foreach($incentive as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'request' => $requestinc,
                'incentive' => $incentive,
                'actionmenu' => $this->PermissionActionMenu('requests-management'),
            );
            return view('incentive/requests-management/view')->with('data', $data);
        }else{
            return redirect('requests-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    
}
