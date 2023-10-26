<?php

namespace App\Http\Controllers\Cms\Qfd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\MstApps;
use App\Models\Table\Qfd\MstProcess;
use App\Models\Table\Qfd\TrxMat;
use App\Models\Table\Qfd\TrxMatDetail;
use App\Models\Table\Qfd\MstSapMat;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Qfd\TrxBomDetail;
use App\Models\Table\Qfd\TrxBomHistory;
class QfdApproveManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('qfd-approval') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function QfdApproveMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('qfd-approval')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $trxmat = TrxMat::where('status','>=',1)->where('material_description', 'like', "%" . $search. "%")->orderBy('created_at', 'desc')->simplePaginate($paginate);
                $trxmat->appends(['search' => $search]);
            } else {
                $trxmat = TrxMat::where('status','>=',1)->orderBy('created_at', 'desc')->simplePaginate($paginate);
            }
           
            $no = 1;
            foreach($trxmat as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'trxmat' => $trxmat,
                'actionmenu' => $this->PermissionActionMenu('qfd-approval'),
            );
            return view('qfd/qfd-approval/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function QfdApproveMgmtView($id)
    {
      if($this->PermissionActionMenu('qfd-approval')->u==1){
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetail::where('trx_material', $id)->get();
            // $mstsap = MstSapMat::where('smt_name', 'like', "%" . 'A1000000'. "%")->orderBy('smt_desc', 'asc')->get();
            // $process = MstProcess::orderBy('nama', 'asc')->get();
            // $response = Http::get('http://webportal.patria.co.id/apisunfish/allempMagang.php');
            // $arr = json_decode($response,true);
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
                'actionmenu' => $this->PermissionActionMenu('qfd-approval'),
              // 'process' => $process,
              // 'mstsap' => $mstsap,
              // 'emp' => $arr['emp'],
            );
          // echo $count;
            return view('qfd/qfd-approval/view')->with('data', $data);
      }else{
        return redirect('qfd-approval')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function QfdApproveMgmtAccept(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-approval')->c==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('qfd-approval')->with('err_message', 'User Unauthorised');
            }else{
                TrxMat::where('id', $request->id)
                    ->update([
                    'accepted'=>Auth::user()->id,
                    'accepted_date'=>date('Y-m-d H:i:s'),
                    'status' => 1,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diapprove!');
            }
        }else{
            return redirect('qfd-approval')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function QfdApproveMgmtRevice(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-approval')->c==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('qfd-approval')->with('err_message', 'User Unauthorised');
            }else{
                TrxMat::where('id', $request->id)
                    ->update([
                    'status' => 3,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diapprove!');
            }
        }else{
            return redirect('qfd-approval')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function QfdApproveMgmtApprove(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-approval')->c==1){
            $trxmat = TrxMat::where('id', $request->id)->first();
            $user =  User::where('id',Auth::user()->id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $request->id)->get();
            
            if (empty($user)) {
                return redirect('qfd-approval')->with('err_message', 'User Unauthorised');
            }else{
                foreach($trxmatdetail as $trx){
                    if(!empty($trx->pic_email)){
                    $this->send($trx->pic,'QFD Assigment Process '.$trx->id_proses,$trx->pic_email,'Your are assign to procces '.$trx->id_proses.' of material '.$trx->id_mat.' - '.$trxmat->material_description.', '.$trx->from.' to '.$trx->to.' with remark '.$trx->remark);
                    }
                }
                TrxMat::where('id', $request->id)
                    ->update([
                    'approved'=>Auth::user()->id,
                    'approved_date'=>date('Y-m-d H:i:s'),
                    'status' => 2,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diapprove!');
            }
        }else{
            return redirect('qfd-approval')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function QfdApproveMgmtReject(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-approval')->c==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('qfd-approval')->with('err_message', 'User Unauthorised');
            }else{
                TrxMat::where('id', $request->id)
                    ->update([
                    'status' => 4,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diapprove!');
            }
        }else{
            return redirect('qfd-approval')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function QfdEmailAttandence($id)
    {
        
        // if($this->PermissionActionMenu('qfd-approval')->c==1){
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomHistory::where('trx_material', $id)->whereIn('conditions',['Updated','Added'])->get();
            
            if (empty($trxmat->attendance)) {
                return redirect()->back()->with('err_message', 'No Attendace Found');
            }else{
                // print_r(explode(",",$trxmat->attendance));
                $sent = $this->sendMom($trxmat->attendance,'QFD Mom '.$trxmat->material_number, explode(",",$trxmat->attendance),$trxmat->material_number,$trxmat->material_description,$trxmat->no_so,$trxmat->cust,$trxmat->qty,$trxmat->note,$trxmat->req_deliv_date,$trxmatdetail,$trxbomdetail);
                // echo $sent;
                return redirect()->back()->with('suc_message', $sent);
            }
        // }else{
        //     return redirect('qfd-approval')->with('err_message', 'Akses Ditolak!');
        // }
    }
    
}
