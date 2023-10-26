<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\Fitting;
use App\Models\View\VwPermissionAppsMenu;
class JenisFittingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('jenisfitting-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function JenisFittingMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('jenisfitting-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $fitting = Fitting::where('nama_fitting', 'like', "%" . $search. "%")->orderBy('id_fitting', 'asc')->simplePaginate($paginate);
                $fitting->appends(['search' => $search]);
            } else {
                $fitting = Fitting::orderBy('id_fitting', 'asc')->simplePaginate($paginate);
            }
            // $fitting = Fitting::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($fitting as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'fitting' => $fitting,
                'actionmenu' => $this->PermissionActionMenu('jenisfitting-management'),
            );
            return view('parts-tracking/jenisfitting-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function JenisFittingMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('jenisfitting-management')->c==1){
            $fitting = Fitting::where('id_fitting', $request->id_fitting)->first();
            $nourut = Fitting::max('no_urut');

            if(empty($fitting)){
            $create = Fitting::create([
                'id_fitting'=>$request->id_fitting,
                'nama_fitting'=>$request->nama_fitting,
                'size'=>$request->size,
                'no_urut'=>$nourut+1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('jenisfitting-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Id Fitting Already Exist!');
            }
        }else{
            return redirect('jenisfitting-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function JenisFittingMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('jenisfitting-management')->u==1){
            $fitting = Fitting::where('id_fitting', $request->id_fitting)->first();
            if(!empty($fitting)){
                $update = Fitting::where('id_fitting', $request->id_fitting)
                ->update([
                    'nama_fitting'=>$request->nama_fitting,
                    'size'=>$request->size,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('jenisfitting-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('jenisfitting-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function JenisFittingMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('jenisfitting-management')->d==1){
            $fitting = Fitting::where('id_fitting', $request->id_fitting)->first();
            if(!empty($fitting)){
                Fitting::where('id_fitting', $request->id_fitting)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('jenisfitting-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
