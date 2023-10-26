<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\MstCompanyList;
use App\Models\Table\Elsa\DetailRenewKontrak;
use App\Models\View\Elsa\VwHistoryKontrak;
use App\Models\View\Elsa\VwFirstRenewKontrak;
use App\Models\View\Elsa\VwLastRenewKontrak;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Elsa\CatMaintenance;
use App\Models\Table\Elsa\CounterpartKontrak;
class RenewKontrakManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('manage-contract') == 0){
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function RenewKontrakMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('manage-contract')->r==1){
        // $checkuser = DetailRenewKontrak::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            if (isset($request->query()['status'],$request->query()['cat'])){
                if($request->query()['status']=='last'){
                    $renew = VwLastRenewKontrak::where('kontrak_category',$request->query()['cat'])->where('status',1)->get();
                }else{
                    $renew = VwFirstRenewKontrak::where('kontrak_category',$request->query()['cat'])->where('status',1)->get();
                }
            }else if(isset($request->query()['status'])){
                if($request->query()['status']=='last'){
                    $renew = VwLastRenewKontrak::where('status',1)->get();
                }else{
                    $renew = VwFirstRenewKontrak::where('status',1)->get();
                }
            }else{
                $renew = VwFirstRenewKontrak::where('status',1)->get();
            }
            $cat = CatMaintenance::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('durasi', 'asc')->get();
            $no = 1;
            foreach($renew as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'renew' => $renew,
                'cat' => $cat,
                'actionmenu' => $this->PermissionActionMenu('manage-contract'),
            );
            return view('elsa/contract-management/manage-contract')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    public function RenewKontrakMgmtDetail($id)
    {
        if($this->PermissionActionMenu('manage-contract')->v==1){
            
            $kontrak = DetailRenewKontrak::where('kontrak_id', $id)->orderBy('created_at', 'desc')->first();
            $history = VwHistoryKontrak::where('kontrak_id', $id)->orderBy('created_at', 'desc')->get();
            $lastnew = VwHistoryKontrak::where('kontrak_id', $id)->orderBy('created_at', 'desc')->limit(5)->get();
            $data = array(
                'kontrak' => $kontrak,
                'history' => $history,
                'lastnew' => $lastnew,
            );
            return view('elsa/contract-management/detail-contract')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    
    public function RenewKontrakMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('manage-contract')->c==1){
            // $kontrak = DetailRenewKontrak::where('detail_renew_kontrak', $request->id)->first();
            // if(!empty($kontrak)){
                if($request->file_contract){
                    $file_extention = $request->file_contract->getClientOriginalExtension();
                    $file_name = $request->contract_num.date('YmdHis').'.'.$file_extention;
                    $file_path = $request->file_contract->move($this->MapPublicPath().'contract',$file_name);
                }else{
                  $file_name='';
                }
            $create = DetailRenewKontrak::create([
                'kontrak_id' =>  $request->kontrak_id,
                'detail_renew_date_renew' => $request->contract_deal_date,
                'detail_renew_note' =>  $request->contract_renew_note,
                'ctg_mtn_id' =>  $request->duration,
                'detail_renew_file' =>  $file_name,
                'detail_renew_no_kontrak' => $request->contract_renew_num,
                'detail_renew_ref_no_kontrak' => $request->kontrak_no_kontrak, 
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('manage-contract')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            // }else{
            //     return redirect()->back()->with('err_message', 'Contract Not Found!');
            // }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
       
    }
    public function RenewKontrakMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('manage-contract')->u==1){
            $kontrak = DetailRenewKontrak::where('kontrak_id', $request->id)->first();
            if(!empty($kontrak)){
                $update = DetailRenewKontrak::where('kontrak_id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('manage-contract')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function RenewKontrakMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('manage-contract')->d==1){
            $kontrak = DetailRenewKontrak::where('kontrak_id', $request->id)->first();
            if(!empty($kontrak)){
                DetailRenewKontrak::where('kontrak_id', $request->id)->update([
                    'status'=>0,
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
