<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\MstBrand;
use App\Models\View\VwPermissionAppsMenu;
class BrandManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('brand-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function BrandMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('brand-management')->r==1){
        // $checkuser = MstBrand::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $brand = MstBrand::where('flag',1)->orderBy('name', 'asc')->get();
            $no = 1;
            foreach($brand as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'brand' => $brand,
                'actionmenu' => $this->PermissionActionMenu('brand-management'),
            );
            return view('elsa/brand-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function BrandMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('brand-management')->c==1){
            $brand = MstBrand::where('name', $request->name)->first();
            if(empty($brand)){
            $create = MstBrand::create([
                'name'=>$request->name,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('brand-management')->with('suc_message', 'Data berhasil ditambahkan!');
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
    public function BrandMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('brand-management')->u==1){
            $brand = MstBrand::where('id', $request->id)->first();
            if(!empty($brand)){
                $update = MstBrand::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('brand-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function BrandMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('brand-management')->d==1){
            $brand = MstBrand::where('id', $request->id)->first();
            if(!empty($brand)){
                MstBrand::where('id', $request->id)->update([
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
