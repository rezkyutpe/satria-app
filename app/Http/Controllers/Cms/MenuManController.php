<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\MstApps;
use App\Models\MstMenu;
use App\Models\View\VwPermissionAppsMenu;
class MenuManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('menu-management') == 0){
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function MenuMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('menu-management')->r==1){
                $paginate = 1500;
                if (isset($request->query()['search'])){
                    $search = $request->query()['search'];
                    $appsmenu = MstMenu::where('menu', 'like', "%" . $search. "%")->orderBy('id', 'asc')->get();
                    $appsmenu->appends(['search' => $search]);
                } else {
                    $appsmenu = MstMenu::orderBy('id', 'asc')->get();
                }
                
                $apps = MstApps::where('status', 1)->get();
                $no = 1;
                foreach($appsmenu as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'appsmenu' => $appsmenu,
                    'apps' => $apps,
                    'actionmenu' => $this->PermissionActionMenu('menu-management'),
                );
                return view('menu-management/index')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function MenuMgmtInsert(Request $request)
    {      
        try{
            if($this->PermissionActionMenu('menu-management')->c==1){  
                $appsmenu = MstMenu::where('link', $request->link)->first();
                if(empty($appsmenu)){
                $create = MstMenu::create([
                    'app'=>$request->app,
                    'topmain'=>$request->topmain,
                    'main'=>$request->main,
                    'menu'=>$request->menu,
                    'link'=>$request->link,
                    'icon'=>$request->icon,
                    'flag' => 1,
                ]);
                    if($create){
                        return redirect('menu-management')->with('suc_message', 'Data berhasil ditambahkan!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{
                return redirect()->back()->with('err_message', 'Menu link Already Exist!');
                }
            }else{
                return redirect('menu-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function MenuMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('menu-management')->u==1){
            $appsmenu = MstMenu::where('id', $request->id)->first();
                if(!empty($appsmenu)){
                    $update = MstMenu::where('id', $request->id)
                    ->update([
                        'topmain'=>$request->topmain,
                        'main'=>$request->main,
                        'menu'=>$request->menu,
                        'link'=>$request->link,
                        'icon'=>$request->icon,
                    ]);
                    if($update){
                        return redirect('menu-management')->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('menu-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
   
    public function MenuMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('menu-management')->d==1){
                $appsmenu = MstMenu::where('id', $request->id)->first();
                if(!empty($appsmenu)){
                    MstMenu::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('menu-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function getMenu($app){
        try{
            $empData['data'] = MstMenu::where('app',$app)->get();
    
            return response()->json($empData);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
     
    }
}
