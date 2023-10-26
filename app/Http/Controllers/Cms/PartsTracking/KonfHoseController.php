<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\KonfHose;
use App\Models\View\VwPermissionAppsMenu;
class KonfHoseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('khose-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function KhoseMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('khose-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $khose = KonfHose::where('nama_khose', 'like', "%" . $search. "%")->orderBy('id_khose', 'asc')->simplePaginate($paginate);
                $khose->appends(['search' => $search]);
            } else {
                $khose = KonfHose::orderBy('id_khose', 'asc')->simplePaginate($paginate);
            }
            // $khose = KonfHose::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($khose as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'khose' => $khose,
                'actionmenu' => $this->PermissionActionMenu('khose-management'),
            );
            return view('parts-tracking/khose-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function KhoseMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('khose-management')->c==1){
            $khose = KonfHose::where('id_khose', $request->id_khose)->first();
            if(empty($khose)){
            $create = KonfHose::create([
                'id_khose'=>$request->id_khose,
                'nama_khose'=>$request->nama_khose,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('khose-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Id Khose Already Exist!');
            }
        }else{
            return redirect('khose-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function KhoseMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('khose-management')->u==1){
            $khose = KonfHose::where('id_khose', $request->id_khose)->first();
            if(!empty($khose)){
                $update = KonfHose::where('id_khose', $request->id_khose)
                ->update([
                    'nama_khose'=>$request->nama_khose,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('khose-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('khose-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function KhoseMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('khose-management')->d==1){
            $khose = KonfHose::where('id_khose', $request->id_khose)->first();
            if(!empty($khose)){
                KonfHose::where('id_khose', $request->id_khose)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('khose-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
