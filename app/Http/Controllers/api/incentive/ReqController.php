<?php
   
namespace App\Http\Controllers\api\incentive;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use PDF;
use App\Models\Table\Incentive\Incentive;
use App\Models\View\Incentive\VwReqReport;
use App\Models\Table\Incentive\RequestInc;
use App\Models\View\Incentive\VwIncGroup;
use App\Models\View\Incentive\VwIncReport;
class ReqController extends BaseController
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
            $req = RequestInc::where('status', $request->status)->get();
        }else{
            $req = RequestInc::get();
        }
        return $this->sendResponse($req, 'Berhasil Menampilkan Data.');
    }
    public function show(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }
        $req = RequestInc::where('id_req', $request->req)->get();
        $inc = VwIncReport::where('request', $request->req)->get();
        $total = 0;
        foreach($inc as $value){
            $total = $total+$value->inc;
        }
        $data = array(
                'total' => 'Rp '.number_format(round($total,0),0,',','.'),
                'req' => $req,
                'inc' => $inc
                );   
        return $this->sendResponse($data, 'Berhasil Menampilkan Data.');
    }

    public function view($id,$token)
    {
        $user =  User::where('token',$token)->first();
        if (empty($user)) {
            return PDF::loadHtml('<h2>Token Unauthorised</h2>')->stream();
        } else{
           $incentive = Incentive::where('request',$id)->get();
            $requestinc = VwReqReport::where('id_req',$id)->first();
            
            $no = 1;
            foreach($incentive as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'request' => $requestinc,
                'incentive' => $incentive,
            );
            // return view('incentive/requests-management/web-view-inc')->with('data', $data);
            $pdf = PDF::loadView('incentive/requests-management/web-view-inc', $data);
            return $pdf->stream();
       }
     }
    public function appget(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }

        if (isset($request->status)) {
            $req = RequestInc::select('id_req','sales as fk_id','req_month as fk_desc','ket','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark','status','created_at','created_by')->where('status', $request->status)->get();
        }else{
            $req = RequestInc::select('id_req','sales as fk_id','req_month as fk_desc','ket','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark','status','created_at','created_by')->get();
        }
        return $this->sendResponse($req, 'Berhasil Menampilkan Data.');
    }
    
    public function accept(Request $request)
    {
        $user =  User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }else{
            RequestInc::where('id_req', $request->id)
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
            RequestInc::where('id_req', $request->id)
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