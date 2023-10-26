<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\SnUnit;
use App\Models\View\VwPermissionAppsMenu;
class SnUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('snunit-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function SnUnitMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('snunit-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $snunit = SnUnit::where('sn_unit', 'like', "%" . $search. "%")->orderBy('id_unit', 'asc')->simplePaginate($paginate);
                $snunit->appends(['search' => $search]);
            } else {
                $snunit = SnUnit::orderBy('id_unit', 'asc')->simplePaginate($paginate);
            }
            // $snunit = SnUnit::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($snunit as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'snunit' => $snunit,
                'actionmenu' => $this->PermissionActionMenu('snunit-management'),
            );
            return view('parts-tracking/snunit-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function SnUnitMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('snunit-management')->c==1){
            $snunit = SnUnit::where('sn_unit', $request->sn_unit)->first();
            if(empty($snunit)){
            $create = SnUnit::create([
                'sn_unit'=>$request->sn_unit,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('snunit-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'SB Unit Already Exist!');
            }
        }else{
            return redirect('snunit-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function SnUnitMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('snunit-management')->u==1){
            $snunit = SnUnit::where('id_unit', $request->id_unit)->first();
            if(!empty($snunit)){
                $update = SnUnit::where('id_unit', $request->id_unit)
                ->update([
                    'sn_unit'=>$request->sn_unit,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('snunit-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('snunit-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function SnUnitMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('snunit-management')->d==1){
            $snunit = SnUnit::where('id_unit', $request->id_unit)->first();
            if(!empty($snunit)){
                SnUnit::where('id_unit', $request->id_unit)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('snunit-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
