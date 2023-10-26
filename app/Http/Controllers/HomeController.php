<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Voucher;
use App\Models\MstApps;
use App\Models\Table\Elsa\MstDept;
use App\Models\MstInfo;
use App\Models\Attendance;
use App\Models\View\VwUserDetailAttendance;
use App\Models\View\VwUserDetail;
use App\Models\Table\Elsa\InventoryRequest;
use App\Models\Table\Elsa\Ticket;
use App\Models\Table\Elsa\Comment;
use App\Models\Table\Elsa\UsingAsset;
use App\Models\View\Elsa\VwTicket;
use App\Models\View\Elsa\VwComment;
use App\Models\View\Elsa\VwNotifComment;
use App\Models\View\Elsa\VwPr;
use App\Models\View\Elsa\VwUsingAsset;
use App\Models\Table\Elsa\History;
use App\Models\Table\Qfd\TrxMat;
use App\Models\Table\Elsa\MstSla;
use App\Models\Table\Incentive\RequestInc;
use Illuminate\Support\Facades\Route;
use App\Models\UserRoleGroup;
use App\Models\MstCompanyList;
use App\Models\Table\Elsa\MstMesin;
use App\Models\View\Elsa\VwInventoryRequestOut;
use Illuminate\Support\Facades\Storage;
use Exception;
use Redirect;
use PDF;

