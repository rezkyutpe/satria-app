<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\View\Elsa\VwScheduleMaintenance;
use App\Models\Table\Elsa\ScheduleMaintenance;
use App\Models\Table\Elsa\CatMaintenance;
use App\Models\Table\Elsa\MstAssets;
use App\Models\View\VwPermissionAppsMenu;
class ScheduleMaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('schedule-maintenance') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function ScheduleMainMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('schedule-maintenance')->r==1){
        // $checkuser = ScheduleMaintenance::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $schedule = VwScheduleMaintenance::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('created_at', 'asc')->get();
            $cat = CatMaintenance::where('dept',Auth::user()->dept)->orderBy('durasi', 'asc')->get();
            $assets = MstAssets::where('dept',Auth::user()->dept)->orderBy('asset_name', 'asc')->get();
            $no = 1;
            foreach($schedule as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'schedule' => $schedule,
                'cat' => $cat,
                'assets' => $assets,
                'actionmenu' => $this->PermissionActionMenu('schedule-maintenance'),
            );
            return view('elsa/schedule-maintenance/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function ScheduleMainMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('schedule-maintenance')->c==1){
           
            $create = ScheduleMaintenance::create([
                'asset'=>$request->asset,
                'category'=>$request->category,
                'dept'=>Auth::user()->dept,
                'flag'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('schedule-maintenance')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
       
    }
    public function ScheduleMainMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('schedule-maintenance')->u==1){
            $schedule = ScheduleMaintenance::where('id', $request->id)->first();
            if(!empty($schedule)){
                $update = ScheduleMaintenance::where('id', $request->id)
                ->update([
                    'asset'=>$request->asset,
                    'category'=>$request->category,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('schedule-maintenance')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function ScheduleMainMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('schedule-maintenance')->d==1){
            $schedule = ScheduleMaintenance::where('id', $request->id)->first();
            if(!empty($schedule)){
                ScheduleMaintenance::where('id', $request->id)->update([
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
