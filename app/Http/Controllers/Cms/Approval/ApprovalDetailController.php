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
class ApprovalDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('approval-detail-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function ApprovalMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('approval-detail-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $approval = VwApprovalApps::where('approval_name', 'like', "%" . $search. "%")->orderBy('approval_name', 'asc')->simplePaginate($paginate);
                $approval->appends(['search' => $search]);
            } else {
                $approval = VwApprovalApps::orderBy('approval_name', 'asc')->simplePaginate($paginate);
            }
            $approvalapps = ApprovalApps::orderBy('approval_name', 'asc')->get();
            $user = User::orderBy('name', 'asc')->get();
            $no = 1;
            foreach($approval as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'approval' => $approval,
                'approvalapps' => $approvalapps,
                'user' => $user,
                'actionmenu' => $this->PermissionActionMenu('approval-detail-management'),
            );
            return view('approval/approval-detail-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function ApprovalMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('approval-detail-management')->c==1){
            // $approval = ApprovalApps::where('approval_name', $request->approval_name)->first();
            // if(empty($approval)){
            $create = ApprovalDetail::create([
                'approval'=>$request->approval,
                'ket'=>$request->ket,
                'get_link'=>$request->get_link,
                'post_link'=>$request->post_link,
                'response'=>$request->response,
                'approval_to'=>$request->approval_to,
                'approval_level'=>$request->approval_level,
                'status'=>'1',
                'reason'=>$request->reason,
                'created_by' => Auth::user()->id,
            ]);
                if($create){
                    return redirect('approval-detail-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            // }else{
            // return redirect()->back()->with('err_message', 'Apps Name Already Exist!');
            // }
        }else{
            return redirect('approval-detail-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function ApprovalMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('approval-detail-management')->u==1){
            $approval = ApprovalDetail::where('id', $request->id)->first();
            if(!empty($approval)){
                $update = ApprovalDetail::where('id', $request->id)
                ->update([
                    'approval'=>$request->approval,
                    'ket'=>$request->ket,
                    'get_link'=>$request->get_link,
                    'post_link'=>$request->post_link,
                    'response'=>$request->response,
                    'approval_to'=>$request->approval_to,
                    'approval_level'=>$request->approval_level,
                    'reason'=>$request->reason,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect('approval-detail-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('approval-detail-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function ApprovalMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('approval-detail-management')->d==1){
            $approval = ApprovalDetail::where('id', $request->id)->first();
            if(!empty($approval)){
                ApprovalDetail::where('id', $request->id)
                ->update([
                    'status'=>'0',
                    'updated_by' => Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('approval-detail-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
