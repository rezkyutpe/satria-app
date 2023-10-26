<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\MstLocation;
use App\Models\View\VwPermissionAppsMenu;
class LocationManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            
            if ($this->PermissionMenu('location-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function LocationMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('location-management')->r==1){
        // $checkuser = MstLocation::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $location = MstLocation::where('flag',1)->orderBy('name', 'asc')->get();
            $no = 1;
            foreach($location as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'location' => $location,
                'actionmenu' => $this->PermissionActionMenu('location-management'),
            );
            return view('elsa/location-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function LocationMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('location-management')->c==1){
            $location = MstLocation::where('id', $request->id)->first();
            if(empty($location)){
                $create = MstLocation::create([
                    'id'=>$request->id,
                    'name'=>$request->name,
                    'flag'=>1,
                    'created_by' => Auth::user()->id,
                ]);
                if($create){
                    return redirect('location-management')->with('suc_message', 'Data berhasil ditambahkan!');
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
    public function LocationMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('location-management')->u==1){
            $location = MstLocation::where('id', $request->id)->first();
            if(!empty($location)){
                $update = MstLocation::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('location-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function LocationMgmtDelete(Request $request)
    {
       
        if($this->PermissionActionMenu('location-management')->d==1){
            $location = MstLocation::where('id', $request->id)->first();
            if(!empty($location)){
                MstLocation::where('id', $request->id)->update([
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
