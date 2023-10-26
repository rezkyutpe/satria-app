<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\Attendance;
use App\Models\MsMarital;
use App\Models\MsSubcont;
use App\Models\MsKlasifikasi;
use App\Models\MsStatusEmp;
use App\Models\View\VwUserDetail;
use App\Models\MstApps;
use App\Models\MstAppsMenu;
use App\Models\MstMenu;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\View\VwUserRoleGroup;
use App\Models\View\VwUserDetailAttendance;
use App\Exports\ExportSafetyHour;
use App\Exports\ExportAttendanceRatio;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Exception;
class UserManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $cekhead = User::where('email', Auth::user()->email)->whereRaw("title REGEXP 'Department Head| Division Head| Chief| Director| System Application'")->first();
            if (empty($cekhead)) {
                if ($this->PermissionMenu('user-management') == 0  && $this->PermissionMenu('user-dashboard') == 0 && $this->PermissionMenu('user-subcont') == 0 && $this->PermissionMenu('user-attendance-list') == 0){
                    return redirect('profile')->with('err_message', 'Akses Ditolak!');
                }else{ 
                return $next($request);
                }
            }else{
                    return redirect('dashboard-head');
            }
        });

    }

    public function UserMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->r==1){
                $paginate = 1500;
                if (isset($request->query()['search'])){
                    $search = $request->query()['search'];
                    $user = User::where('name', 'like', "%" . $search. "%")->orderBy('name', 'asc')->get();
                    $user->appends(['search' => $search]);
                } else {
                    $user = User::orderBy('name', 'asc')->simplePaginate($paginate);
                }
                $apps = MstApps::where('status', 1)->get();
                $no = 1;
                foreach($user as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'user' => $user,
                    'apps' => $apps,
                    'actionmenu' => $this->PermissionActionMenu('user-management'),
                );
                return view('user-management/index')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    
    public function UserMgmtSubcont(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-subcont')->r==1){
                // $paginate = 1500;
               
                $marital = MsMarital::orderBy('code', 'asc')->get();
                $klasifikasi = MsKlasifikasi::orderBy('name', 'asc')->get();
                $statusemp = MsStatusEmp::orderBy('name', 'asc')->get();
                $subcont = MsSubcont::orderBy('name', 'asc')->get();
                $status = MsStatusEmp::orderBy('name', 'asc')->get();
                $manager = $this->getUserSF('999');
                if($this->PermissionActionMenu('user-dashboard')->group==1){
                    $title = 'All';
                    $dept = Auth::user()->company_name;
                    $usersatria = User::where('role_id',10)->orderBy('email', 'asc')->get();
                    $user = VwUserDetail::orderBy('nrp', 'asc')->get();
                }else{
                    $title = 'Department';
                    $dept = Auth::user()->department;
                    $usersatria = User::where('dept',Auth::user()->dept)->where('role_id',10)->orderBy('email', 'asc')->get();
                    $user = VwUserDetail::where('dept',Auth::user()->dept)->orderBy('nrp', 'asc')->get();
                }
                
                $data = array(
                    'title' => $title,
                    'dept' => $dept,
                    'usersatria' => $usersatria,
                    'user' => $user,
                    'marital' => $marital,
                    'klasifikasi' => $klasifikasi,
                    'statusemp' => $statusemp,
                    'subcont' => $subcont,
                    'manager' => $manager,
                    'status' => $status,
                    'actionmenu' => $this->PermissionActionMenu('user-management'),
                );
                return view('user-management/user-subcont')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    
    public function UserMgmtDashboard(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-dashboard')->r==1){
                if($this->PermissionActionMenu('user-dashboard')->group==1){
                    $title = 'Admin Dashboard';
                    $dept = Auth::user()->company_name;
                    if (isset($request->query()['q'])){
                        $q = $request->query()['q'];
                        $attendance = VwUserDetailAttendance::where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfo = VwUserDetailAttendance::where('work_metode','WFO')->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfh = VwUserDetailAttendance::where('work_metode','WFH')->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                    } else {
                        $attendance = VwUserDetailAttendance::where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfo = VwUserDetailAttendance::where('work_metode','WFO')->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfh = VwUserDetailAttendance::where('work_metode','WFH')->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'asc')->get();
                    }
                    $userdetail= VwUserDetail::orderBy('end_date', 'asc')->get();
                }else{
                    $title = 'Department Dashboard';
                    $dept = Auth::user()->department;
                    if (isset($request->query()['q'])){
                        $q = $request->query()['q'];
                        $attendance = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfo = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('work_metode','WFO')->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfh = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('work_metode','WFH')->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                    } else {
                        $attendance = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfo = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('work_metode','WFO')->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'asc')->get();
                        $attendancewfh = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('work_metode','WFH')->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'asc')->get();
                    }
                    $userdetail= VwUserDetail::where('dept',Auth::user()->dept)->orderBy('end_date', 'asc')->get();
                }
                
                $data = array(
                    'title' => $title,
                    'dept' => $dept,
                    'attendance' => $attendance,
                    'attendancewfo' => $attendancewfo,
                    'attendancewfh' => $attendancewfh,
                    'userdetail' => $userdetail,
                );
                return view('user-management/user-dashboard')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    
    
    public function UserMgmtSubcontInsert(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('user-subcont')->c == 1) {
                if ($request->new_password != $request->re_password) {
                    return redirect()->back()->with('err_message', 'Re-Type Password Not Match!');
                } else {
                    $data = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'email_sf' => $request->email,
                        'phone' => $request->phone,
                        'departement' => $request->departement,
                        'section' => $request->section,
                        'division' => $request->division,
                        'department_code' => $request->department_code,
                        'section_code' => $request->section_code,
                        'division_code' => $request->division_code,
                        'company_name' => $request->company_name,
                        'company_id' => $request->company_id,
                        'worklocation_code' => $request->worklocation_code,
                        'worklocation_name' => $request->worklocation_name,
                        'worklocation_lat_long' => $request->worklocation_lat_long,
                        'title' => $request->title,
                        'password' => $request->new_password
                    ];
                    $result = $this->UserSubcontDirectCreate($data);
                    if (is_string($result) && strpos($result, 'Email telah digunakan! Gunakan alamat email yang belum terdaftar!') !== false) {
                        return redirect()->back()->with('err_message', $result);
                    } else {
                        $user = User::where('email', $request->email)->first();
                        if (!empty($user)) {
                            UserDetail::create([
                                'user_id' => $user->id,
                                'name' => $request->name,
                                'nrp' => $request->nrp,
                                'email' => $request->email,
                                'marital_status'=>$request->marital_status,
                                'birth_date'=>$request->birth_date,
                                'address'=>$request->address,
                                'plant'=>$request->plant,
                                'join_date'=>$request->join_date,
                                'end_date'=>$request->end_date,
                                'status'=>$request->status,
                                'klasifikasi'=>$request->klasifikasi,
                                'vendor'=>$request->vendor,
                                'password' => Hash::make($request->password),
                            ]);
                            return redirect()->back()->with('suc_message', 'Data baru berhasil ditambahkan!');
                        } else {
                            return redirect()->back()->with('err_message', 'Email tidak ditemukan setelah pembuatan akun!');
                        }
                    }
                }
            } else {
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
        }
    }

    public function UserMgmtSubcontUpdate(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('user-subcont')->u == 1) {
                $user = UserDetail::where('id', $request->id)->first();
                $email = UserDetail::where('email', $request->email)->first();
                if ($user->email == $request->email or empty($email)) {

                    UserDetail::where('id', $request->id)
                    ->update([
                            'name' => $request->name,
                            'nrp' => $request->nrp,
                            'email' => $request->email,
                            'marital_status'=>$request->marital_status,
                            'birth_date'=>$request->birth_date,
                            'address'=>$request->address,
                            'plant'=>$request->plant,
                            'join_date'=>$request->join_date,
                            'end_date'=>$request->end_date,
                            'status'=>$request->status,
                            'klasifikasi'=>$request->klasifikasi,
                            'vendor'=>$request->vendor
                        ]);
                    return redirect()->back()->with('suc_message', 'Data telah diperbarui');
                }else if(!empty($email)){
                    return redirect()->back()->with('err_message', 'Email telah digunakan! Gunakan alamat email yang belum terdaftar!');
                }else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
        }   
    }
    
    public function GetUserSfForDropdown($manager)
    {
        try{
            $result = $this->getUserSF($manager);
            return response()->json($result);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['error' => 'Error Request, Exception Error.'], 400);
        }
    }
    
    public function UserMgmtAttendanceList(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-attendance-list')->v==1){
                if($this->PermissionActionMenu('user-dashboard')->group==1){
                    $title = 'All';
                    $dept = Auth::user()->company_name;
                    $company = Auth::user()->company_name;
                    if (isset($request->query()['q'])){
                        $q = $request->query()['q'];
                        $attendance = VwUserDetailAttendance::where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                    } else {
                        $attendance= VwUserDetailAttendance::where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
                    }
                }else if($this->PermissionActionMenu('user-dashboard')->group==51){
                    $title = 'All';
                    $dept = Auth::user()->company_name;
                    $company = Auth::user()->company_name;
                    if (isset($request->query()['q'])){
                        $q = $request->query()['q'];
                        $attendance = VwUserDetailAttendance::where('vendor_name',Auth::user()->company_name)->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                    } else {
                        $attendance= VwUserDetailAttendance::where('vendor_name',Auth::user()->company_name)->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
                    }
                }else{
                    $title = 'Department';
                    $dept = Auth::user()->department;
                    $company = Auth::user()->company_name;
                    if (isset($request->query()['q'])){
                        $q = $request->query()['q'];
                        $attendance = VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . $q. "%")->orderBy('created_at', 'asc')->get();
                    } else {
                        $attendance= VwUserDetailAttendance::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
                    }
                }
                
                $data = array(
                    'title' => $title,
                    'dept' => $dept,
                    'company' => $company,
                    'attendance' => $attendance,
                    'actionmenu' => $this->PermissionActionMenu('user-management'),
                );
                // dd($data);
                return view('user-management/user-attendance-list')->with('data', $data);
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtDetail($id)
    {
        try{
            if($this->PermissionActionMenu('user-subcont')->v==1){
                $usersatria = User::where('id',$id)->first();
                $user = VwUserDetail::where('user_id',$id)->orderBy('nrp', 'asc')->first();
                $attendance= VwUserDetailAttendance::where('created_by',$id)->orderBy('created_at', 'desc')->get();
                $appsmenu = VwUserRoleGroup::where('user', $id)->get();
                $marital = MsMarital::orderBy('code', 'asc')->get();
                $klasifikasi = MsKlasifikasi::orderBy('name', 'asc')->get();
                $statusemp = MsStatusEmp::orderBy('name', 'asc')->get();
                $subcont = MsSubcont::orderBy('name', 'asc')->get();
                $status = MsStatusEmp::orderBy('name', 'asc')->get();
                $no = 1;
                foreach($appsmenu as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                'usersatria' => $usersatria,
                'user' => $user,
                'marital' => $marital,
                'klasifikasi' => $klasifikasi,
                'statusemp' => $statusemp,
                'subcont' => $subcont,
                'status' => $status,
                'appsmenu' => $appsmenu,
                'attendance' => $attendance,
                'appsmenu' => $appsmenu,
                'actionmenu' => $this->PermissionActionMenu('user-management'),
                );
                // dd($data);
                return view('user-management/user-detail')->with('data', $data);
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtView($id)
    {
        try{
            if($this->PermissionActionMenu('user-management')->v==1){
                $user = User::where('id',$id)->first();
                // $apps = MstApps::where('status', 1)->get();
                // $menu = MstMenu::where('flag', 1)->get();
                $appsmenu = VwUserRoleGroup::where('user', $id)->get();
                $no = 1;
                foreach($appsmenu as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                'user' => $user,
                // 'apps' => $apps,
                // 'menu' => $menu,
                'appsmenu' => $appsmenu,
                'actionmenu' => $this->PermissionActionMenu('user-management'),
                );
                return view('user-management/view')->with('data', $data);
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtViewSF($id)
    {
            
            $empData['data'] = $this->getUserSF($id);
  
            return response()->json($empData);
        
    }
    public function UserMgmtProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $data = array(
            'user' => $user,
        );
        return view('auth/profile')->with($data);
    }
    
    public function UserMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->c==1){
                $user = User::where('email', $request->new_email)->first();
                if ($request->new_password != $request->re_password) {
                    return redirect()->back()->with('err_message', 'Re-Type Password Not Match!');
                }else{
                    if(empty($user)){
                        User::create([
                            'name' => $request->full_name,
                            'email' => $request->new_email,
                            'role_id' => 10,
                            'is_blocked' => 1,
                            'phone'=>$request->phone,
                            'email_sf'=>$request->new_email, 
                            'email_verified_at' => date('Y-m-d H:i:s'),
                            'password' => Hash::make($request->new_password),
                            // 'token' => Str::random(60),
                        ]);
                        return redirect('user-management')->with('suc_message', 'Data baru berhasil ditambahkan!');
                    } else {
                        return redirect()->back()->with('err_message', 'Email telah digunakan! Gunakan alamat email yang belum terdaftar!');
                    }
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtInsertAppMenu(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->c==1){
                $user = User::where('email', $request->email)->first();
        
                if(!empty($user)){
                    MstAppsMenu::create([
                        'user' => $request->user,
                        'app' => $request->app,
                        'menu' =>  $request->menu,
                        'access' => 1,
                        'c' =>  $request->c,
                        'r' =>  $request->r,
                        'u' =>  $request->u,
                        'd' =>  $request->d,
                        'v' =>  $request->v,
                    ]);
                    
                    User::where('id', $request->user)
                    ->update([
                        'accessed_app' => $request->app
                        ]
                        );
                    return redirect('user-view/'.$request->user)->with('suc_message', 'Data baru berhasil ditambahkan!');
                } else {
                    return redirect()->back()->with('err_message', 'User Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }

    public function UserMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->u==1){
                $user = User::where('id', $request->id)->first();
                $email = User::where('email', $request->email)->first();
                if ($user->email == $request->email or empty($email)) {

                    User::where('id', $request->id)
                    ->update([
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'phone'=>$request->phone,
                        'email_sf'=>$request->email
                        ]
                        );
                    if(!empty($request->password)){
                        User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                    }
                    return redirect('user-management')->with('suc_message', 'Data telah diperbarui!');
                }else if(!empty($email)){
                    return redirect()->back()->with('err_message', 'Email telah digunakan! Gunakan alamat email yang belum terdaftar!');
                }else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtUpdateAppMenu(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->u==1){
                $user = User::where('email', $request->email)->first();
            
                if(!empty($user)){
                    MstAppsMenu::where('id', $request->id)
                    ->update([
                        'user' => $request->user,
                        'app' => $request->app,
                        'menu' =>  $request->menu,
                        'access' => 1,
                        'c' =>  $request->c,
                        'r' =>  $request->r,
                        'u' =>  $request->u,
                        'd' =>  $request->d,
                        'v' =>  $request->v,
                    ]);
                    return redirect('user-view/'.$request->user)->with('suc_message', 'Data baru berhasil ditambahkan!');
                } else {
                    return redirect()->back()->with('err_message', 'User Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtUpdateProfile(Request $request)
    {
        try{
            $user = User::where('id', $request->id)->first();
            if(!empty($user)){
                if($request->photo){
                    $file_extention = $request->photo->getClientOriginalExtension();
                    $file_name = $request->email.'image_profile.'.$file_extention;
                    $file_path = $request->photo->move($this->MapPublicPath().'pictures',$file_name);
                }else{
                $file_name=$user->picture;
                }
                User::where('id', $request->id)
                ->update([
                    'name' => $request->full_name,
                    'dev_web' => $request->website,
                    'dev_country_id' => $request->country,
                    'dev_address' => $request->address,
                    'picture' => $file_name,
                    'email' => $request->email
                    ]
                    );
                if(!empty($request->password)){
                    User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                }
                return redirect()->back()->with('suc_message', 'Data telah diperbarui!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtResetPass(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->u==1){
                $user = User::where('id', $request->id)->first();
                if(!empty($user)){
                    User::where('id', $request->id)
                    ->update([
                            'password' => Hash::make($request->password),
                        ]
                        );
                    if(!empty($request->password)){
                        User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                    }
                    return redirect()->back()->with('suc_message', 'Data telah diperbarui!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
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
            if ($request->new_password != $request->re_password) {
                return redirect()->back()->with('err_message', 'Re-Type Password Not Match!');
            }else{
                if(!empty($user)){
                    if(Hash::check($request->old_password, $user->password)){
                    User::where('id', Auth::user()->id)
                        ->update([
                            'password' => Hash::make($request->new_password),
                            ]
                        );
                    }else {
                        return redirect()->back()->with('err_message', 'Old Password Not Match!');
                    }
                    if(!empty($request->new_password)){
                        User::where('id', $request->id)->update(['password' => Hash::make($request->new_password)]);
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
    public function UserMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->d==1){
                $user = User::where('id', $request->id)->first();
                if(!empty($user)){
                    User::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtDeleteAppMenu(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->d==1){
                $appmenu = MstAppsMenu::where('id', $request->id)->first();
                if(!empty($appmenu)){
                    MstAppsMenu::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtBlock(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->d==1){
                $user = User::where('id', $request->id)->first();
                if(!empty($user)){
                    User::where('id', $request->id)
                    ->update([
                            'is_blocked' => 0,
                        ]
                        );
                    if(!empty($request->password)){
                        User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                    }
                    return redirect()->back()->with('suc_message', 'Data telah diblock!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function UserMgmtUnBlock(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-management')->d==1){
                $user = User::where('id', $request->id)->first();
                if(!empty($user)){
                    User::where('id', $request->id)
                    ->update([
                            'is_blocked' => 1,
                        ]
                        );
                    if(!empty($request->password)){
                        User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                    }
                    return redirect()->back()->with('suc_message', 'Data telah diunblock!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('user-management')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    
    public function UserMgmtSafetyHour(Request $request)
    {
        try {
            if($this->PermissionActionMenu('user-subcont')->v==1){
                $month = $request->month_search;
                $company = $request->company_search;
                $response = null;
                if(!empty($month) && !empty($company)){
                    $year = substr($month, 0, 4);
                    $month = substr($month, 5, 2);
                    $response = $this->getSafetyHour($year,$month,$company);
                }else{
                    $response = $this->getSafetyHour();
                }

                if (!empty($response) && $response['status'] == true) {
                    $bulanIndonesia = $response['tanggal'];
                    // Array untuk mengonversi nama bulan
                    $bulanMap = [
                        'Januari' => 'January',
                        'Februari' => 'February',
                        'Maret' => 'March',
                        'April' => 'April',
                        'Mei' => 'May',
                        'Juni' => 'June',
                        'Juli' => 'July',
                        'Agustus' => 'August',
                        'September' => 'September',
                        'Oktober' => 'October',
                        'November' => 'November',
                        'Desember' => 'December',
                    ];
                    list($bulan, $tahun) = explode(" ", $bulanIndonesia);
                    $bulanInggris = $bulanMap[$bulan];
                    $tanggalInggris = "$bulanInggris $tahun";
                    $data = [
                        'company' => $response['company'],
                        'tanggal' => $response['tanggal'],
                        'safety' => $response['safety'],
                        'tanggal_dropdown' => Carbon::createFromFormat('F Y', $tanggalInggris, 'Asia/Jakarta')->locale('en_US')->format('Y-m'),
                        'actionmenu' => $this->PermissionActionMenu('user-management'),
                    ];
                    return view('user-management/user-safety-hour')->with('data', $data);
                } else {
                    return redirect()->back()->with('err_message', 'Safety Hour Not Found');
                }
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function ExportSafetyHour(Request $request)
    {
        try {
            if($this->PermissionActionMenu('user-subcont')->v==1){
                $month = $request->month_export;
                $company = $request->company_export;
                $tanggal = $request->tanggal_export;
                $year = substr($month, 0, 4);
                $month = substr($month, 5, 2);
                $nama_file = 'SafetyHour' . " " . ucfirst($company) . " " . $tanggal . '.xlsx';
                return Excel::download(new ExportSafetyHour($year, $month, $company, $tanggal), $nama_file);
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function UserMgmtAttendanceRatio(Request $request)
    {
        try {
            if($this->PermissionActionMenu('user-subcont')->r==1){
                $month = $request->month_search;
                $company = $request->company_search;
                $departement = $request->departement_search;
                $response = null;
                if(!empty($month) && !empty($company) && !empty($departement)){
                    $year = substr($month, 0, 4);
                    $month = substr($month, 5, 2);
                    $response = $this->getAttendanceRatio($year,$month,$company,$departement);
                }else{
                    $response = $this->getAttendanceRatio();
                }

                if (!empty($response) && $response['status'] == true) {
                    $bulanIndonesia = $response['tanggal'];
                    // Array untuk mengonversi nama bulan
                    $bulanMap = [
                        'Januari' => 'January',
                        'Februari' => 'February',
                        'Maret' => 'March',
                        'April' => 'April',
                        'Mei' => 'May',
                        'Juni' => 'June',
                        'Juli' => 'July',
                        'Agustus' => 'August',
                        'September' => 'September',
                        'Oktober' => 'October',
                        'November' => 'November',
                        'Desember' => 'December',
                    ];
                    list($bulan, $tahun) = explode(" ", $bulanIndonesia);
                    $bulanInggris = $bulanMap[$bulan];
                    $tanggalInggris = "$bulanInggris $tahun";
                    if($response['mp'] > 0){
                        $totalMP = $response['mp'];
                        $totalHariKerja = $this->getTotalWeekdaysInMonth(Carbon::createFromFormat('F Y', $tanggalInggris, 'Asia/Jakarta')->locale('en_US')->format('Y-m'));
                        $totalMandays = $totalHariKerja * $totalMP;
                        $totalAbsentism = $totalMandays - array_sum(array_column($response['safety'], 'PRS'));
                        $attendanceRatio = ($totalAbsentism / $totalMandays) * 100;
                    }else{
                        $totalMP = 0;
                        $totalHariKerja = 0;
                        $totalMandays = 0;
                        $totalAbsentism = 0;
                        $attendanceRatio = 0;
                    }
                    $data = [
                        'company' => $response['company'],
                        'tanggal' => $response['tanggal'],
                        'safety' => $response['safety'],
                        'dept' => $response['dept'],
                        'departement' => $this->getDepartement($response['company']),
                        'tanggal_dropdown' => Carbon::createFromFormat('F Y', $tanggalInggris, 'Asia/Jakarta')->locale('en_US')->format('Y-m'),
                        'totalMP' => $totalMP,
                        'totalHariKerja' => $totalHariKerja,
                        'totalMandays' => $totalMandays,
                        'totalAbsentism' => $totalAbsentism,
                        'attendanceRatio' => $attendanceRatio,
                        'actionmenu' => $this->PermissionActionMenu('user-management'),
                    ];
                    return view('user-management/user-attendance-ratio')->with('data', $data);
                } else {
                    return redirect()->back()->with('err_message', 'Attendance Ratio Not Found');
                }
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function GetDepartementForDropdown($company)
    {
        $departements = $this->getDepartement($company);
        return response()->json($departements);
    }

    public function ExportAttendanceRatio(Request $request)
    {
        try {
            if($this->PermissionActionMenu('user-subcont')->r==1){
                $month = $request->month_export;
                $company = $request->company_export;
                $tanggal = $request->tanggal_export;
                $departement = $request->departement_export;
                $nameDepartement = $request->name_departement_export;
                $year = substr($month, 0, 4);
                $month = substr($month, 5, 2);
                $nama_file = 'Attendance Ratio' . " (" . $nameDepartement . ") " . $tanggal . '.xlsx';
                return Excel::download(new ExportAttendanceRatio($year, $month, $company, $tanggal, $departement, $nameDepartement), $nama_file);
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
