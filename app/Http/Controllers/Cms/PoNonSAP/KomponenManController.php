<?php

namespace App\Http\Controllers\Cms\PoNonSAP;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;
use PDF;
use App\Models\Table\PoNonSAP\MstPro;
use App\Models\View\PoNonSAP\VwPoPro;
use App\Models\Table\PoNonSAP\MstPo;
use App\Models\Table\PoNonSAP\Komponen;
use App\Models\Table\PoNonSAP\MstKomponen;
use App\Models\View\VwPermissionAppsMenu;

class KomponenManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('komponen-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function KomponenMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('komponen-management')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $komponen = MstKomponen::where('pn_patria', 'like', "%" . $search. "%")->orderBy('pn_patria', 'asc')->simplePaginate($paginate);
                $komponen->appends(['search' => $search]);
            } else {
                $komponen = MstKomponen::orderBy('pn_patria', 'asc')->simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($komponen as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'komponen' => $komponen,
            'actionmenu' => $this->PermissionActionMenu('komponen-management'),
            );

            return view('po-non-sap/komponen-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function getKomponen($pn_patria){

        $empData['data'] = MstKomponen::where('pn_patria',$pn_patria)->first();
  
        return response()->json($empData);
     
    }
    public function KomponenMgmtView($nopo)
    {
        if($this->PermissionActionMenu('komponen-management')->r==1){
            $komponen = MstKomponen::where('pn_patria', $nopo)->get();
            
            $data = array(
            'komponen' => $komponen
            );
        // echo $count;
            return view('po-non-sap/komponen-management/view-po')->with('data', $data);
        }else{
            return redirect('komponen-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function KomponenMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('komponen-management')->c==1){
            $komponen = MstKomponen::where('pn_patria', $request->pn_patria)->first();
            if(empty($komponen)){
            $create = MstKomponen::create([
                'pn_patria'=>$request->pn_patria,
                'description'=>$request->desc,
                'pn_vendor'=>$request->pn_vendor,
                'uom'=>$request->uom,
                'type' => $request->type,
            ]);
                if($create){
                    return redirect('komponen-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'PN Patria Already Exist!');
            }
        }else{
            return redirect('komponen-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function KomponenMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('komponen-management')->u==1){
            $komponen = MstKomponen::where('pn_patria', $request->pn_patria)->first();
            if(!empty($komponen)){
                $update = MstKomponen::where('pn_patria', $request->pn_patria)
                ->update([
                    'pn_patria'=>$request->pn_patrian,
                    'description'=>$request->desc,
                    'pn_vendor'=>$request->pn_vendor,
                'uom'=>$request->uom,
                    'type' => $request->type,
                ]);
                if($update){
                    return redirect('komponen-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'PN Patria Gagal ditemukan!');
            }
        }else{
            return redirect('komponen-management')->with('err_message', 'Akses Ditolak!');
        }    
    }
    public function KomponenMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('komponen-management')->d==1){
            $del = MstKomponen::where('pn_patria', $request->pn_patria)->first();
            if(!empty($del)){
                MstKomponen::where('pn_patria', $request->pn_patria)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('komponen-management')->with('err_message', 'Akses Ditolak!');
        }
    }


}
