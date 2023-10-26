<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\AssistGroup;
use App\Models\View\VwPermissionAppsMenu;
class AssistGroupManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('assist-group') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function AssistGroupMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('assist-group')->r==1){
            
            $assistgroup = AssistGroup::orderBy('name', 'asc')->get();
            // $assistgroup = AssistGroup::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($assistgroup as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'assistgroup' => $assistgroup,
                'actionmenu' => $this->PermissionActionMenu('assist-group'),
            );
            return view('elsa/assist-group/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function AssistGroupMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('assist-group')->c==1){
            $assistgroup = AssistGroup::where('name', $request->name)->where('dept', $request->dept)->first();
            if(empty($assistgroup)){
            $create = AssistGroup::create([
                'name'=>$request->name,
                'dept'=>$request->dept,
                'status'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('assist-group')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Nama Aplikasi Already Exist!');
            }
        }else{
            return redirect('assist-group')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function AssistGroupMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('assist-group')->u==1){
            $assistgroup = AssistGroup::where('id', $request->id)->first();
            if(!empty($assistgroup)){
                $update = AssistGroup::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'dept'=>$request->dept,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('assist-group')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('assist-group')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function AssistGroupMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('assist-group')->d==1){
            $assistgroup = AssistGroup::where('id', $request->id)->first();
            if(!empty($assistgroup)){
                AssistGroup::where('id', $request->id)->update([
                    'status'=>0,
                    'updated_by' => Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('assist-group')->with('err_message', 'Akses Ditolak!');
        }
    }
}
