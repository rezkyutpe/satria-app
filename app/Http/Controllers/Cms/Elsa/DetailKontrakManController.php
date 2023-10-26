<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\MstCompanyList;
use App\Models\Table\Elsa\DetailRenewKontrak;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Elsa\CatMaintenance;
class KontrakManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('contract-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function kontrakMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->r==1){
        // $checkuser = DetailRenewKontrak::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $kontrak = DetailRenewKontrak::orderBy('created_at', 'desc')->get();
            $no = 1;
            foreach($kontrak as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'kontrak' => $kontrak,
                'actionmenu' => $this->PermissionActionMenu('contract-management'),
            );
            return view('elsa/contract-management/index')->with('data', $data);
           
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    
    public function kontrakMgmtAdd()
    {
      if($this->PermissionActionMenu('contract-management')->c==1){
            $response = $this->getUserSF(999);
            $arr = json_decode($response,true);
            $company = MstCompanyList::orderBy('id', 'asc')->get();
            $cat = CatMaintenance::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('durasi', 'asc')->get();
            $data = array(
                'emp' => $arr['emp'],
                'company' => $company,
                'cat' => $cat,
                'actionmenu' => $this->PermissionActionMenu('contract-management'),
            );
            return view('elsa/contract-management/add-contract')->with('data', $data);
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function kontrakMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->c==1){
            $kontrak = DetailRenewKontrak::where('kontrak_no_kontrak', $request->contract_num)->first();
            if(empty($kontrak)){
                if($request->file_contract){
                    $file_extention = $request->file_contract->getClientOriginalExtension();
                    $file_name = $request->contract_num.date('YmdHis').'.'.$file_extention;
                    $file_path = $request->file_contract->move($this->MapPublicPath().'contract',$file_name);
                }else{
                  $file_name='';
                }
            $create = DetailRenewKontrak::create([
                'kontrak_no_kontrak'=>$request->contract_num,
                'kontrak_perusahaan'=>$request->company,
                'kontrak_no_category'=>$request->category,
                'ctg_mtn_id'=>$request->duration,
                'kontrak_date'=>$request->contract_date,
                'kontrak_priority'=>$request->priority,
                'kontrak_pic_nrp'=>$request->priority,
                'kontrak_pic_email'=>$request->priority,
                'kontrak_pic_name'=>$request->priority,
                'kontrak_file'=>$file_name,
                'kontrak_desc'=>$request->desc,
                'status'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('contract-management')->with('suc_message', 'Data berhasil ditambahkan!');
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
    public function kontrakMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->u==1){
            $kontrak = DetailRenewKontrak::where('id', $request->id)->first();
            if(!empty($kontrak)){
                $update = DetailRenewKontrak::where('id', $request->id)
                ->update([
                    'name'=>$request->name,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('contract-management')->with('suc_message', 'Data berhasil diupdate!');
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
   
    public function kontrakMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->d==1){
            $kontrak = DetailRenewKontrak::where('id', $request->id)->first();
            if(!empty($kontrak)){
                DetailRenewKontrak::where('id', $request->id)->update([
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
