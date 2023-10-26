<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\MstSla;
use App\Models\View\VwPermissionAppsMenu;
class SlaManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            
            if ($this->PermissionMenu('sla-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function SlaMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('sla-management')->r==1){
        // $checkuser = MstSla::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $sla = MstSla::where('dept',Auth::user()->dept)->orderBy('name', 'asc')->get();
            $no = 1;
            foreach($sla as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'sla' => $sla,
                'actionmenu' => $this->PermissionActionMenu('sla-management'),
            );
            return view('elsa/sla-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function SlaMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('sla-management')->c==1){
            $sla = MstSla::where('name', $request->name)->where('dept', $request->dept)->first();
            if(empty($sla)){
            $create = MstSla::create([
                'name'=>$request->name,
                'dept'=>Auth::user()->dept,
                'resolution_time'=>$request->resolution_time,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('sla-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Nama Aplikasi Already Exist!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
       
    }
    public function SlaMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('sla-management')->u==1){
            $sla = MstSla::where('id', $request->id)->first();
            if(!empty($sla)){
                $update = MstSla::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'resolution_time'=>$request->resolution_time,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('sla-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function SlaMgmtDelete(Request $request)
    {
       
        if($this->PermissionActionMenu('sla-management')->d==1){
            $sla = MstSla::where('id', $request->id)->first();
            if(!empty($sla)){
                MstSla::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
}
