<?php

namespace App\Http\Controllers\Cms\PartsTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\PartsTracking\Diameter;
use App\Models\View\VwPermissionAppsMenu;
class DiameterController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('diameter-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function DiameterMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('diameter-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $diameter = Diameter::where('ukuran_diameter', 'like', "%" . $search. "%")->orderBy('id_diameter', 'asc')->simplePaginate($paginate);
                $diameter->appends(['search' => $search]);
            } else {
                $diameter = Diameter::orderBy('id_diameter', 'asc')->simplePaginate($paginate);
            }
            // $diameter = Diameter::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($diameter as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'diameter' => $diameter,
                'actionmenu' => $this->PermissionActionMenu('diameter-management'),
            );
            return view('parts-tracking/diameter-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function DiameterMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('diameter-management')->c==1){
            $diameter = Diameter::where('id_diameter', $request->id_diameter)->first();
            $nourut = Diameter::max('no_urut');

            if(empty($diameter)){
            $create = Diameter::create([
                'id_diameter'=>$request->id_diameter,
                'ukuran_diameter'=>$request->ukuran_diameter,
                'no_urut'=>$nourut+1,
                'max_wp'=>0,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('diameter-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Id Diameter Already Exist!');
            }
        }else{
            return redirect('diameter-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function DiameterMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('diameter-management')->u==1){
            $diameter = Diameter::where('id_diameter', $request->id_diameter)->first();
            if(!empty($diameter)){
                $update = Diameter::where('id_diameter', $request->id_diameter)
                ->update([
                    'ukuran_diameter'=>$request->ukuran_diameter,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('diameter-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('diameter-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function DiameterMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('diameter-management')->d==1){
            $diameter = Diameter::where('id_diameter', $request->id_diameter)->first();
            if(!empty($diameter)){
                Diameter::where('id_diameter', $request->id_diameter)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('diameter-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
