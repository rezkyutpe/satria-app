<?php

namespace App\Http\Controllers\Cms\Incentive;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\CustType;
use App\Models\View\VwPermissionAppsMenu;
class CustTypeManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('custtype-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function CustTypeMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('custtype-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $custtype = CustType::where('descrip', 'like', "%" . $search. "%")->orderBy('id', 'asc')->simplePaginate($paginate);
                $custtype->appends(['search' => $search]);
            } else {
                $custtype = CustType::orderBy('id', 'asc')->simplePaginate($paginate);
            }
            // $custtype = CustType::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($custtype as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'custtype' => $custtype,
                'actionmenu' => $this->PermissionActionMenu('custtype-management'),
            );
            return view('incentive/custtype-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function CustTypeMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('custtype-management')->c==1){
            $custtype = CustType::where('id', $request->id)->first();
            if(empty($custtype)){
            $create = CustType::create([
                'id'=>$request->id,
                'descrip'=>$request->descrip,
                'percentage'=>$request->percentage,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('custtype-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'CustType Name Already Exist!');
            }
        }else{
            return redirect('custtype-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function CustTypeMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('custtype-management')->u==1){
            $custtype = CustType::where('id', $request->id)->first();
            if(!empty($custtype)){
                $update = CustType::where('id', $request->id)
                ->update([
                    'descrip'=>$request->descrip,
                    'percentage'=>$request->percentage,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('custtype-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('custtype-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function CustTypeMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('custtype-management')->d==1){
            $custtype = CustType::where('id', $request->id)->first();
            if(!empty($custtype)){
                CustType::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('custtype-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
