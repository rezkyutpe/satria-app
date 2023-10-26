<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\MstCompanyList;
use App\Models\Table\Elsa\MstKontrak;
use App\Models\View\Elsa\VwHistoryKontrak;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Elsa\CatMaintenance;
use App\Models\Table\Elsa\CounterpartKontrak;
class KontrakManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('contract-management') == 0){
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function KontrakMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->r==1){
        // $checkuser = MstKontrak::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $kontrak = MstKontrak::where('status',1)->orderBy('created_at', 'desc')->get();
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
    public function KontrakMgmtDetail($id)
    {
        if($this->PermissionActionMenu('contract-management')->v==1){
            
            $kontrak = MstKontrak::where('kontrak_id', $id)->orderBy('created_at', 'desc')->first();
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
    
    public function KontrakMgmtAdd()
    {
      if($this->PermissionActionMenu('contract-management')->c==1){
            $response = $this->getUserSF(999);
            $arr = $response;
            $company = MstCompanyList::orderBy('company_name', 'asc')->get();
            $cat = CatMaintenance::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('durasi', 'asc')->get();
            $data = array(
                'emp' => $arr,
                'company' => $company,
                'cat' => $cat,
                'actionmenu' => $this->PermissionActionMenu('contract-management'),
            );
            return view('elsa/contract-management/add-contract')->with('data', $data);
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function KontrakMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->c==1){
            $kontrak = MstKontrak::where('kontrak_no_kontrak', $request->contract_num)->first();
            if(empty($kontrak)){
                if($request->file_contract){
                    $file_extention = $request->file_contract->getClientOriginalExtension();
                    $file_name = $request->contract_num.date('YmdHis').'.'.$file_extention;
                    $file_path = $request->file_contract->move($this->MapPublicPath().'contract',$file_name);
                }else{
                  $file_name='';
                }
            $create = MstKontrak::create([
                'kontrak_no_kontrak'=>$request->contract_num,
                'kontrak_perusahaan'=>$request->company,
                'kontrak_no_category'=>$request->category,
                'ctg_mtn_id'=>$request->duration,
                'kontrak_date'=>$request->contract_date,
                'kontrak_priority'=>$request->priority,
                'kontrak_pic_nrp'=>$request->nrp,
                'kontrak_pic_email'=>$request->email,
                'kontrak_pic_name'=>$request->name,
                'kontrak_file'=>$file_name,
                'kontrak_desc'=>$request->desc,
                'status'=>1,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    $id = MstKontrak::where('kontrak_no_kontrak', $request->contract_num)->first();
                    
                    if(count($request->counterpart_company) > 0)
                    {
                        foreach($request->counterpart_company as $item=>$v){
                            $data2=array(
                                'kontrak_id'=>$id->kontrak_id,
                                'counterpart_company'=>$request->counterpart_company[$item],
                                'counterpart_contact'=>$request->counterpart_contact[$item],
                                'created_by' => Auth::user()->id,
                            );
                            CounterpartKontrak::insert($data2);
                        }
                    }
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
    public function KontrakMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->u==1){
            $kontrak = MstKontrak::where('kontrak_id', $request->kontrak_id)->first();
            if(!empty($kontrak)){
                $update = MstKontrak::where('kontrak_id', $request->kontrak_id)
                ->update([
                    'kontrak_perusahaan'=>$request->kontrak_perusahaan,
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
   
    public function KontrakMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('contract-management')->d==1){
            $kontrak = MstKontrak::where('kontrak_id', $request->id)->first();
            if(!empty($kontrak)){
                MstKontrak::where('kontrak_id', $request->id)->update([
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
    public function getCounterPart($id)
    {
        $data = CounterpartKontrak::where('kontrak_id', $id)->get();

        return response()->json($data);
        
    }
}
