<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\View\Elsa\VwAssist;
use App\Models\Table\Elsa\Assist;
use App\Models\Table\Elsa\AssistGroup;
use App\Models\View\VwPermissionAppsMenu;
class AssistManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if(empty($this->cekElsaAssist(Auth::user()->id))){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
                return $next($request);
            });
    }

    public function AssistMgmtInit(Request $request)
    {
        // $checkuser = Assist::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $assist = VwAssist::where('dept',$this->cekElsaAssist(Auth::user()->id)->dept)->where('flag','!=',0)->orderBy('name', 'asc')->get();
            $group = AssistGroup::where('dept',$this->cekElsaAssist(Auth::user()->id)->dept)->get();
            $user = User::get();
            $no = 1;
            foreach($assist as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'assist' => $assist,
                'group' => $group,
                'user' => $user,
                'actionmenu' => $this->PermissionActionMenu('assist-management'),
            );
            return view('elsa/assist-management/index')->with('data', $data);
           
    }
    public function AssistMgmtInsert(Request $request)
    {
            $assist = Assist::where('satria_id', $request->user)->first();
            if(empty($assist)){
            $create = Assist::create([
                'satria_id'=>$request->user,
                'id_group'=>$request->id_group,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('assist-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'User Already Exist!');
            }
       
    }
    public function AssistMgmtUpdate(Request $request)
    {
            $assist = Assist::where('id', $request->id)->first();
            if(!empty($assist)){
                $update = Assist::where('id', $request->id)
                ->update([
                    'id_group'=>$request->id_group,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('assist-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        
    }
   
    public function AssistMgmtDelete(Request $request)
    {
       
            $assist = Assist::where('id', $request->id)->first();
            if(!empty($assist)){
                Assist::where('id', $request->id)->update([
                    'flag'=>0,
                    'updated_by' => Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
    }
}
