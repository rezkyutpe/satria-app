<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\Lifetime;
use App\Models\View\VwPermissionAppsMenu;
class LifetimeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('lifetime-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function LifetimeMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('lifetime-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $lifetime = Lifetime::where('jml', 'like', "%" . $search. "%")->orderBy('jml', 'asc')->simplePaginate($paginate);
                $lifetime->appends(['search' => $search]);
            } else {
                $lifetime = Lifetime::orderBy('jml', 'asc')->simplePaginate($paginate);
            }
            // $lifetime = Lifetime::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($lifetime as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'lifetime' => $lifetime,
                'actionmenu' => $this->PermissionActionMenu('lifetime-management'),
            );
            return view('parts-tracking/lifetime-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function LifetimeMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('lifetime-management')->c==1){
            $lifetime = Lifetime::where('jml', $request->jml)->first();
            if(empty($lifetime)){
            $create = Lifetime::create([
                'ket'=>$request->ket,
                'jml'=>$request->jml,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('lifetime-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'SB Unit Already Exist!');
            }
        }else{
            return redirect('lifetime-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function LifetimeMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('lifetime-management')->u==1){
            $lifetime = Lifetime::where('id_lifetime', $request->id_lifetime)->first();
            if(!empty($lifetime)){
                $update = Lifetime::where('id_lifetime', $request->id_lifetime)
                ->update([
                    'ket'=>$request->ket,
                    'jml'=>$request->jml,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('lifetime-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('lifetime-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function LifetimeMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('lifetime-management')->d==1){
            $lifetime = Lifetime::where('id_lifetime', $request->id_lifetime)->first();
            if(!empty($lifetime)){
                Lifetime::where('id_lifetime', $request->id_lifetime)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('lifetime-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
