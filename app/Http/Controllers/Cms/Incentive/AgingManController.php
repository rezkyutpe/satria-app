<?php

namespace App\Http\Controllers\Cms\Incentive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\Aging;
use App\Models\View\VwPermissionAppsMenu;
class AgingManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('aging-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function AgingMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('aging-management')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $aging = Aging::where('descrip', 'like', "%" . $search. "%")->orderBy('id', 'asc')->simplePaginate($paginate);
                $aging->appends(['search' => $search]);
            } else {
                $aging = Aging::orderBy('id', 'asc')->simplePaginate($paginate);
            }
            // $aging = Aging::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($aging as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'aging' => $aging,
                'actionmenu' => $this->PermissionActionMenu('aging-management'),
            );
            return view('incentive/aging-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function AgingMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('aging-management')->c==1){
            $aging = Aging::where('id', $request->id)->first();
            if(empty($aging)){
            $create = Aging::create([
                'id'=>$request->id,
                'min'=>$request->min,
                'max'=>$request->max,
                'descrip'=>$request->descrip,
                'percentage'=>$request->percentage,
                'type'=>$request->type,
                'cat'=>$request->cat,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('aging-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'aging Name Already Exist!');
            }
        }else{
            return redirect('aging-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function AgingMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('aging-management')->u==1){
            $aging = Aging::where('id', $request->id)->first();
            if(!empty($aging)){
                $update = Aging::where('id', $request->id)
                ->update([
                    'min'=>$request->min,
                    'max'=>$request->max,
                'descrip'=>$request->descrip,
                    'percentage'=>$request->percentage,
                    'type'=>$request->type,
                    'cat'=>$request->cat,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('aging-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('aging-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function AgingMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('aging-management')->d==1){
            $aging = Aging::where('id', $request->id)->first();
            if(!empty($aging)){
                Aging::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('aging-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
