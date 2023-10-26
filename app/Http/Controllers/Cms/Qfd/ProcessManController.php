<?php

namespace App\Http\Controllers\Cms\Qfd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\MstApps;
use App\Models\Table\Qfd\MstProcess;
use App\Models\View\VwPermissionAppsMenu;
class ProcessManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('process-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function ProcessMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('process-management')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $mstprocess = MstProcess::where('nama', 'like', "%" . $search. "%")->orderBy('nama', 'asc')->simplePaginate($paginate);
                $mstprocess->appends(['search' => $search]);
            } else {
                $mstprocess = MstProcess::orderBy('nama', 'asc')->simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($mstprocess as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'mstprocess' => $mstprocess,
                'actionmenu' => $this->PermissionActionMenu('process-management'),
            );
            return view('qfd/process-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ProcessMgmtInsert(Request $request)
    {      
        if($this->PermissionActionMenu('process-management')->c==1){  
            $mstprocess = MstProcess::where('nama', $request->nama)->first();
            if(empty($mstprocess)){
            $create = MstProcess::create([
                'id'=>$request->id,
                'nama'=>$request->nama,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('process-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Process Name Already Exist!');
            }
        }else{
            return redirect('process-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ProcessMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('process-management')->u==1){
            $mstprocess = MstProcess::where('id', $request->id)->first();
            if(!empty($mstprocess)){
                $update = MstProcess::where('id', $request->id)
                ->update([
                    'nama'=>$request->nama,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('process-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('process-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function ProcessMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('process-management')->d==1){
            $mstprocess = MstProcess::where('id', $request->id)->first();
            if(!empty($mstprocess)){
                MstProcess::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('process-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function getProcess($app){

        $empData['data'] = MstProcess::orderBy('nama', 'asc')->get();
  
        return response()->json($empData);
     
    }
}
