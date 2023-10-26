<?php

namespace App\Http\Controllers\Cms\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\View\Approval\VwApprovalApps;
use App\Models\Table\Approval\ApprovalApps;
use App\Models\Table\Approval\ApprovalDetail;
use App\Models\View\VwPermissionAppsMenu;
class ApprovalAppsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('approval-apps-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function ApprovalMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('approval-apps-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $approval = ApprovalApps::where('approval_name', 'like', "%" . $search. "%")->orderBy('approval_name', 'asc')->simplePaginate($paginate);
                $approval->appends(['search' => $search]);
            } else {
                $approval = ApprovalApps::orderBy('approval_name', 'asc')->simplePaginate($paginate);
            }
            // $approval = ApprovalApps::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($approval as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'approval' => $approval,
                'actionmenu' => $this->PermissionActionMenu('approval-apps-management'),
            );
            return view('approval/approval-apps-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function ApprovalMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('approval-apps-management')->c==1){
            $approval = ApprovalApps::where('approval_name', $request->approval_name)->first();
            if(empty($approval)){
            $create = ApprovalApps::create([
                'approval_name'=>$request->approval_name,
                'get_link'=>$request->get_link,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('approval-apps-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Apps Name Already Exist!');
            }
        }else{
            return redirect('approval-apps-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ApprovalMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('approval-apps-management')->u==1){
            $approval = ApprovalApps::where('id', $request->id)->first();
            if(!empty($approval)){
                $update = ApprovalApps::where('id', $request->id)
                ->update([
                    'approval_name'=>$request->approval_name,
                    'get_link'=>$request->get_link,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('approval-apps-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('approval-apps-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function ApprovalMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('approval-apps-management')->d==1){
            $approval = ApprovalApps::where('id', $request->id)->first();
            if(!empty($approval)){
                ApprovalApps::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('approval-apps-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
