<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\InventoryCat;
use App\Models\View\VwPermissionAppsMenu;
class CatInvManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            
            if ($this->PermissionMenu('inv-category-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function CatInvMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('inv-category-management')->r==1){
        // $checkuser = InventoryCat::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $category = InventoryCat::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('name', 'asc')->get();
            $no = 1;
            foreach($category as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'category' => $category,
                'actionmenu' => $this->PermissionActionMenu('inv-category-management'),
            );
            return view('elsa/inv-category-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function CatInvMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('inv-category-management')->c==1){
            $category = InventoryCat::where('name', $request->name)->where('dept', $request->dept)->first();
            if(empty($category)){
            $create = InventoryCat::create([
                'name'=>$request->name,
                'dept'=>Auth::user()->dept,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('inv-category-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Nama Aplikasi Already Exist!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
       
    }
    public function CatInvMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('inv-category-management')->u==1){
            $category = InventoryCat::where('id', $request->id)->first();
            if(!empty($category)){
                $update = InventoryCat::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('inv-category-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function CatInvMgmtDelete(Request $request)
    {
       
        if($this->PermissionActionMenu('inv-category-management')->d==1){
            $category = InventoryCat::where('id', $request->id)->first();
            if(!empty($category)){
                InventoryCat::where('id', $request->id)->update([
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
