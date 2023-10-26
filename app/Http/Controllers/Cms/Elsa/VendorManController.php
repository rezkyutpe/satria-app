<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\MstVendor;
use App\Models\View\VwPermissionAppsMenu;
class VendorManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('master-vendor') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function VendorMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('master-vendor')->r==1){
        // $checkuser = MstVendor::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $vendor = MstVendor::where('flag',1)->orderBy('name', 'asc')->get();
            $no = 1;
            foreach($vendor as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'vendor' => $vendor,
                'actionmenu' => $this->PermissionActionMenu('master-vendor'),
            );
            return view('elsa/vendor-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }  
    }
    public function VendorMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('master-vendor')->r==1){
            $vendor = MstVendor::where('name', $request->name)->first();
            if(empty($vendor)){
            $create = MstVendor::create([
                'code'=>$request->code,
                'name'=>$request->name,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('master-vendor')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Name Already Exist!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }  
       
    }
    public function VendorMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('master-vendor')->r==1){
            $vendor = MstVendor::where('id', $request->id)->first();
            if(!empty($vendor)){
                $update = MstVendor::where('id', $request->id)
                ->update([
                    'code'=>$request->code,
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('master-vendor')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }  
    }
   
    public function VendorMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('master-vendor')->r==1){
            $vendor = MstVendor::where('id', $request->id)->first();
            if(!empty($vendor)){
                MstVendor::where('id', $request->id)->update([
                    'flag'=>0,
                    'updated_by' => Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }  
    }
}
