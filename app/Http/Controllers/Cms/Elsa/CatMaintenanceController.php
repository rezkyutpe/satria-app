<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\CatMaintenance;
use App\Models\View\VwPermissionAppsMenu;
class CatMaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('cat-maintenance') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function CatMainMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('cat-maintenance')->r==1){
        // $checkuser = CatMaintenance::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $cat = CatMaintenance::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('durasi', 'asc')->get();
            $no = 1;
            foreach($cat as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'cat' => $cat,
                'actionmenu' => $this->PermissionActionMenu('cat-maintenance'),
            );
            return view('elsa/cat-maintenance/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function CatMainMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('cat-maintenance')->c==1){
           
            $create = CatMaintenance::create([
                'note'=>$request->note,
                'durasi'=>$request->durasi,
                'start_alert'=>$request->start_alert,
                'dept'=>Auth::user()->dept,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('cat-maintenance')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
       
    }
    public function CatMainMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('cat-maintenance')->u==1){
            $cat = CatMaintenance::where('id', $request->id)->first();
            if(!empty($cat)){
                $update = CatMaintenance::where('id', $request->id)
                ->update([
                    'note'=>$request->note,
                    'durasi'=>$request->durasi,
                    'start_alert'=>$request->start_alert,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('cat-maintenance')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
        
    }
   
    public function CatMainMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('cat-maintenance')->d==1){
            $cat = CatMaintenance::where('id', $request->id)->first();
            if(!empty($cat)){
                CatMaintenance::where('id', $request->id)->update([
                    'flag'=>0,
                    'updated_by' => Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
}
