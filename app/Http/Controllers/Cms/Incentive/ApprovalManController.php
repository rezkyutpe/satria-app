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

class ApprovalManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('approval-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function ApprovalMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('approval-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'], $request->query()['month'])){
                $search = $request->query()['search'];
                $month = $request->query()['month'];
                $approval = VwReqReport::where('status',2)->where('sales', 'like', "%" . $search. "%")->where('updated_at', 'like', "%" . $month. "%")->orderBy('updated_at', 'asc')->simplePaginate($paginate);
                $approval->appends(['search' => $search]);
            }else if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $approval = VwReqReport::where('sales', 'like', "%" . $search. "%")->orderBy('updated_at', 'asc')->simplePaginate($paginate);
                $approval->appends(['search' => $search]);
            }else if (isset($request->query()['month'])){
                $month = $request->query()['month'];
                $approval = VwReqReport::where('status',2)->where('updated_at', 'like', "%" . $month. "%")->orderBy('updated_at', 'asc')->simplePaginate($paginate);
                $approval->appends(['month' => $month]);
            } else {
                $approval = VwReqReport::orderBy('updated_at', 'asc')->simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($approval as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'approval' => $approval,
            'actionmenu' => $this->PermissionActionMenu('approval-management'),
            );

            return view('incentive/approval-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ApprovalMgmtView($req)
    {
        if($this->PermissionActionMenu('approval-management')->v==1){
            
            $incentive = Incentive::where('request',$req)->get();
            $requestinc = VwReqReport::where('id_req',$req)->first();
            
            $no = 1;
            foreach($incentive as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'request' => $requestinc,
                'incentive' => $incentive,
                'actionmenu' => $this->PermissionActionMenu('approval-management'),
            );
            return view('incentive/approval-management/view')->with('data', $data);
        }else{
            return redirect('approval-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ApprovalMgmtAdjust(Request $request)
    {
        if($this->PermissionActionMenu('approval-management')->u==1){
            $inc = Incentive::where('id', $request->id)->first();
            if(!empty($inc)){
                $update = Incentive::where('id', $request->id)
                ->update([
                    'inc_ef' => $request->inc_ef, 
                    'updated_by'=> Auth::user()->id,
                ]);
                AdjustmentHistory::insert([
                    'inc'=> $request->id,
                    'inc_ef_old'=>$request->inc_efold,
                    'inc_ef_new'=>$request->inc_ef,
                    'created_by'=> Auth::user()->id,
                ]);
                if($update){
                    return  redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Inv Tidak ditemukan!');
            }
        }else{
            return  redirect()->back()->with('err_message', 'Akses Ditolak!');
        }    
    }
    public function ApprovalMgmtAccept(Request $request)
    {
        
        if($this->PermissionActionMenu('approval-management')->u==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('approval-management')->with('err_message', 'User Unauthorised');
            }else{
                RequestInc::where('id_req', $request->id)
                    ->update([
                    'accepted'=>Auth::user()->id,
                    'accepted_date'=>date('Y-m-d H:i:s'),
                    'status' => 1,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diaccept!');
            }
        }else{
            return redirect('approval-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ApprovalMgmtApprove(Request $request)
    {
        
        if($this->PermissionActionMenu('approval-management')->c==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('approval-management')->with('err_message', 'User Unauthorised');
            }else{
                RequestInc::where('id_req', $request->id)
                    ->update([
                    'approved'=>Auth::user()->id,
                    'approved_date'=>date('Y-m-d H:i:s'),
                    'status' => 2,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diapprove!');
            }
        }else{
            return redirect('approval-management')->with('err_message', 'Akses Ditolak!');
        }
    }
     public function ExportReq(Request $request)
    {
      if($this->PermissionActionMenu('approval-management')->v==1){
        $approval = VwReqReport::where('status',2)->where('updated_at', 'like', "%" . $request->date. "%")->orderBy('updated_at', 'asc')->get();
   
        $data = array(
          'approval' => $approval,
        );
          
        $pdf = PDF::loadView('incentive/approval-management/export', $data);
        
        // return view('po-non-sap/po-management/myPDF')->with('data', $data);
        return $pdf->stream();
      }else{
        return redirect('approval-management')->with('err_message', 'Akses Ditolak!');
      }
    }
}
