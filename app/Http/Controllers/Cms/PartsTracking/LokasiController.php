<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\Lokasi;
use App\Models\View\VwPermissionAppsMenu;
class LokasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('lokasi-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function LokasiMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('lokasi-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $lokasi = Lokasi::where('nama_lokasi', 'like', "%" . $search. "%")->orderBy('id_lokasi', 'asc')->simplePaginate($paginate);
                $lokasi->appends(['search' => $search]);
            } else {
                $lokasi = Lokasi::orderBy('id_lokasi', 'asc')->simplePaginate($paginate);
            }
            // $lokasi = Lokasi::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($lokasi as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'lokasi' => $lokasi,
                'actionmenu' => $this->PermissionActionMenu('lokasi-management'),
            );
            return view('parts-tracking/lokasi-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function LokasiMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('lokasi-management')->c==1){
            $lokasi = Lokasi::where('id_lokasi', $request->id_lokasi)->first();
            if(empty($lokasi)){
            $create = Lokasi::create([
                'id_lokasi'=>$request->id_lokasi,
                'nama_lokasi'=>$request->nama_lokasi,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('lokasi-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Id Lokasi Already Exist!');
            }
        }else{
            return redirect('lokasi-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function LokasiMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('lokasi-management')->u==1){
            $lokasi = Lokasi::where('id_lokasi', $request->id_lokasi)->first();
            if(!empty($lokasi)){
                $update = Lokasi::where('id_lokasi', $request->id_lokasi)
                ->update([
                    'nama_lokasi'=>$request->nama_lokasi,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('lokasi-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('lokasi-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function LokasiMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('lokasi-management')->d==1){
            $lokasi = Lokasi::where('id_lokasi', $request->id_lokasi)->first();
            if(!empty($lokasi)){
                Lokasi::where('id_lokasi', $request->id_lokasi)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('lokasi-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
