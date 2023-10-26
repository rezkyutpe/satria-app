<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\Aplikasi;
use App\Models\View\VwPermissionAppsMenu;
class AplikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('aplikasi-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function AplikasiMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('aplikasi-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $app = Aplikasi::where('nama_app', 'like', "%" . $search. "%")->orderBy('id_app', 'asc')->simplePaginate($paginate);
                $app->appends(['search' => $search]);
            } else {
                $app = Aplikasi::orderBy('id_app', 'asc')->simplePaginate($paginate);
            }
            // $app = Aplikasi::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($app as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'app' => $app,
                'actionmenu' => $this->PermissionActionMenu('aplikasi-management'),
            );
            return view('parts-tracking/aplikasi-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function AplikasiMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('aplikasi-management')->c==1){
            $app = Aplikasi::where('nama_app', $request->nama_app)->first();
            if(empty($app)){
            $create = Aplikasi::create([
                'nama_app'=>$request->nama_app,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('aplikasi-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Nama Aplikasi Already Exist!');
            }
        }else{
            return redirect('aplikasi-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function AplikasiMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('aplikasi-management')->u==1){
            $app = Aplikasi::where('id_app', $request->id_app)->first();
            if(!empty($app)){
                $update = Aplikasi::where('id_app', $request->id_app)
                ->update([
                    'nama_app'=>$request->nama_app,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('aplikasi-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('aplikasi-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function AplikasiMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('aplikasi-management')->d==1){
            $app = Aplikasi::where('id_app', $request->id_app)->first();
            if(!empty($app)){
                Aplikasi::where('id_app', $request->id_app)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('aplikasi-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
