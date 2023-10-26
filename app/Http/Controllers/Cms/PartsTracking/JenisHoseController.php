<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\JenisHose;
use App\Models\View\VwPermissionAppsMenu;
class JenisHoseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('jhose-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function JhoseMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('jhose-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $jhose = JenisHose::where('nama_hose', 'like', "%" . $search. "%")->orderBy('id_jhose', 'asc')->simplePaginate($paginate);
                $jhose->appends(['search' => $search]);
            } else {
                $jhose = JenisHose::orderBy('id_jhose', 'asc')->simplePaginate($paginate);
            }
            // $jhose = JenisHose::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($jhose as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'jhose' => $jhose,
                'actionmenu' => $this->PermissionActionMenu('jhose-management'),
            );
            return view('parts-tracking/jhose-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function JhoseMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('jhose-management')->c==1){
            $jhose = JenisHose::where('id_jhose', $request->id_jhose)->first();
            if(empty($jhose)){
            $create = JenisHose::create([
                'id_jhose'=>$request->id_jhose,
                'nama_hose'=>$request->nama_hose,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('jhose-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Id Jhose Already Exist!');
            }
        }else{
            return redirect('jhose-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function JhoseMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('jhose-management')->u==1){
            $jhose = JenisHose::where('id_jhose', $request->id_jhose)->first();
            if(!empty($jhose)){
                $update = JenisHose::where('id_jhose', $request->id_jhose)
                ->update([
                    'nama_hose'=>$request->nama_hose,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('jhose-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('jhose-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function JhoseMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('jhose-management')->d==1){
            $jhose = JenisHose::where('id_jhose', $request->id_jhose)->first();
            if(!empty($jhose)){
                JenisHose::where('id_jhose', $request->id_jhose)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('jhose-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
