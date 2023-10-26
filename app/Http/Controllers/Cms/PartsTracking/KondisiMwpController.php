<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\KondisiMwp;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\PartsTracking\JenisHose;
use App\Models\Table\PartsTracking\Diameter;
class KondisiMwpController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('mwp-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function MwpMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('mwp-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $mwp = KondisiMwp::join('t_jenishose', 't_kondisi_mwp.jhose', '=', 't_jenishose.id_jhose')->where('jhose', 'like', "%" . $search. "%")->orderBy('id_mwp', 'asc')->simplePaginate($paginate);
                $mwp->appends(['search' => $search]);
            } else {
                $mwp = KondisiMwp::join('t_jenishose', 't_kondisi_mwp.jhose', '=', 't_jenishose.id_jhose')->orderBy('id_mwp', 'asc')->simplePaginate($paginate);
            }
            
            $jhose =  JenisHose::get();
            $diameter =  Diameter::where('ukuran_diameter', 'like', "-%")->get();
            $no = 1;
            foreach($mwp as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'mwp' => $mwp,
                'jhose' => $jhose,
                'diameter' => $diameter,
                'actionmenu' => $this->PermissionActionMenu('mwp-management'),
            );
            return view('parts-tracking/mwp-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function MwpMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('mwp-management')->c==1){
            // $mwp = KondisiMwp::where('jml', $request->jml)->first();
            // if(empty($mwp)){
            $create = KondisiMwp::create([
                'jhose'=>$request->jhose,
                'diameter'=>$request->diameter,
                'mwp'=>$request->mwp,
                'mbp'=>$request->mbp,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('mwp-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            // }else{
            // return redirect()->back()->with('err_message', 'SB Unit Already Exist!');
            // }
        }else{
            return redirect('mwp-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function MwpMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('mwp-management')->u==1){
            $mwp = KondisiMwp::where('id_mwp', $request->id_mwp)->first();
            if(!empty($mwp)){
                $update = KondisiMwp::where('id_mwp', $request->id_mwp)
                ->update([
                    'jhose'=>$request->jhose,
                    'diameter'=>$request->diameter,
                    'mwp'=>$request->mwp,
                    'mbp'=>$request->mbp,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('mwp-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('mwp-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function MwpMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('mwp-management')->d==1){
            $mwp = KondisiMwp::where('id_mwp', $request->id_mwp)->first();
            if(!empty($mwp)){
                KondisiMwp::where('id_mwp', $request->id_mwp)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('mwp-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
