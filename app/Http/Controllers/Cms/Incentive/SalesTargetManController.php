<?php

namespace App\Http\Controllers\Cms\Incentive;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\SalesTarget;
use App\Models\View\VwPermissionAppsMenu;
class SalesTargetManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('sales-target-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function SalesTargetMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('sales-target-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $salestarget = SalesTarget::where('sales_id', 'like', "%" . $search. "%")->orderBy('id', 'asc')->simplePaginate($paginate);
                $salestarget->appends(['search' => $search]);
            } else {
                $salestarget = SalesTarget::orderBy('id', 'asc')->simplePaginate($paginate);
            }
            $user = User::where('grade', '>0')->get();
            $no = 1;
            foreach($salestarget as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'salestarget' => $salestarget,
                'actionmenu' => $this->PermissionActionMenu('sales-target-management'),
            );
            return view('incentive/sales-target-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function SalesTargetMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('sales-target-management')->c==1){
            $salestarget = SalesTarget::where('id', $request->id)->first();
            if(empty($salestarget)){
            $create = SalesTarget::create([
                'id'=>$request->id,
                'sales_id'=>$request->sales_id,
                'month'=>$request->month,
                'year'=>$request->year,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('sales-target-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'SalesTarget Name Already Exist!');
            }
        }else{
            return redirect('sales-target-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function SalesTargetMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('sales-target-management')->u==1){
            $salestarget = SalesTarget::where('id', $request->id)->first();
            if(!empty($salestarget)){
                $update = SalesTarget::where('id', $request->id)
                ->update([
                    'sales_id'=>$request->sales_id,
                    'month'=>$request->month,
                    'year'=>$request->year,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('sales-target-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('sales-target-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function SalesTargetMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('sales-target-management')->d==1){
            $salestarget = SalesTarget::where('id', $request->id)->first();
            if(!empty($salestarget)){
                SalesTarget::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('sales-target-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
