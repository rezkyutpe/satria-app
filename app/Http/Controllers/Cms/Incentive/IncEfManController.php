<?php

namespace App\Http\Controllers\Cms\Incentive;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\IncEF;
use App\Models\View\VwPermissionAppsMenu;
class IncEfManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('incef-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function IncEfMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('incef-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $incef = IncEF::where('descrip', 'like', "%" . $search. "%")->orderBy('id', 'asc')->simplePaginate($paginate);
                $incef->appends(['search' => $search]);
            } else {
                $incef = IncEF::orderBy('id', 'asc')->simplePaginate($paginate);
            }
            // $incef = IncEF::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($incef as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'incef' => $incef,
                'actionmenu' => $this->PermissionActionMenu('incef-management'),
            );
            return view('incentive/incef-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function IncEfMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('incef-management')->c==1){
            $incef = IncEF::where('id', $request->id)->first();
            if(empty($incef)){
            $create = IncEF::create([
                'id'=>$request->id,
                'descrip'=>$request->descrip,
                'percentage'=>$request->percentage,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('incef-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'IncEf Name Already Exist!');
            }
        }else{
            return redirect('incef-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function IncEfMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('incef-management')->u==1){
            $incef = IncEF::where('id', $request->id)->first();
            if(!empty($incef)){
                $update = IncEF::where('id', $request->id)
                ->update([
                    'descrip'=>$request->descrip,
                    'percentage'=>$request->percentage,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('incef-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('incef-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function IncEfMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('incef-management')->d==1){
            $incef = IncEF::where('id', $request->id)->first();
            if(!empty($incef)){
                IncEF::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('incef-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
