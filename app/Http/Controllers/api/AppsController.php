<?php
   
namespace App\Http\Controllers\api;
   
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\MstInfo;
use Validator;
   
class AppsController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $user = User::where('token',$request->id)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
            $apps =  VwPermissionAppsMenu::select('app','app_name','logo')->where('user', $user->id)->where('logo','!=','satria.svg')->groupBy('app_name')->get();
            return $this->sendResponse($apps, 'Berhasil Menampilkan Data.');
        }
    }
    public function getInfo(Request $request)
    {
        $user = User::where('token',$request->id)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
            $info= MstInfo::get();
            return $this->sendResponse($info, 'Berhasil Menampilkan Data.');
        }
    }
    
}