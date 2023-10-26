<?php
   
namespace App\Http\Controllers\api\qfd;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use PDF;
use App\Models\Table\Qfd\Qfd;
use App\Models\Table\Qfd\TrxMat;
use App\Models\Table\Qfd\TrxMatDetail;
use App\Models\Table\Qfd\TrxBomDetail;
use App\Models\View\Qfd\VwIncGroup;
use App\Models\View\Qfd\VwIncReport;
class QfdApprovalController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }
        if (isset($request->status)) {
            $req = TrxMat::select('id as id_req','material_number as fk_id','material_description as fk_desc','file as ket','accept_to','approve_to','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark','status','created_at','created_by')->where('status', $request->status)->get();
        }else{
            $req = TrxMat::select('id as id_req','material_number as fk_id','material_description as fk_desc','file as ket','accept_to','approve_to','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark','status','created_at','created_by')->get();
        }
        return $this->sendResponse($req, 'Berhasil Menampilkan Data.');
    }
    public function show(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        } 
        redirect('qfd-web-view/'.$request->id);  
    }
    public function view($id,$token)
    {
        $user =  User::where('token',$token)->first();
        if (empty($user)) {
            $attendance = TrxMat::where('id', $id)->where('attendance', 'like', "%" . $token. "%")->first();
            if (empty($attendance)) {
                return PDF::loadHtml('<h2>Token Unauthorised</h2>')->stream();
            }else{ 
                $trxmat = TrxMat::where('id', $id)->first();
                $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
                $trxbomdetail = TrxBomDetail::where('trx_material', $id)->where('flag', 1)->get();
                
                $data = array(
                  'trxmat' => $trxmat,
                  'trxmatdetail' => $trxmatdetail,
                  'trxbomdetail' => $trxbomdetail,
                );
                // return view('qfd/qfd-management/web-view-qfd')->with('data', $data);
                $pdf = PDF::loadView('qfd/qfd-management/web-view-qfd', $data);
                return $pdf->stream();
            }
        } else{
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetail::where('trx_material', $id)->where('flag', 1)->get();
            
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
            );
            // return view('qfd/qfd-management/web-view-qfd')->with('data', $data);
        	$pdf = PDF::loadView('qfd/qfd-management/web-view-qfd', $data);
            return $pdf->stream();
       }
    }
    public function accept(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }else{
            TrxMat::where('id', $request->id)
                ->update([
                'accepted'=>$user->id,
                'accepted_date'=>date('Y-m-d H:i:s'),
                'status' => 1,
                'updated_by' =>$user->id,
            ]);
            return $this->sendResponse([], 'Berhasil Mengupdate Data.');
        }
    }
     public function approve(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }else{
            TrxMat::where('id', $request->id)
                ->update([
                'approved'=>$user->id,
                'approved_date'=>date('Y-m-d H:i:s'),
                'status' => 2,
                'updated_by' =>$user->id,
            ]);
            return $this->sendResponse([], 'Berhasil Mengupdate Data.');
        }
    }
}