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
use App\Models\Table\Incentive\RequestInc;
use App\Models\Table\Incentive\Customer;
use App\Models\Table\Incentive\CustType;
use App\Models\View\VwPermissionAppsMenu;
use App\Imports\IncentiveImport;

class SalesIncManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('sales-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function SalesIncMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('sales-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $incentive = Incentive::where('sales',Auth::user()->email)->where('cash_date', 'like', "%" . $search. "%")->orderBy('inv', 'asc')->simplePaginate($paginate);
                $incentive->appends(['search' => $search]);
            } else {
                $incentive = Incentive::where('sales',Auth::user()->email)->orderBy('inv', 'asc')->simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($incentive as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'incentive' => $incentive,
            'actionmenu' => $this->PermissionActionMenu('sales-management'),
            );

            return view('incentive/sales-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function SalesIncMgmtView($pack)
    {
        if($this->PermissionActionMenu('sales-management')->v==1){
            $incentive = MstPackage::where('incentive', $pack)->get();
            
            $data = array(
            'incentive' => $incentive,
            'packagename' => $pack
            );
        // echo $count;
            return view('incentive/sales-management/view-incentive')->with('data', $data);
        }else{
            return redirect('sales-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    
    public function SalesIncMgmtSubmit(Request $request)
    {
        
        if($this->PermissionActionMenu('sales-management')->u==1){
            $user =  User::where('id',Auth::user()->id)->first();
            $reqinc =  RequestInc::where('req_month','like', "%" . $request->date. "%")->first();
            if (empty($user)) {
                return redirect('sales-management')->with('err_message', 'User Unauthorised');
            }else{
                if (empty($reqinc)) {
                    Incentive::where('sales', Auth::user()->email)->where('cash_date','like', "%" . $request->date. "%")
                        ->update([
                        'request'=>'ICT'.Auth::user()->email.date('Ymd',strtotime($request->date.'-01')),
                        'status' => 1,
                    ]);
                    RequestInc::insert([
                        'id_req'=>'ICT'.Auth::user()->email.date('Ymd',strtotime($request->date.'-01')),
                        'sales'=>Auth::user()->email,
                        'req_month'=>$request->date.'-01',
                        'created_by'=> Auth::user()->id,
                    ]);
                    
                    return redirect()->back()->with('suc_message', 'Data telah disubmit!');
                }else{
                    return redirect('sales-management')->with('err_message', 'Incentive '.$request->date.' already submit');
                }
                    
            }
        }else{
            return redirect('sales-management')->with('err_message', 'Akses Ditolak!');
        }
    }

}
