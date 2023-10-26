<?php

namespace App\Http\Controllers\Cms\Incentive;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\Gpm;
use App\Models\View\VwPermissionAppsMenu;
class GpmManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('gpm-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function GpmMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('gpm-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $gpm = Gpm::where('descrip', 'like', "%" . $search. "%")->orderBy('gpm', 'asc')->simplePaginate($paginate);
                $gpm->appends(['search' => $search]);
            } else {
                $gpm = Gpm::orderBy('gpm', 'asc')->simplePaginate($paginate);
            }
            // $gpm = Gpm::with(['countrys'])->where('role_gpm', 1)->get();
            $no = 1;
            foreach($gpm as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'gpm' => $gpm,
                'actionmenu' => $this->PermissionActionMenu('gpm-management'),
            );
            return view('incentive/gpm-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function GpmMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('gpm-management')->c==1){
            $gpm = Gpm::where('gpm', $request->gpm)->first();
            if(empty($gpm)){
            $create = Gpm::create([
                'gpm'=>$request->gpm,
                'min'=>$request->min,
                'max'=>$request->max,
                'percentage'=>$request->percentage,
                'cat'=>$request->cat,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('gpm-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Gpm Code Already Exist!');
            }
        }else{
            return redirect('gpm-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function GpmMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('gpm-management')->u==1){
            $gpm = Gpm::where('gpm', $request->gpm)->first();
            if(!empty($gpm)){
                $update = Gpm::where('gpm', $request->gpm)
                ->update([
                    'min'=>$request->min,
                    'max'=>$request->max,
                    'percentage'=>$request->percentage,
                    'cat'=>$request->cat,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('gpm-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('gpm-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function GpmMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('gpm-management')->d==1){
            $gpm = Gpm::where('gpm', $request->id)->first();
            if(!empty($gpm)){
                Gpm::where('gpm', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('gpm-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
