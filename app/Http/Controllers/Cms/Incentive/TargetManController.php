<?php

namespace App\Http\Controllers\Cms\Incentive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\TargetPercent;
use App\Models\View\VwPermissionAppsMenu;
class TargetManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('target-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function TargetPercentMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('target-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $target = TargetPercent::where('percentage', 'like', "%" . $search. "%")->orderBy('id', 'asc')->simplePaginate($paginate);
                $target->appends(['search' => $search]);
            } else {
                $target = TargetPercent::orderBy('id', 'asc')->simplePaginate($paginate);
            }
            // $target = TargetPercent::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($target as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'target' => $target,
                'actionmenu' => $this->PermissionActionMenu('target-management'),
            );
            return view('incentive/target-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function TargetPercentMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('target-management')->c==1){
            $target = TargetPercent::where('id', $request->id)->first();
            if(empty($target)){
            $create = TargetPercent::create([
                'id'=>$request->id,
                'min'=>$request->min,
                'max'=>$request->max,
                'percentage'=>$request->percentage,
                'cat'=>$request->cat,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('target-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'TargetPercent Name Already Exist!');
            }
        }else{
            return redirect('target-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TargetPercentMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('target-management')->u==1){
            $target = TargetPercent::where('id', $request->id)->first();
            if(!empty($target)){
                $update = TargetPercent::where('id', $request->id)
                ->update([
                    'min'=>$request->min,
                    'max'=>$request->max,
                    'percentage'=>$request->percentage,
                    'cat'=>$request->cat,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('target-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('target-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function TargetPercentMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('target-management')->d==1){
            $target = TargetPercent::where('id', $request->id)->first();
            if(!empty($target)){
                TargetPercent::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('target-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
