<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\MstAssets;
use App\Models\Table\Elsa\HistoryAsset;
use App\Models\View\Elsa\VwAssetHistory;
use App\Models\Table\Elsa\MstLocation;
use App\Models\Table\Elsa\MstRoom;
use App\Models\View\Elsa\VwRoom;
use App\Models\Table\Elsa\MstAssetStatus;
use App\Models\View\Elsa\VwUsingAsset;
use App\Models\View\VwPermissionAppsMenu;
class AssetsManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('assets-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function AssetsMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('assets-management')->r==1){
        // $checkuser = MstAssets::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            // $assets = VwUsingAsset::where('dept',Auth::user()->dept)->orderBy('asset_name', 'asc')->get();
            // $no = 1;
            // foreach($assets as $data){
            //     $data->no = $no;
            //     $no++;
            // }
            $data = array(
                // 'assets' => $assets,
                'actionmenu' => $this->PermissionActionMenu('assets-management'),
            );
            return view('elsa/assets-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function getAsset()
    {
        try {
            $assets = MstAssets::where('dept',Auth::user()->dept)->orderBy('asset_desc', 'asc')->get();
            $data = array(
                'title' => "Assets",
                'req' => $assets,
            );
            return response()->json($data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function detailAsset($id)
    {
        try {
            $assets = MstAssets::where('asset_num',$id)->where('dept',Auth::user()->dept)->orderBy('asset_desc', 'asc')->first();
            if(empty($assets)){
                $data = [];
                return response()->json($data);
            }else{
                $location = MstLocation::where('flag',1)->orderBy('name', 'asc')->get();
                $room = VwRoom::where('flag',1)->orderBy('name', 'asc')->get();
                $status = MstAssetStatus::where('flag',1)->orderBy('name', 'asc')->get();
                $history = VwAssetHistory::where('asset_id',$assets->id)->orderBy('created_at', 'desc')->get();
                $user = User::select('id','email','name')->where('role_id','!=',30)->orderBy('name', 'asc')->get();
                $data = array(
                    'title' => "Assets",
                    'req' => $assets,
                    'location' => $location,
                    'room' => $room,
                    'status' => $status,
                    'history' => $history,
                    'user' => $user,
                );
                return response()->json($data);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function searchAsset($id)
    {
        try {
            $assets = MstAssets::where('asset_num', 'like', "%" . $id. "%")->orWhere('asset_sn', 'like', "%" . $id. "%")->where('dept',Auth::user()->dept)->orderBy('asset_desc', 'asc')->get();
            $data = array(
                'title' => "Assets",
                'req' => $assets,
            );
            return response()->json($data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function AssetsMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('assets-management')->c==1){
            $assets = MstAssets::where('name', $request->name)->first();
            if(empty($assets)){
            $create = MstAssets::create([
                'name'=>$request->name,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('assets-management')->with('suc_message', 'Data berhasil ditambahkan!');
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
    public function AssetsMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('assets-management')->u==1){
            $assets = MstAssets::where('id', $request->id)->first();
            if(!empty($assets)){
                $update = MstAssets::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('assets-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function AssetsMgmtUpdateDetail(Request $request)
    {
        if($this->PermissionActionMenu('assets-management')->u==1){
            $assets = MstAssets::where('id', $request->asset_id)->first();
            if(!empty($assets)){
                $update = MstAssets::where('id', $request->asset_id)
                ->update([
                    'asset_condition'=>$request->asset_condition,
                    'room'=>$request->room,
                    'pic'=>$request->pic,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                	 $create = HistoryAsset::create([
		                'asset_id'=>$request->asset_id,
		                'asset_condition'=>$request->asset_condition,
		                'room'=>$request->room,
		                'pic'=>$request->pic,
		                'note'=>$request->note,
		                'created_by' => Auth::user()->id,
		            ]);
                    return redirect('assets-management')->with('suc_message', 'Data berhasil diupdate!');
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
    public function AssetsMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('assets-management')->d==1){
            $assets = MstAssets::where('id', $request->id)->first();
            if(!empty($assets)){
                MstAssets::where('id', $request->id)->update([
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
