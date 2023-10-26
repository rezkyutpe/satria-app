<?php
   
namespace App\Http\Controllers\api\approval;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\View\Approval\VwApprovalApps;
use App\Models\Table\Approval\ApprovalApps;
use App\Models\Table\Approval\ApprovalDetail;
use Validator;
   
class ApprovalController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
            $apps =  VwApprovalApps::select('id','name','approval_to','approval_level','approval_name','get_link_apps','get_link','post_link')->where('approval_to', $user->id)->groupBy('id')->get();
            return $this->sendResponse($apps, 'Berhasil Menampilkan Data.');
        }
    }
    // public function post(Request $request)
    // {
    //     $user = User::where('token',$request->uuid)->first();
    //     if (empty($user)) {
    //         return $this->sendError('User Token Not Found',$user);
    //     }else{
    //         $update = ApprovalDetail::where('id', $request->id)
    //             ->update([
    //                 'status'=>$request->status,
    //                 'reason'=>$request->reason,
    //                 'updated_by' => Auth::user()->id,
    //             ]);
    //         return $this->sendResponse($apps, 'Berhasil Mengupdate Data.');
    //     }
    // }
    public function detail(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
            if($request->id){
                $info= ApprovalDetail::select('approval_detail.*','approval_apps.approval_name')->join('approval_apps', 'approval_detail.approval', '=', 'approval_apps.id')->where('approval_to',$user->id)->where('approval',$request->id)->get();
                return $this->sendResponse($info, 'Berhasil Menampilkan Data.');
            }else{
                $info= ApprovalDetail::join('approval_apps', 'approval_detail.approval', '=', 'approval_apps.id')->where('approval_to',$user->id)->get();
                return $this->sendResponse($info, 'Berhasil Menampilkan Data.');
            }
        }
    }
    
}