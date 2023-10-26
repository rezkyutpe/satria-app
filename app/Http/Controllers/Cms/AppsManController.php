<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\MstApps;
use App\Models\View\VwPermissionAppsMenu;
use Exception;
class AppsManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('apps-management') == 0){
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function AppsMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('apps-management')->r==1){
                $paginate = 1500;
                if (isset($request->query()['search'])){
                    $search = $request->query()['search'];
                    $apps = MstApps::where('app_name', 'like', "%" . $search. "%")->orderBy('app_name', 'asc')->simplePaginate($paginate);
                    $apps->appends(['search' => $search]);
                } else {
                    $apps = MstApps::orderBy('app_name', 'asc')->simplePaginate($paginate);
                }
                // $apps = MstApps::with(['countrys'])->where('role_id', 1)->get();
                $no = 1;
                foreach($apps as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'apps' => $apps,
                    'actionmenu' => $this->PermissionActionMenu('apps-management'),
                );
                return view('apps-management/index')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }
    public function AppsMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('apps-management')->c==1){
                $apps = MstApps::where('app_name', $request->app_name)->first();
                if(empty($apps)){
                $create = MstApps::create([
                    'app_name'=>$request->app_name,
                    'link'=>$request->link,
                    'logo'=>$request->logo,
                    'status' => 1,
                ]);
                    if($create){
                        return redirect()->back()->with('suc_message', 'Data berhasil ditambahkan!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{
                return redirect()->back()->with('err_message', 'Apps Name Already Exist!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function AppsMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('apps-management')->u==1){
                $apps = MstApps::where('id', $request->id)->first();
                if(!empty($apps)){
                    $update = MstApps::where('id', $request->id)
                    ->update([
                        'app_name'=>$request->app_name,
                        'link'=>$request->link,
                        'logo'=>$request->logo,
                        'status' => 1,
                    ]);
                    if($update){
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
   
    public function AppsMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('apps-management')->d==1){
                $apps = MstApps::where('id', $request->id)->first();
                if(!empty($apps)){
                    MstApps::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
}
