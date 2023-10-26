<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\UserRoleGroup;
use App\Models\MstRoleGroup;
use App\Models\MstApps;
use App\Models\MstMenu;
use App\Models\MstGroupMenu;
use App\Models\View\VwPermissionGroupMenu;
use App\Models\View\VwPermissionAppsMenu;
use Exception;
class RoleGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('rolegroup-management') == 0){
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function RoleGroupMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->r==1){
                if($this->PermissionActionMenu('user-management')->c==1){
                    if (isset($request->query()['search'])){
                        $search = $request->query()['search'];
                        $rolegroup = MstRoleGroup::select('role_group.*','apps.app_name')->join('apps', 'apps.id', '=', 'role_group.apps')->where('role_group.name', 'like', "%" . $search. "%")->orderBy('role_group.name', 'asc')->get();
                    } else {
                        $rolegroup = MstRoleGroup::select('role_group.*','apps.app_name')->join('apps', 'apps.id', '=', 'role_group.apps')->orderBy('role_group.name', 'asc')->get();
                    }
                    $apps = MstApps::where('status', 1)->get();
                }else{
                    if (isset($request->query()['search'])){
                        $search = $request->query()['search'];
                        $rolegroup = MstRoleGroup::select('role_group.*','apps.app_name')->join('apps', 'apps.id', '=', 'role_group.apps')->where('role_group.name', 'like', "%" . $search. "%")->whereNotIn('role_group.id',[1,52])->orderBy('role_group.name', 'asc')->get();
                    } else {
                        $rolegroup = MstRoleGroup::select('role_group.*','apps.app_name')->join('apps', 'apps.id', '=', 'role_group.apps')->orderBy('role_group.name', 'asc')->whereNotIn('role_group.id',[1,52])->get();
                    }
                    $apps = MstApps::where('status', 1)->whereNotIn('id',[1,52])->get();
                }
                $user = User::get();

                $no = 1;
                foreach($rolegroup as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'rolegroup' => $rolegroup,
                    'user' => $user,
                    'apps' => $apps,
                    'actionmenu' => $this->PermissionActionMenu('rolegroup-management'),
                );
                return view('rolegroup-management/index')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }    
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }           
    }
    
    public function RoleGroupMgmtView($id)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->v==1){
                if($id == 1 || $id == 52){
                    if($this->PermissionActionMenu('user-management')->c==1){
                        $apps = MstApps::where('status', 1)->get();
                        $userrole = UserRoleGroup::select('user_role_group.*','users.name','users.dept','users.department')->join('users', 'users.id', '=', 'user_role_group.user')->where('group', $id)->get();
                        $rolegroup = MstRoleGroup::select('role_group.*','apps.app_name')->join('apps', 'apps.id', '=', 'role_group.apps')->where('role_group.id',$id)->first();
                        $user = User::get();
                        $menu = MstMenu::where('flag', 1)->get();
                        $appsmenu = VwPermissionGroupMenu::where('group', $id)->get();
                        $no = 1;
                        foreach($appsmenu as $data){
                            $data->no = $no;
                            $no++;
                        }
                        $data = array(
                        'user' => $user,
                        'userrole' => $userrole,
                        'rolegroup' => $rolegroup,
                        'apps' => $apps,
                        'menu' => $menu,
                        'appsmenu' => $appsmenu,
                        );
                        return view('rolegroup-management/view')->with('data', $data);
                    }else{
                        return redirect('rolegroup-management')->with('err_message', 'Akses Ditolakss!');
                    }
                }else{
                    $apps = MstApps::where('status', 1)->where('id','!=',1)->get();
                    $userrole = UserRoleGroup::select('user_role_group.*','users.name','users.dept','users.department')->join('users', 'users.id', '=', 'user_role_group.user')->where('group', $id)->get();
                    $rolegroup = MstRoleGroup::select('role_group.*','apps.app_name')->join('apps', 'apps.id', '=', 'role_group.apps')->where('role_group.id',$id)->first();
                    $user = User::get();
                    $menu = MstMenu::where('flag', 1)->get();
                    $appsmenu = VwPermissionGroupMenu::where('group', $id)->get();
                    $no = 1;
                    foreach($appsmenu as $data){
                        $data->no = $no;
                        $no++;
                    }
                    $data = array(
                    'user' => $user,
                    'userrole' => $userrole,
                    'rolegroup' => $rolegroup,
                    'apps' => $apps,
                    'menu' => $menu,
                    'appsmenu' => $appsmenu,
                    );
                    return view('rolegroup-management/view')->with('data', $data);
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    
    public function GetUserRoleGroupView($id)
    {
        $userrole = UserRoleGroup::select('user_role_group.*','users.name','users.email','users.dept','users.department')->join('users', 'users.id', '=', 'user_role_group.user')->where('group', $id)->orderBy('created_at','DESC')->get();
        $userId = UserRoleGroup::select('user_role_group.user')->join('users', 'users.id', '=', 'user_role_group.user')->where('group', $id)->get('id');
        $user = User::whereNotIn('id',$userId)->get();
        $data = array(
            'userrole' => $userrole,
            'user' => $user,
        );
        return response()->json($data);
    }
    public function RoleGroupMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->c==1){
                $rolegroup = MstRoleGroup::where('name', $request->name)->where('apps', $request->apps)->first();
                if(empty($rolegroup)){
                $create = MstRoleGroup::create([
                    'name'=>$request->name,
                    'apps'=>$request->apps,
                    
                    'flag' => 1,
                ]);
                    if($create){
                        return redirect('rolegroup-management')->with('suc_message', 'Data berhasil ditambahkan!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{
                return redirect()->back()->with('err_message', 'Role Group Name Already Exist!');
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function RoleGroupMgmtAddUser(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->c==1){
                $rolegroup = UserRoleGroup::where('user', $request->user)->where('group', $request->group)->first();
                if(empty($rolegroup)){
                $create = UserRoleGroup::create([
                    'user'=>$request->user,
                    'group'=>$request->group,
                ]);
                    if($create){
                        return response()->json(['message'=>'success']);
                    }else{
                        return response()->json(['message'=>'gagal']);
                    }
                }else{
                    return response()->json(['message'=>'useradded']);
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function  RoleGroupMgmtPermissionAdd(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->c==1){
                
                MstGroupMenu::create([
                        'group' => $request->group,
                        'app' => $request->app,
                        'menu' =>  $request->menu,
                        'access' => 1,
                        'c' =>  $request->c,
                        'r' =>  $request->r,
                        'u' =>  $request->u,
                        'd' =>  $request->d,
                        'v' =>  $request->v,
                    ]);
                    
                    return redirect()->back()->with('suc_message', 'Data baru berhasil ditambahkan!');
            
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function  RoleGroupMgmtPermissionUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->c==1){
                $group = MstGroupMenu::where('id', $request->id)->first();
            
                if(!empty($group)){
                    MstGroupMenu::where('id', $request->id)
                    ->update([
                        'app' => $request->app,
                        'menu' =>  $request->menu,
                        'access' => 1,
                        'c' =>  $request->c,
                        'r' =>  $request->r,
                        'u' =>  $request->u,
                        'd' =>  $request->d,
                        'v' =>  $request->v,
                    ]);
                    
                    return redirect()->back()->with('suc_message', 'Data baru berhasil ditambahkan!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function RoleGroupMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->u==1){
                $rolegroup = MstRoleGroup::where('id', $request->id)->first();
                if(!empty($rolegroup)){
                    $update = MstRoleGroup::where('id', $request->id)
                    ->update([
                        'name'=>$request->name,
                        'apps'=>$request->apps,
                        
                        'flag' => 1,
                    ]);
                    if($update){
                        return redirect('rolegroup-management')->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function RoleGroupMgmtDeleteUser($id)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->d==1){
                $rolegroup = UserRoleGroup::where('id', $id)->first();
                if(!empty($rolegroup)){
                    UserRoleGroup::where('id', $id)->delete();
                    return response()->json(['message'=>'success']);
                } else {
                    return response()->json(['message'=>'gagal']);
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function RoleGroupMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->d==1){
                $rolegroup = MstRoleGroup::where('id', $request->id)->first();
                if(!empty($rolegroup)){
                    MstRoleGroup::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    
    public function RoleGroupMgmtPermissionDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('rolegroup-management')->d==1){
                $rolegroup = MstGroupMenu::where('id', $request->id)->first();
                if(!empty($rolegroup)){
                    MstGroupMenu::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('rolegroup-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
}
