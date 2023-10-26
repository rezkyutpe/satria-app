<?php
   
namespace App\Http\Controllers\api\incentive;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Table\Incentive\Incentive;
use App\Models\Table\Incentive\RequestInc;
use App\Models\View\Incentive\VwIncGroup;
use App\Models\View\Incentive\VwIncReport;
class IncController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $user =  User::where('token',$request->id)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }
        $inc = VwIncReport::where('sales', $user->email)->get();
  
        return $this->sendResponse($inc, 'Berhasil Menampilkan Data.');
    }
    public function show(Request $request)
    {
        $user =  User::where('token',$request->id)->first();  
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }
        $reqinc =  RequestInc::where('req_month','like', "%" . $request->date. "%")->where('sales',$user->email)->first();
      
        
        if (!empty($reqinc)) {
            return $this->sendError('Incentive '.$request->date.' already submit');
        }
        $inc = VwIncReport::where('sales', $user->email)->where('cash_date','like', "%" . $request->date. "%")->get();
           
      
        return $this->sendResponse($inc, 'Berhasil Menampilkan Data.');
    }
    public function update(Request $request)
    {
        $user =  User::where('token',$request->id)->first();
        $reqinc =  RequestInc::where('req_month','like', "%" . $request->date. "%")->where('sales',$user->email)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }else{
            
            if (empty($reqinc)) {
                Incentive::where('sales', $user->email)->where('cash_date','like', "%" . $request->date. "%")
                    ->update([
                    'request'=>'ICT'.$user->email.date('Ymd',strtotime($request->date.'-01')),
                    'status' => 1,
                ]);
                RequestInc::insert([
                    'id_req'=>'ICT'.$user->email.date('Ymd',strtotime($request->date.'-01')),
                    'sales'=>$user->email,
                    'req_month'=>$request->date.'-01',
                    'created_by'=> $user->id,
                ]);
                
                return $this->sendResponse([], 'Berhasil Mengupdate Data.');
            }else{
                return $this->sendError('Incentive '.$request->date.' already submit');
            }
        }
    }
}