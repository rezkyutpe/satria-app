<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\MstRoom;
use App\Models\Table\Elsa\MstLocation;
use App\Models\View\VwPermissionAppsMenu;
class RoomManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            
            if ($this->PermissionMenu('room-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function RoomMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('room-management')->r==1){
        // $checkuser = MstRoom::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $room = MstRoom::where('flag',1)->orderBy('name', 'asc')->get();
            $location = MstLocation::where('flag',1)->orderBy('name', 'asc')->get();
            $no = 1;
            foreach($room as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'location' => $location,
                'room' => $room,
                'actionmenu' => $this->PermissionActionMenu('room-management'),
            );
            return view('elsa/room-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function RoomMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('room-management')->c==1){
            $room = MstRoom::where('id', $request->id)->where('dept', $request->dept)->first();
            if(empty($room)){
            $create = MstRoom::create([
                'id'=>$request->id,
                'name'=>$request->name,
                'location'=>$request->location,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('room-management')->with('suc_message', 'Data berhasil ditambahkan!');
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
    public function RoomMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('room-management')->u==1){
            $room = MstRoom::where('id', $request->id)->first();
            if(!empty($room)){
                $update = MstRoom::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'location'=>$request->location,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('room-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function RoomMgmtDelete(Request $request)
    {
       
        if($this->PermissionActionMenu('room-management')->d==1){
            $room = MstRoom::where('id', $request->id)->first();
            if(!empty($room)){
                MstRoom::where('id', $request->id)->update([
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
