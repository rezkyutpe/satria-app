<?php

namespace App\Http\Controllers;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ErrorLogs;
use Illuminate\Http\Request;
use App\Exports\TicketExport;
use App\Models\UserRoleGroup;
use App\Models\View\Elsa\VwPr;
use App\Models\View\VwErrorLogs;
use App\Models\Table\Elsa\MstSla;
use App\Models\Table\Elsa\Ticket;
use App\Models\View\Elsa\VwTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Table\Elsa\UsingAsset;
use App\Models\View\Elsa\VwInventory;
use App\Models\TrxWfa;
use App\Models\View\VwTrxWfa;
use Illuminate\Support\Facades\Route;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Elsa\InventoryRequest;

class DashboardHeadController extends Controller
{
    public function __construct()
    {
          $this->middleware(function ($request, $next) {
            
            $cekhead = User::where('email', Auth::user()->email)->whereRaw("title REGEXP 'Department Head| Division Head| Chief| Director| System Application|'")->first();
            if (empty($cekhead)) {
                return redirect('welcome');
            }{
                return $next($request);
            }
            });
    }
    public function index()
    {
        try {
            $user = User::where('email',Auth::user()->email)->first();
            $req = VwTrxWfa::whereIn('approve_dept_to', [Auth::user()->email,$user->personal_number])->orWhereIn('approve_div_to', [Auth::user()->email,$user->personal_number])->orWhereIn('approve_dic_to', [Auth::user()->email,$user->personal_number])->get();
            $title = "Purchasing Request Approval";
            $company = "patria";
            $departementForDropdown = $this->getDepartement($company);
            $departement = Auth::user()->dept;
            $year = date("Y");
            return view('dashboard/satria-head-dashboard', compact('req','title','company','departement','departementForDropdown','year'));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TrxWfa()
    {
        try {
            $user = User::where('email',Auth::user()->email)->first();
            $submission = VwTrxWfa::whereIn('approve_dept_to', [Auth::user()->email,$user->personal_number])->orWhereIn('approve_div_to', [Auth::user()->email,$user->personal_number])->orWhereIn('approve_dic_to', [Auth::user()->email,$user->personal_number])->orderBy('created_at', 'desc')->get();
            $data = array(
                'title' => "WFA",
                'req' => $submission,
            );
            return response()->json($data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TrxPR()
    {
        try {
            $user = User::where('email',Auth::user()->email)->first();
            $req = VwPr::select(
                'pr_id as id_req',
                'pr_number as fk_id',
                'pr_category as fk_desc',
                'inventory_nama as ket',
                'pr_description as message',
                'rate',
                'accept_to',
                'approve_to',
                'accepted',
                'accepted_date',
                'accepted_remark',
                'approved',
                'approved_date',
                'approved_remark',
                'status',
                'created_at',
                'created_by'
            )->whereIn('accept_to', [Auth::user()->email,$user->personal_number])->orWhereIn('approve_to', [Auth::user()->email,$user->personal_number])->get();
            $data = array(
                'title' => "PR",
                'req' => $req,
            );
            return response()->json($data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TrxTicket(){
        try{
            $user = User::where('email',Auth::user()->email)->first();
            $req = VwTicket::select('id as id_req','ticket_id as fk_id','subject as fk_desc','dept_submiter','reporter_name as ket','message as message','rate','approve_dept_to','approve_dept','approve_dept_at','approve_dept_remark',
            'status','created_at','created_by')
            ->whereIn('approve_dept_to', [Auth::user()->email,$user->personal_number])->where('status','<=',3)->where('dept',Auth::user()->dept)
            ->orWhereIn('approve_div_to', [Auth::user()->email,$user->personal_number])->where('status','<=',3)
            ->orWhereIn('approve_dic_to', [Auth::user()->email,$user->personal_number])->where('status','<=',3)->orderBy('created_at', 'desc')->get();
            $data = array(
                'title'=>"Ticket Request Approval",
                'req' => $req,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function WfaActionApproval(Request $request)
    {
        try {
            $approval =  VwTrxWfa::where('id', $request->id_req)->first();
            if (empty($approval)) {
                return response()->json(['code' => 0, 'message' => 'Failed to Approval.']);
            } else {
                if ($request->submit == 'submit') {
                    if ($request->action == 'approve_dept') {
                        TrxWfa::where('id', $request->id_req)
                        ->update([
                            'approve_dept' => Auth::user()->email,
                            'approve_dept_at' => date('Y-m-d H:i:s'),
                            'approve_dept_remark' => $request->remark,
                            'status' => 1,
                            'updated_by' => Auth::user()->id,
                        ]);
                        return response()->json(['code' => 1, 'message' => 'Approval Data Success!']);
                    }else if ($request->action == 'approve_div') {
                        TrxWfa::where('id', $request->id_req)
                        ->update([
                            'approve_div' => Auth::user()->email,
                            'approve_div_at' => date('Y-m-d H:i:s'),
                            'approve_div_remark' => $request->remark,
                            'status' => 2,
                            'updated_by' => Auth::user()->id,
                        ]);
                        return response()->json(['code' => 1, 'message' => 'Approval Data Success!']);
                    }else if ($request->action == 'approve_dic') {
                        TrxWfa::where('id', $request->id_req)
                        ->update([
                            'approve_dic' => Auth::user()->email,
                            'approve_dic_at' => date('Y-m-d H:i:s'),
                            'approve_dic_remark' => $request->remark,
                            'status' => 3,
                            'updated_by' => Auth::user()->id,
                        ]);
                        return response()->json(['code' => 1, 'message' => 'Approval Data Success!']);
                    }
                }else 
                if ($request->submit == 'reject') {
                    TrxWfa::where('id', $request->id_req)
                        ->update([
                            'rejected' => Auth::user()->email,
                            'rejected_at' => date('Y-m-d H:i:s'),
                            'rejected_remark' => $request->remark,
                            'status' => 9,
                            'updated_by' => Auth::user()->id,
                        ]);
                    return response()->json(['code' => 1, 'message' => 'Approval Data Rejected!']);
                }
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TicketActionApproval(Request $request)
    {
        try {
            $approval =  VwTicket::where('id', $request->id_req)->first();
            if (empty($approval)) {
                return response()->json(['code' => 0, 'message' => 'Failed to Approval.']);
            } else {
                if ($request->submit == 'submit') {
                    if ($request->action == 'approve_dept') {
                        Ticket::where('id', $request->id_req)
                        ->update([
                            'approve_dept' => Auth::user()->email,
                            'approve_dept_at' => date('Y-m-d H:i:s'),
                            'approve_dept_remark' => $request->remark,
                            'status' => 1,
                            'updated_by' => Auth::user()->id,
                        ]);
                        return response()->json(['code' => 1, 'message' => 'Approval Data Success!']);
                    }else if ($request->action == 'approve_div') {
                        Ticket::where('id', $request->id_req)
                        ->update([
                            'approve_div' => Auth::user()->email,
                            'approve_div_at' => date('Y-m-d H:i:s'),
                            'approve_div_remark' => $request->remark,
                            'approve_dic' => Auth::user()->email,
                            'approve_dic_at' => date('Y-m-d H:i:s'),
                            'approve_dic_remark' => $request->remark,
                            'status' => 3,
                            'updated_by' => Auth::user()->id,
                        ]);
                        return response()->json(['code' => 1, 'message' => 'Approval Data Success!']);
                    }else if ($request->action == 'approve_dic') {
                        Ticket::where('id', $request->id_req)
                        ->update([
                            'approve_dic' => Auth::user()->email,
                            'approve_dic_at' => date('Y-m-d H:i:s'),
                            'approve_dic_remark' => $request->remark,
                            'status' => 3,
                            'updated_by' => Auth::user()->id,
                        ]);
                        return response()->json(['code' => 1, 'message' => 'Approval Data Success!']);
                    }
                }else 
                if ($request->submit == 'reject') {
                    Ticket::where('id', $request->id_req)
                        ->update([
                            'rejected' => Auth::user()->email,
                            'rejected_at' => date('Y-m-d H:i:s'),
                            'rejected_remark' => $request->remark,
                            'status' => 9,
                            'updated_by' => Auth::user()->id,
                        ]);
                    return response()->json(['code' => 1, 'message' => 'Approval Data Rejected!']);
                }
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public static function getAttendanceRatiodDashboard($year = null, $company = null, $departement = null)
    {
        try{
            if($year == null && $company == null && $departement == null){
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'attendance-ratio-report/patria/'.Auth::user()->dept.'/'.date("Y").'/0');
                $arr = json_decode($response,true);
                return $arr['data'];
            }else{
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'attendance-ratio-report/'.$company.'/'.$departement.'/'.$year.'/0');
                $arr = json_decode($response,true);
                return $arr['data'];
            }
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }
    public function DashboardAttendanceRatio(Request $request)
    {
        try {
            $year = $request->year_search;
            $company = $request->company_search;
            $departement = $request->departement_search;
            $response = null;
            if(!empty($year) && !empty($company) && !empty($departement)){
                $response = $this->getAttendanceRatiodDashboard($year,$company,$departement);
            }else{
                $response = $this->getAttendanceRatiodDashboard();
            }

            if (!empty($response) && $response['status'] == true) {
                if($response['mp'] > 0){
                    $totalMonth = count($response['safety']);
                    $resultAttendanceRatio = [];
                    $j = 0;
                    //Melakukan perulangan sebanyak total bulan
                    for ($i = 1; $i <= $totalMonth; $i++) {
                        foreach($response['safety'][$j] as $data){
                            //mencari total PRS
                            $totalPRS = 0;
                            $totalHariKerja = 0;
                            foreach($data as $PRS){
                               $totalPRS += $PRS['PRS'];
                            }
                            $totalMP = $response['mp'];
                            if(empty($year)){
                                $totalHariKerja = $this->getTotalWeekdaysInMonth(date("Y") .'-'. $i);
                            }else{
                                $totalHariKerja = $this->getTotalWeekdaysInMonth($year .'-'. $i);
                            }
                            $totalMandays = $totalHariKerja * $totalMP;
                            $totalAbsentism = $totalMandays - $totalPRS;
                            $attendanceRatio = ($totalAbsentism / $totalMandays) * 100;
                            $resultAttendanceRatio[] = [
                                'Month' => date("F", mktime(0, 0, 0, $i, 1, date("Y"))),
                                'PRS' => round($attendanceRatio)
                            ];
                        }
                        $j++;
                    }
                    return response()->json($resultAttendanceRatio);
                }else{
                    $resultAttendanceRatio = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $resultAttendanceRatio[] = [
                            'Month' => date("F", mktime(0, 0, 0, $i, 1, date("Y"))),
                            'PRS' => 0
                        ];
                    }
                    return response()->json($resultAttendanceRatio);
                }                
            } else {
                return response()->json(['error' => 'Attendance Ratio Not Found']);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return response()->json(['error' => 'Error Request, Exception Error']);
        }
    }
}