use App\Models\View\VwPermissionAppsMenu;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        try{
            $user = User::where('id', Auth::user()->id)->where('accessed_app', NULL)->first();
            $apps = MstApps::where('id', Auth::user()->accessed_app)->first();
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            if(!empty($user)){
                return redirect('welcome');
            }else{
                 if(empty($apps)){
                    return $this->home();
                }else{  
                    // dd($apps);          
                    if ($this->PermissionMenu($apps->link) == 0){
                        User::where('id', Auth::user()->id)
                          ->update([
                              'accessed_app' => NULL,
                              ]
                            );
                        return redirect('welcome')->with('err_message', 'Akses Ditolak !');
                    }
                    if($apps->sub_apps!=null){
                        // dd($actual_link.'/'.$apps->sub_apps.'/'.$apps->link)
                        return Redirect::to($actual_link.'/'.$apps->sub_apps.'/'.$apps->link);
                    }else{
                        return redirect($apps->link);
                    }
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function home()
    {
        return view('home');
    }
    public function about()
    {
        return view('about');
    }
    public function faq()
    {
        return view('faq');
    }
    public function welcome()
    {
        $apps = MstApps::orderBy('app_name', 'asc')->get();
        $info= MstInfo::get();
        $data = array(
            'apps' => $apps,
            'info' => $info,
        );
        return view('index')->with('data', $data);
    }
    
    public function UserTicket()
    {
        try{
            $ticketdata = VwTicket::where('reporter_nrp',Auth::user()->email)->orderBy('created_at', 'desc')->get();
            $data = array(
                'ticketdata' => $ticketdata,
            );
            return view('user-ticket')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function SearchMenu(Request $request){
        try{
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                return   redirect($search);
            } else {
                return redirect()->back()->with('err_message', 'No Result!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserPR(Request $request)
    {
        try{
            $prdata = VwPr::where('pr_nrp',Auth::user()->email)->orderBy('created_at', 'desc')->get();
            $data = array(
                'prdata' => $prdata,
            );
            return view('user-pr')->with('data', $data);

        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function RateTicket(Request $request)
    {
        try{
            $ticket = Ticket::where('id', $request->id)->first();
            if(!empty($ticket)){
                if($ticket->flag==5){
                Ticket::where('id', $request->id)
                  ->update([
                      'rate' => $request->rate,
                      'review' => $request->review,
                      ]
                    );
                }else{
                    Ticket::where('id', $request->id)
                    ->update([
                    'flag'=>5,
                    // 'resolve_time'=>date("Y-m-d H:i:s"),
                      'rate' => $request->rate,
                      'review' => $request->review,
                      ]
                    );
                    $ticketok = VwTicket::where('id', $request->id)->first();
                    
                    $this->setSla($request->id);
                    $this->setHistory($request->id,'Closed',$request->review,5);
                    $this->send($ticket->reporter_name,'Ticketing - Your Ticket Closed '.$ticket->ticket_id,Auth::user()->email_sf,$this->emailElsa($ticket->ticket_id,$ticketok->resolve_result,$ticket->asset_name,$ticket->message,$ticketok->assist_name,"Selesai ditutup dengan waktu "));
                }
                return redirect('user-ticket')->with('suc_message', 'Ticket Closed!');
            } else {
                return redirect()->back()->with('err_message', 'Error Closed Ticket!');
            }

        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function RatePR(Request $request)
    {
        try{
            $pr = InventoryRequest::where('pr_id', $request->id)->first();
            if(!empty($pr)){
                InventoryRequest::where('pr_id', $request->id)
                  ->update([
                      'rate' => $request->rate,
                      'review' => $request->review,
                      ]
                    );
               
                return redirect('user-pr')->with('suc_message', 'PR Review Success!');
            } else {
                return redirect()->back()->with('err_message', 'Error Review PR!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function CommentTicket(Request $request)
    {
        try{
            $ticket = Ticket::where('id', $request->id_ticket)->first();
            if(!empty($ticket)){
                Comment::where('id_ticket',  $request->id_ticket)
                  ->update([
                      'notification' => 0,
                      ]
                    );
                $input=Comment::create([
                      'text' => $request->text,
                      'id_ticket' => $request->id_ticket,
                      'notification' => 1,
                      'created_by' => Auth::user()->email,
                      ]
                    );
                return response()->json(['message'=>'Sended.']);
            } else {
                return response()->json(['message'=>'Not Sent']);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserMgmtViewSF($id)
    {
        try{
            $empData['data'] = $this->getUserSF($id);  
            return response()->json($empData);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
        
    }
    public function UserMgmtProfile()
    {
        try{
            $user = User::where('id', Auth::user()->id)->first();
            if(Auth::user()->role_id!='30'){
                $dept= MstDept::get();
                $company= MstCompanyList::where('company_code','!=','Console')->get();
            }else{
                $dept= [];
                $company= [];
            }

            $data = array(
                'company' => $company,
                'dept' => $dept,
                'user' => $user,
            );
            return view('auth/profile')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserAttendance(Request $request)
    {
        try{
            if(Auth::user()->role_id!='30'){
                if (isset($request->query()['q'])){
                    $q = $request->query()['q'];
                    $attendance= VwUserDetailAttendance::where('created_by',Auth::user()->id)->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at','desc')->get();
                }else{
                    $attendance= VwUserDetailAttendance::where('created_by',Auth::user()->id)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at','desc')->get();
                }
                $attendancemonth= VwUserDetailAttendance::where('created_by',Auth::user()->id)->where('created_at', 'like', "%" . date('Y-m'). "%")->get();
                $userdetail= VwUserDetail::where('user_id',Auth::user()->id)->first();
                $attendancetoday= VwUserDetailAttendance::where('created_by',Auth::user()->id)->where('created_at', 'like', "%" . date('Y-m-d'). "%")->first();
                $data = array(
                    'attendance' =>$attendance,
                    'userdetail' =>$userdetail,
                    'attendancemonth' =>$attendancemonth,
                    'attendancetoday' =>$attendancetoday,
                );

                // dd($data);
                return view('user-attendance')->with('data', $data);
            } else {
                return redirect()->back()->with('err_message', 'You Can\'t Access this Function!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function UserAttendanceDetail($id)
    {
        try{
            $attendancetoday= VwUserDetailAttendance::where('id',$id)->first();
            return response()->json($attendancetoday);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserCheckIn(Request $request)
    {
        try {
        	// echo '<img src='.$request->photo.'>';
            if($request->photo){
                $img = $request->photo;
                $img = str_replace('data:image/jpeg;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file_name = Auth::user()->email.'_in_'.date('YmdHis').'.jpeg';
                // $success = move_uploaded_file($data,$this->MapPublicPath().'attendance/'.$file_name);
                Storage::disk('public')->put('Attendance/In/'.$file_name, $data);
            }else{
                $file_name= NULL;
            }
            $create = Attendance::create([
                'remote_addr_in'=>$_SERVER['REMOTE_ADDR'],
                'longitude_in'=>$request->input('longitude'),
                'latitude_in'=>$request->input('latitude'),
                'work_metode'=>$request->input('metode'),
                'address_in'=>'',
                'in_time'=>date('Y-m-d H:i:s'),
                'foto_in' => $file_name,
                'subcont'=>Auth::user()->email,
                'client'=>'PT United Tractors Pandu Engineering',
                'created_by' => Auth::user()->id,
            ]);
            if($create){
                return redirect()->back()->with('suc_message', 'Check In Successfully!');
            }else{
                return redirect()->back()->with('err_message', 'Check In Not Successfully!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserCheckOut(Request $request)
    {
        try {
            if(isset($request->longitude) || isset($request->latitude)){
                if($request->photo){
                    $img = $request->photo;
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file_name = Auth::user()->email.'_out_'.date('YmdHis').'.jpeg';
                    // $success = move_uploaded_file($data,$this->MapPublicPath().'attendance/'.$file_name);
                    Storage::disk('public')->put('Attendance/Out/'.$file_name, $data);
                }else{
                    return redirect()->back()->with('err_message', 'Please Capture Your Attendance Picture');
                }
                $update = Attendance::where('id',$request->id)
                ->update([
                    'remote_addr_out'=>$_SERVER['REMOTE_ADDR'],
                    'longitude_out'=>$request->longitude,
                    'latitude_out'=>$request->latitude,
                    'address_out'=>'',
                    'foto_out' => $file_name,
                    'note'=>$request->note,
                    'out_time'=>date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect()->back()->with('suc_message', 'Check Out Successfully!');
                }else{
                    return redirect()->back()->with('err_message', 'Check Out Not Successfully!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Your Attendance Location Not Found, Please Allow your Location Permission');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function UserAttendanceUpdate(Request $request)
    {
        try {
            $attendance = Attendance::where('id',$request->id)->first();
            if(!empty($attendance)){
                $revice_in_time=date('Y-m-d',strtotime($attendance->in_time)).' '.$request->in_time;
                $revice_out_time=date('Y-m-d',strtotime($attendance->in_time)).' '.$request->out_time;
                $update = Attendance::where('id',$request->id)
                ->update([
                    'is_ovt'=>$request->ovt,
                    'note'=>$request->note,
                    'revice_in_time'=>($attendance->in_time==$revice_in_time)?NULL:$revice_in_time,
                    'revice_out_time'=>($attendance->out_time==$revice_out_time)?NULL:$revice_out_time,
                    'updated_by' => Auth::user()->id,
                ]);
                if($update){
                    return redirect()->back()->with('suc_message', 'Revice Attendance Successfully!');
                }else{
                    return redirect()->back()->with('err_message', 'Revice Attendance Not Successfully!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Attendance Id Not Found');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function UserAttendanceExport(Request $request) {
        try{
            if (isset($request->query()['q'])){
                $q = $request->query()['q'];
                $attendance = VwUserDetailAttendance::where('created_at', 'like', "%" . $q. "%")->where('created_by',Auth::user()->id)->orderBy('created_at', 'asc')->get();
            } else {
                return redirect()->back()->with('err_message', 'Please Select Month to Export Your Attendance Report');
            }
             $userdetail= VwUserDetail::where('user_id',Auth::user()->id)->first();
            $data = array(
                'userdetail' => $userdetail,
                'attendance' => $attendance,
            );
            $pdf = PDF::loadView('attendance-export', $data);
            return $pdf->stream('Attendance_Report_'.$request->query()['q'].'.pdf');   
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }  
    }
    public function UserMgmtProfileAdmin()
    {
        try{
            $user = User::where('id', Auth::user()->id)->first();
            $data = array(
                'user' => $user,
            );
            return view('auth/profile-admin')->with($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserMgmtConfigApp()
    {
        try{
            $user = User::where('id', Auth::user()->id)->first();
            $apps =  VwPermissionAppsMenu::select('app','app_name')->where('user', Auth::user()->id)->groupBy('app_name')->get();
            $data = array(
                'user' => $user,
                'apps' => $apps,
            );
            return view('auth/config-app')->with($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserMgmtUpdateConfig(Request $request)
    {
        try{
           // $user = User::where('id', Auth::user()->id)->first();
            $user =  VwPermissionAppsMenu::where('app', $request->apps)->where('user', Auth::user()->id)->get();
            if(!empty($user)){
               $update = User::where('id', Auth::user()->id)
                  ->update([
                      'accessed_app' => $request->apps,
                      ]
                    );
               
                return redirect('/')->with('suc_message', 'Data telah diperbarui!'.$request->apps);
            } else {
                return redirect('welcome')->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserMgmtProfilePassword()
    {
        try{
            if(Auth::user()->role_id=='30' || Auth::user()->role_id=='10'){
                return view('auth/profile-password');
            } else {
                return redirect()->back()->with('err_message', 'You Can\'t Access this Function!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserMgmtChangePass(Request $request)
    {
        try{
            $user = User::where('id', Auth::user()->id)->first();
            if ($request->password != $request->password_confirmation) {
                return redirect()->back()->with('err_message', 'Re-Type Password Not Match!');
            }else{
                if(!empty($user)){
                    if(Hash::check($request->old_password, $user->password)){
                    User::where('id', Auth::user()->id)
                        ->update([
                            'password' => Hash::make($request->password),
                            ]
                        );
                    }else {
                        return redirect()->back()->with('err_message', 'Old Password Not Match!');
                    }
                    if(!empty($request->password)){
                        User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                    }
                    return redirect()->back()->with('suc_message', 'Data telah diperbarui!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtUpdateProfile(Request $request)
    {
        try{
            $dept= MstDept::where('id',$request->dept)->first();
            $email = User::where('email', $request->email)->first();
            
            if (!empty($email)) {
                if($request->photo){
                    $file_extention = $request->photo->getClientOriginalExtension();
                    $file_name = $request->email.'.'.$file_extention;
                    $file_path = $request->photo->move($this->MapPublicPath().'profile',$file_name);
                }else{
                    if($email->photo)
                    {
                        $file_name=$email->photo;
                    }else{
                        $file_name= NULL;
                    }
                }
                User::where('email', $request->email)
                ->update([
                    'name' => $request->full_name,
                    'phone'=>$request->phone,
                    'dept' => $request->dept,
                    'department' => $dept->nama,
                    'division' => $request->division,
                    'company_name' => $request->company,
                    'title' => $request->title,
                    'photo' => $file_name,
                    ]
                    );
                return redirect('profile')->with('suc_message', 'Data telah diperbarui!');
                // echo $request->dept;
                // dd($dept);
            }else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserHelpRequest(Request $request)
    {
        try {
            $dept= MstDept::where('id',$request->dept)->first();
            $accept_to='';
            $userdept = MstDept::where('id',Auth::user()->dept)->first();
            if(!empty($userdept)){
            $accept_to=$userdept->depthead_nrp;
            }
            if($request->action=='pr'){
                
                $no = InventoryRequest::where('created_at', 'like', date('Y-m-d'). "%")->count();
                $pr_num = 'PR'.date('Ymd').str_pad($no, 4, "0", STR_PAD_LEFT); //PR20210101000
                $pruser = $request->pruser != null?  implode(",",$request->input('pruser')) : null;
               // if(!empty($inventory)){
                    $create = InventoryRequest::create([
                        'pr_number'=>$pr_num,
                        'pr_nrp'=>Auth::user()->email,
                        'pr_name'=>Auth::user()->name,
                        'pr_dept'=>Auth::user()->department,
                        'dept'=>$request->dept,
                        'accept_to'=>isset($this->getDirectManager(Auth::user()->email)[0]['direct_manager']) ? $this->getDirectManager(Auth::user()->email)[0]['direct_manager'] : $accept_to,
                        'approve_to'=>$dept->depthead_nrp,
                        'pr_category'=>$request->category,
                        'pr_quantity'=>$request->qty,
                        'pr_description'=>$request->message,
                        'pr_remark'=>'',
                        'pr_to'=>$pruser,
                        'created_by' => Auth::user()->id,
                    ]);
                    if($create){
                         if(!empty($this->getDirectManager(Auth::user()->email)[0]['direct_manager_email'])){
                        $this->send($this->getDirectManager(Auth::user()->email)[0]['direct_manager_nama'],'Purchasing Request -  PR Need Your Approval '.$pr_num,$this->getDirectManager(Auth::user()->email)[0]['direct_manager_email'],$this->emailPR($pr_num,Auth::user()->name,$request->category,$request->message,$request->qty,"berhasil dibuat"));
                        }
                        return redirect()->back()->with('suc_message', 'Request PR Berhasil!');
                    }else{
                        return redirect()->back()->with('err_message', 'Request PR Gagal!');
                    }
            }else{
                $no = Ticket::where('created_at', 'like', date('Y-m-d'). "%")->count();
                $tk_num = 'TK'.date('Ymd').str_pad($no, 4, "0", STR_PAD_LEFT); 
                $assist = UserRoleGroup::select('user_role_group.*','users.name','users.email_sf')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept',$request->dept)->whereIn('group', [14,16])->get();
                if($request->media){
                    $file_extention = $request->media->getClientOriginalExtension();
                    $file_name = $tk_num.date('YmdHis').'.'.$file_extention;
                    $file_path = $request->media->move($this->MapPublicPath().'ticket',$file_name);
                }else{
                  $file_name='blank.png';
                }//PR20210101000
                $prioriy = '2';
                $cekpriority = User::where('email',Auth::user()->email)->whereRaw("title REGEXP 'Department Head| Division Head| Director'")->first();
                if(!empty($cekpriority)){
                    $prioriy = '3';
                }
                // $approved=isset($this->getDirectManager(Auth::user()->email)[0]['direct_manager']) ? $this->getDirectManager(Auth::user()->email)[0]['direct_manager'] : $accept_to;
                // $approved_date=date('Y-m-d H:i:s');
                // $approved_remark='Ok';

                // $divh = "1103009";
                // $dich = "1103009";
            	$accept_to=$dept->depthead_nrp;
                if($request->dept=='8915'){
	                $deptheadresult = $this->getDirectManager($dept->depthead_nrp)[0]['direct_manager'];
	                $divh = isset($deptheadresult) ? $deptheadresult : "1103009";
	                $divheadresult = $this->getDirectManager($divh)[0]['direct_manager'];
	                $dich = isset($divheadresult) ? $divheadresult : "1103009";
	            }else{
	            	$divh = '';
	            	$dich = '';
	            }
	            $status=4;
                // if($request->dept=='8931'){
                //     $approved=null;
                //     $approved_date=null;
                //     $approved_remark=null;
                //     $status=1;
                // }else{
                //     $approved=isset($this->getDirectManager(Auth::user()->email)[0]['direct_manager']) ? $this->getDirectManager(Auth::user()->email)[0]['direct_manager'] : $accept_to;
                //     $approved_date=date('Y-m-d H:i:s');
                //     $approved_remark='Ok';
                //     $status=2;
                // }
                $emailassist=array();
                foreach($assist as $row){
                    if($row->email_sf!=''){
                        array_push($emailassist,$row->email_sf);
                    }
                }
                    // print_r($emailassist);
                    // echo implode(",",$emailassist);
                $create = Ticket::create([
                    'ticket_id'=>$tk_num,
                    'reporter_nrp'=>Auth::user()->email,
                    'reporter_by'=>Auth::user()->id,
                    'subject'=>$request->subject,
                    'dept' => $request->dept,
                    'asset_id' => $request->asset,
                    'message' => $request->message,
                    'location' => $request->location,
                    'priority'=>$prioriy,
                    'approve_dept_to'=>$accept_to,
                    'approve_div_to'=>$divh,
                    'approve_dic_to'=>$dich,
                    // 'approve_dept'=>$approved,
                    // 'approve_dept_at'=>$approved_date,
                    // 'approve_dept_remark'=>$approved_remark,
                    'status' => $status,
                    'media'=>$file_name,
                    'created_by' => Auth::user()->id,
                ]);
                if($create){
                        $sent = $this->send(implode(",",$emailassist),'New Ticket '. $tk_num.' : '.$request->subject,explode(",",implode(",",$emailassist)),'New Ticket #'.$tk_num.' has created with by '.Auth::user()->name.' with message '.$request->message);
                 // $ticket = VwTicket::where('ticket_id', $tk_num)->first();
                 //        if(!empty($ticket->email_sf)){
                 //        $this->send($ticket->reporter_name,'Ticketing -  Ticket Created '.$ticket->ticket_id,Auth::user()->email,$this->emailElsa($ticket->ticket_id,$ticket->resolve_time,$ticket->asset_name,$ticket->message,$ticket->assist_name,"berhasil dibuat"));
                 //    }
                    return redirect()->back()->with('suc_message', 'Request Ticket Berhasil!');
                }else{
                    return redirect()->back()->with('err_message', 'Request Ticket Gagal!');
                }
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function NotifRead()
    {
           
            $ratings =  VwNotifComment::where('reporter_nrp',Auth::user()->email)->where('created_by','!=',Auth::user()->email)->orderBy('notif', 'desc')->limit( 5 )->get();
            $output = '';
            if(count($ratings) > 0)
            {
              foreach ($ratings as $row) {
                  $output .= '
                  <li class="list-group-item"><a onclick="getChat('.$row->id_ticket .')" >
                      '.$row->ticket_id.'<span class="label label-pill label-danger pull-right" >'.$row->notif.'</span><br>
                      <small><i class="fa fa-clock-o"></i> '.$row->name.'</small></a>
                  </li>';
             }
            }
            else{
                 $output .= '
                 <li><a href="#" class="text-bold text-italic">Notification Not Found</a></li>';
            }
            $output .='<div class="card text-center">
                  <a href="#" style="padding: 20px;display:block;" id="load-more-btn" onmouseover="loadmorebutton()">More</a>
              </div>';
            $notify = VwNotifComment::where('reporter_nrp',Auth::user()->email)->where('notif','!=',0)->orderBy('notif', 'desc')->get();
            $count = count($notify);
            $data = array(
                'notification' => $output,
                'unseen_notification'  => $count
            );
            echo json_encode($data);
          
    }
    public function NotifReadAll()
    {
      if(isset($_GET['view'])){

      if($_GET["view"] != '')
      {
            // $updated = Notifikasi::where('to_users_id', Auth::user()->id)->update([
            //       'read_at' => date('Y-m-d H:i:s')
            //     ]);
              }
            $ratings = VwNotifComment::where('reporter_nrp',Auth::user()->email)->where('created_by','!=',Auth::user()->email)->orderBy('notif', 'desc')->limit( 20 )->get();
            $output = '';
            foreach ($ratings as $row) {
                $output .= '
                <li class="list-group-item">
                    '.$row->content.'<br>
                    <small><i class="fa fa-clock-o"></i> '.$row->created_at.'</small>
                </li>';
            }
            $notify = VwNotifComment::where('reporter_nrp',Auth::user()->email)->where('notif','!=',0)->orderBy('notif', 'desc')->get();
            $count = count($notify);
            $data = array(
                'notification' => $output,
                'unseen_notification'  => $count
            );
            echo json_encode($data);
          }
    }
    
    public function getChat($ticket){
        try{
            $empData['data'] = VwComment::where('id_ticket',$ticket)->get();
            return response()->json($empData);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    
    public function getTicket($ticket){
        try{
            $empData = History::where('id_ticket',$ticket)->get();
            $ticket = VwTicket::where('id', $ticket)->first();
      
            $data = array(
                'data' => $empData,
                'ticket' => $ticket,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function getPR($pr){
        try{
            $pr = VwPr::where('pr_id', $pr)->first();
      
            $data = array(
                'pr' => $pr,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function getQfdApproval(){
        try{
            $req = TrxMat::select('id as id_req','material_number as fk_id','material_description as fk_desc','note as ket','accept_to','approve_to','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark','status','created_at','created_by')->get();
            
            $data = array(
                'title'=>"QFD",
                'req' => $req,
            );
            return view('list-request-approval')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function getIncApproval(){
        try{
            $req = RequestInc::select('id_req','sales as fk_id','req_month as fk_desc','ket','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark','status','created_at','created_by')->get();
            $data = array(
                'title'=>"Incentive",
                'req' => $req,
            );
            return view('list-request-approval')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function getPrApproval(){
        try{
            $user = User::where('email',Auth::user()->email)->first();
            $req = VwPr::select('pr_id as id_req','pr_number as fk_id','pr_category as fk_desc','inventory_nama as ket','pr_description as message','rate','accept_to','approve_to','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark',
            'status','created_at','created_by')->whereIn('accept_to',[Auth::user()->email,$user->personal_number])->orWhereIn('approve_to',[Auth::user()->email,$user->personal_number])->get();
            $data = array(
                'title'=>"Purchasing Request Approval",
                'req' => $req,
            );
            return view('list-request-approval')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function getTicketApproval(){
        try{
            $req = VwTicket::select('id as id_req','ticket_id as fk_id','subject as fk_desc','reporter_name as ket','message as message','rate','approve_dept_to','approved','approved_date','approved_remark',
            'status','created_at','created_by')->where('approve_dept_to',Auth::user()->email)->get();
            $data = array(
                'title'=>"Ticket Request Approval",
                'req' => $req,
            );
            return view('list-request-approval')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function viewPrApproval($id){
        try{
            $req = VwPr::select('pr_id as id_req','pr_to','pr_number as fk_id','pr_category as fk_desc','inventory_nama as ket','accept_to','approve_to','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark',
            'status','pr_nrp as nrp','pr_name as emp_name','pr_dept as emp_dept','phone','pr_description as message','pr_quantity as qty','dept_name as approval_to','rate','accept_name as accepted_name','accept_title','approve_name as approved_name','approve_title', 'created_at','created_by','accept_to_name','approve_to_name','rejected','rejected_date','rejected_remark')->where('pr_id',$id)->first();
            $prreqout = VwInventoryRequestOut::where('pr_id',$id)->get();
            $data = array(
                'req' => $req,
                'prreqout' => $prreqout,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function viewTicketApproval($id){
        try{
            $req = VwTicket::select('id as id_req','ticket_id as fk_id','subject as fk_desc','reporter_name as ket','vw_ticket.*')->where('id',$id)->first();
            $data = array(
                'req' => $req,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function PrApprovalAction(Request $request)
    {
        try{
            $approval =  InventoryRequest::where('pr_id',$request->id_req)->first();
            if (empty($approval)) {
                return redirect()->back()->with('err_message', 'Approval Action Gagal!');
            }else{
                if($request->submit=='submit'){
                    if($request->action=='accept'){
                        InventoryRequest::where('pr_id', $request->id_req)
                            ->update([
                            'accepted'=>Auth::user()->email,
                            'accepted_date'=>date('Y-m-d H:i:s'),
                            'accepted_remark'=>$request->remark,
                            'status' => 1,
                            'updated_by' =>Auth::user()->id,
                        ]);
                        return redirect()->back()->with('suc_message', 'Approval Data Accepted!');
                    }else if($request->action=='approve'){
                        InventoryRequest::where('pr_id', $request->id_req)
                            ->update([
                            'approved'=>Auth::user()->email,
                            'approved_date'=>date('Y-m-d H:i:s'),
                            'approved_remark'=>$request->remark,
                            'status' => 2,
                            'updated_by' =>Auth::user()->id,
                        ]);
                        return redirect()->back()->with('suc_message', 'Approval Data Approved!');
                    }else{
                        return redirect()->back()->with('err_message', 'No Action Proccesed!');
                    }
                }else if($request->submit=='reject'){
                    InventoryRequest::where('pr_id', $request->id_req)
                        ->update([
                        'rejected'=>Auth::user()->email,
                        'rejected_date'=>date('Y-m-d H:i:s'),
                        'rejected_remark'=>$request->remark,
                        'status' => 9,
                        'updated_by' =>Auth::user()->id,
                    ]);
                    return redirect()->back()->with('suc_message', 'Approval Data Rejected!');
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    
    public function PrApprovalActionPost(Request $request)
    {
        try{
            $approval =  InventoryRequest::where('pr_id',$request->id_req)->first();
            if (empty($approval)) {
                return response()->json(['code'=>0, 'message'=>'Failed to Approval.']);
            }else{
                if($request->submit=='submit'){
                    if($request->action=='accept'){
                        InventoryRequest::where('pr_id', $request->id_req)
                            ->update([
                            'accepted'=>Auth::user()->email,
                            'accepted_date'=>date('Y-m-d H:i:s'),
                            'accepted_remark'=>$request->remark,
                            'status' => 1,
                            'updated_by' =>Auth::user()->id,
                        ]);
                        return response()->json(['code'=>1, 'message'=>'Approval Data Accepted!']);
                    }else if($request->action=='approve'){
                        InventoryRequest::where('pr_id', $request->id_req)
                            ->update([
                            'approved'=>Auth::user()->email,
                            'approved_date'=>date('Y-m-d H:i:s'),
                            'approved_remark'=>$request->remark,
                            'status' => 2,
                            'updated_by' =>Auth::user()->id,
                        ]);
                        return response()->json(['code'=>1, 'message'=>'Approval Data Approved!']);
                    }else{
                        return response()->json(['code'=>0, 'message'=>'No Action Proccesed!']);
                    }
                }else if($request->submit=='reject'){
                    InventoryRequest::where('pr_id', $request->id_req)
                        ->update([
                        'rejected'=>Auth::user()->email,
                        'rejected_date'=>date('Y-m-d H:i:s'),
                        'rejected_remark'=>$request->remark,
                        'status' => 9,
                        'updated_by' =>Auth::user()->id,
                    ]);
                    return response()->json(['code'=>1, 'message'=>'Approval Data Rejected!']);
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TicketApprovalAction(Request $request)
    {
        try{
            $ticket =  VwTicket::where('id',$request->id_req)->first();
            if (empty($ticket)) {
                return redirect()->back()->with('err_message', 'Approval Action Gagal!');
            }else{
                if($request->submit=='submit'){
                    if($request->action=='approve'){
                        Ticket::where('id', $request->id_req)
                            ->update([
                            'approved'=>Auth::user()->email,
                            'approved_date'=>date('Y-m-d H:i:s'),
                            'approved_remark'=>$request->remark,
                            'status' => 2,
                            'updated_by' =>Auth::user()->id,
                        ]);
                        return redirect()->back()->with('suc_message', 'Approval Data Approved!');
                    }else{
                        return redirect()->back()->with('err_message', 'No Action Proccesed!');
                    }
                }else if($request->submit=='reject'){
                    Ticket::where('id', $request->id_req)
                        ->update([
                        'rejected'=>Auth::user()->email,
                        'rejected_date'=>date('Y-m-d H:i:s'),
                        'rejected_remark'=>$request->remark,
                        'status' => 9,
                        'flag'=>10,
                        'updated_by' =>Auth::user()->id,
                    ]);
                    if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing - Your Ticket Rejected '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,$ticket->resolve_time,$ticket->asset_name,$request->note,$ticket->assist_name,"Rejected"));
                        }
                    return redirect()->back()->with('suc_message', 'Approval Data Rejected!');
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    public function TicketApprovalActionPost(Request $request)
    {
        try{
            $ticket =  VwTicket::where('id',$request->id_req)->first();
            if (empty($ticket)) {
                return response()->json(['code'=>0, 'message'=>'Failed to Approval.']);
            }else{
                if($request->submit=='submit'){
                    if($request->action=='approve'){
                        Ticket::where('id', $request->id_req)
                            ->update([
                            'approved'=>Auth::user()->email,
                            'approved_date'=>date('Y-m-d H:i:s'),
                            'approved_remark'=>$request->remark,
                            'status' => 2,
                            'updated_by' =>Auth::user()->id,
                        ]);
                        return response()->json(['code'=>0, 'message'=>'Approval Data Approved!']);
                    }else{
                        return response()->json(['code'=>0, 'message'=>'No Action Proccesed!']);
                    }
                }else if($request->submit=='reject'){
                    Ticket::where('id', $request->id_req)
                        ->update([
                        'rejected'=>Auth::user()->email,
                        'rejected_date'=>date('Y-m-d H:i:s'),
                        'rejected_remark'=>$request->remark,
                        'status' => 9,
                        'flag'=>10,
                        'updated_by' =>Auth::user()->id,
                    ]);
                    if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing - Your Ticket Rejected '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,$ticket->resolve_time,$ticket->asset_name,$request->note,$ticket->assist_name,"Rejected"));
                        }
                    return response()->json(['code'=>0, 'message'=>'Approval Data Rejected!']);
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function setHistory($idticket,$title,$desc,$flow)
    {
        try{
            $create = History::create([
                'id_ticket'=>$idticket,
                'title'=>$title,
                'description'=>$desc,
                'order'=>$flow,
                'people' => Auth::user()->id,
            ]);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    // Sla Start
    public function setSla($id)
    {
        $ticket =  Ticket::where('id', $id)->first();

        $sla = MstSla::where('id', $ticket->sla)->first();

        $data =  $this->get_sla_count($ticket->respond_time, $ticket->resolve_time, $sla->resolution_time);  

        if ($data['status'] == 'passed') 
        {
            $update = Ticket::where('id', $id)
            ->update([
                'resolve_status' => '1'
            ]);
        }
        $update = Ticket::where('id', $id)
        ->update([
            'resolve_result' => $data['hasil'],
            'resolve_percent' => $data['percent']
        ]);
    }
    public function get_sla_count($awal, $akhir, $sla)
    { 
        $intime = '07:30:00'; //get from DB or API
        $outtime = '16:30:00'; //get rom DB or API

        $dateToday = date("Y-m-d"); //Date today
        
        $dateTimeA  = $awal; //parameter
        $timestampA = strtotime($dateTimeA);
        $dateA      = date("Y-m-d", $timestampA);

        $dateTimeB  = $akhir; //get from date todays
        $timestampB = strtotime($dateTimeB);
        $dateB      = date("Y-m-d", $timestampB);

        $dateYesterday = date('Y-m-d',(strtotime ( '-'.$this->getDiffDay($dateTimeA, $dateTimeB).' day' , strtotime ( $dateToday) ) ));

        $intimeToday = date('Y-m-d H:i:s', strtotime("$dateToday $intime"));
        $outtimeYesterday = date('Y-m-d H:i:s', strtotime("$dateYesterday $outtime"));

        $between_check = $this->check_hour_between($dateTimeB, $outtime, $intime);

        if($dateA == $dateB  || $between_check  ) 
        {
            $hasil = abs($timestampB - $timestampA) / (60 * 60); 
        }
        else
        {
            $diff = abs($timestampB - $timestampA) / (60 * 60); // perbedaan jam asli
            $offTime = $this->perkurangan($intimeToday, $outtimeYesterday); // waktu offtime mentah

            $offDay = $this->checkDay($dateA, $dateB); // waktu pendukung offtime, cek libur
            $hasil = ($diff - $offTime) + $offDay ; 
        }
        $hasil = number_format($hasil, 2, '.', '');

        $left = $sla - $hasil;
        $left = number_format($left, 2, '.', ''); 
        $left = $left * 60;
        $left = $this->convertToHoursMins($left, '%02d hours %02d minutes');

        $percent = $hasil / $sla * 100;
        $percent = number_format($percent, 2, '.', '');
        if($percent > 100) {$percent = 100;}

        $status = $this->countSLA($hasil, $sla);

        $hasil = $hasil * 60;
        $hasil = $this->convertToHoursMins($hasil, '%02d hours %02d minutes');

        $data = array('status' => $status, 'hasil' => $hasil, 'left' => $left, 'percent' => $percent);
        return $data;
    } 

    function convertToHoursMins($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
    
    public function check_hour_between($currentTime, $startTime, $endTime)
    {    
        $currentTime = strtotime($currentTime);
        $currentTime      = date("H:i", $currentTime);  

        if (
                (
                $startTime < $endTime &&
                $currentTime >= $startTime &&
                $currentTime <= $endTime
                ) ||
                (
                $startTime > $endTime && (
                $currentTime >= $startTime ||
                $currentTime <= $endTime
                )
                )
        ) 
        {
            return true; 
        } 
        else 
        {
            return false;
        }
    }
    public function perkurangan($tgl1, $tgl2)
    {
        $timestampA = strtotime($tgl1);
        $timestampB = strtotime($tgl2);
        return abs($timestampA - $timestampB) / (60 * 60);;
    }

    public function checkDay($date = null, $end_date = null)
    {
        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        $end_date = date ("Y-m-d", strtotime("-1 day", strtotime($end_date)));

        $offDay = array('2020-03-26');
        $count = 0;
        $countActual = 0;

        while (strtotime($date) <= strtotime($end_date)) 
        {   
            if(!in_array($date, $offDay))
            {
                $count++;
            } 
            

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        }  

        $hasil = ($count * 8); 
        return $hasil; 
    }

    public function getDiffDay($date, $end_date)
    { 
        $count = 0;
        while (strtotime($date) <= strtotime($end_date)) 
        {  
            $count++; 

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        } 

        return ($count - 1);
    }

    public function countSLA($workHour, $sla)
    {
        if($workHour <= $sla)
        {
            return 'passed';
        }
        else
        {
            return 'not passed';
        }
    }
    // Sla End
    
    public function GetDataMesin($id)
    {
        try {
            $mesin = MstMesin::where('dept_id', $id)->get();
            return response()->json($mesin);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
