<?php

namespace App\Http\Controllers\Cms\PoTracking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Table\PoTracking\SubcontDevUserRole;
use App\Models\Table\PoTracking\SubcontDevVendor;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\SubcontDevRoles;
use App\Models\Table\PoTracking\RolesType;
use App\Models\Table\PoTracking\Vendors;
use App\Models\Table\PoTracking\VendorsSubcont;
use App\Models\Table\PoTracking\DelayReason;
use App\Models\Table\PoTracking\SubcontLeadtimeMaster;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\ReasonSubCont;
use App\Models\View\PoTracking\VwPosubcont;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\UserProcurementSuperior;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwPoLocalOngoing;
use Yajra\Datatables\Datatables;
use DB;
use Model;
use Illuminate\Support\Carbon;
use App\Models\User;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Storage;
use Exception;
use App\Models\Table\PoTracking\ParameterSla;
use App\Models\Table\PoTracking\MigrationProcurementPO;
use App\Models\Table\PoTracking\DisabledDays;


use App\Exports\ExportSubcontVendors;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PoTrackingMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('potracking') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }
      //Loghistory
    public function LogHistory()
      {
        if($this->PermissionActionMenu('loghistory')->r==1 ){
            $data = UserVendor::all();
           foreach($data as $item){
                $vendor[] = $item->VendorCode ;
            }

            $datainternal = LogHistory::whereNotIn('user', $vendor)->orderBy('id', 'desc')->paginate(3000);

            foreach($datainternal as $a){
                if(strpos($a->user , 'PROC-S') !== false){
                    $val1 = MigrationProcurementPO::where('procurement',$a->user)->select('name')->first();
                    $data_user = User::where('name','like','%'.$val1->name.'%')->first();
                    if(isset($data_user)){
                        $a->setAttribute('CreatedBy',$data_user->name);
                        $a->setAttribute('userlogintype',$data_user->title);
                    }
                }
            }
            $dataexternal = LogHistory::whereIn('user', $vendor)->orderBy('id', 'desc')->paginate(3000);


            $datainternalactive = LogHistory::whereNotIn('user', $vendor)->whereDate('created_at', Carbon::today())->groupBy('user')->get()->count();
            $datainternalmonth = LogHistory::whereNotIn('user', $vendor)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->groupBy('user')->get()->count();
            $datalocal = UserVendor::where('VendorType','Vendor Local')->get();
            foreach($datalocal as $item1){
                $vendorlocal[] = $item1->VendorCode ;
            }
            $datalocalactive = LogHistory::whereIn('user', $vendorlocal)->whereDate('created_at', Carbon::today())->groupBy('user')->get()->count();
            $datalocalmonth = LogHistory::whereIn('user', $vendorlocal)->whereYear('created_at', Carbon::now()->year)->groupBy('user')->whereMonth('created_at', Carbon::now()->month)->get()->count();
            $datasubcont = UserVendor::where('VendorType','Vendor SubCont')->get();;
            foreach($datasubcont as $item2){
                $vendorsubcont[] = $item2->VendorCode ;
            }
            $datasubcontactive = LogHistory::whereIn('user', $vendorsubcont)->whereDate('created_at', Carbon::today())->groupBy('user')->get()->count();
            $datasubcontmonth = LogHistory::whereIn('user', $vendorsubcont)->whereYear('created_at', Carbon::now()->year)->groupBy('user')->whereMonth('created_at', Carbon::now()->month)->get()->count();

            return view('po-tracking/master/LogHistory',compact('datainternal','dataexternal','datainternalactive','datainternalmonth','datalocalactive','datalocalmonth','datasubcontactive','datasubcontmonth'));

        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
      }
      public function searchpoall(Request $request)
      {

        if($request->ajax()){
            return DataTables::of(VwPo::get())->toJson();
        }else{
            return DataTables::of(Vwpo::all())->toJson();
            // return response()->json([
            //     'draw' => 0,
            //     'recordsTotal' => count(Pir::all()),
            //     'recordsFiltered' => count(Pir::all()),
            //     'data' => Pir::all(),
            // ], 200);
        }
    }
    //Checkpo
    public function checkpo()
    {
        if($this->PermissionActionMenu('checkpo')->r==1){
        $header_title           = "Check All Po";
        $link_search                    = "searchcheckpo";
        $link_reset                     = "checkpo";
        $actionmenu =  $this->PermissionActionMenu('checkpo');
        return view('po-tracking/master/CheckPo',
        compact(

            'header_title',
            'link_search',
            'link_reset',
            'actionmenu'

        ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function cariRolesType(Request $request)
            {
            $data = RolesType::where('ID', $request->id)->first();
            echo json_encode($data);
            }
    public function RolesTypeInsert(Request $request)
    {

            $appsmenu = RolesType::where('Name', $request->name)->first();
            if(empty($appsmenu)){
            $create = RolesType::create([
                'Name'=>$request->name,

            ]);
                if($create){
                    return redirect('reasonsubcont')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }


    //Subcontdevuserrules
    public function Subcontdevuserroles()
    {
        if($this->PermissionActionMenu('subcontdevuserroles')->c==1 || $this->PermissionActionMenu('subcontdevuserroles')->r==1 || $this->PermissionActionMenu('subcontdevuserroles')->v==1 || $this->PermissionActionMenu('subcontdevuserroles')->u==1){
        $data = SubcontDevUserRole::all();
        $dataRole = SubcontDevRoles::all();
        return view('po-tracking/master/subcontDevUserRole', compact('dataRole', 'data'));
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }
    }
    public function cariSubcontdevuserroles(Request $request)
    {
    $data = SubcontDevUserRole::where('ID', $request->id)->first();
    echo json_encode($data);
    }
    public function SubcontdevuserrolesInsert(Request $request)
    {


            $appsmenu = SubcontDevUserRole::where('Username', $request->Username)->first();
            if(empty($appsmenu)){
            $create = SubcontDevUserRole::create([
                'Username'=>$request->Username,
                'RoleID'=>$request->RoleID,
                'IsHead'=>$request->IsHead,
                'CreatedBy'=>Auth::user()->name,
            ]);
                if($create){
                    return redirect('subcontdevuserroles')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }

    //SubcontDevVendor
    public function SubcontDevVendor()
    {
        if($this->PermissionActionMenu('subcontdevvendor')->c==1 || $this->PermissionActionMenu('subcontdevvendor')->r==1 || $this->PermissionActionMenu('subcontdevvendor')->v==1 || $this->PermissionActionMenu('subcontdevvendor')->u==1){
        $user = User::all();
        $vendor = Vendors::all();
        $subcontvendor1 = SubcontDevVendor::select('*')->groupBy('Username')->get();
        $subcontvendor = SubcontDevVendor::all();
        $data =  Vendors::all();

        return view('po-tracking/master/subcontDevVendor', compact('data','subcontvendor', 'subcontvendor1', 'user', 'vendor'));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function deleteSubcontDevVendor(Request $request)
            {
            $data = SubcontDevVendor::where('ID', $request->id)->first();

            echo json_encode($data);
            }

    public function cariSubcontDevVendor(Request $request)
            {
            $data = SubcontDevVendor::where('ID', $request->id)->first();
            $Code = explode(', ', $data->VendorCode);
            $dataVendors = Vendors::whereIn('Code', $Code)->get();
            echo json_encode($dataVendors);
            }
    public function SubcontDevVendorInsert(Request $request)
    {


            $appsmenu = SubcontDevVendor::where('Username', $request->Username)->first();

            if(empty($appsmenu)){

            foreach ($request->Vendorcode as $key ) {

                $insert = [
                    ['Username'=> $request->Username,'VendorCode'=> $key],
                ];
                SubcontDevVendor::insert($insert);
               }


          // Eloquent approach
                  if($insert){
                    return redirect('subcontdevvendor')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }
    public function SubcontDevVendorDelete(Request $request)
    {

            $subcont = SubcontDevVendor::where('ID', $request->id)->first();
            if(!empty($subcont)){
                SubcontDevVendor::where('ID', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

    }
    //UservendorDelete
    public function UservendorDelete(Request $request)
    {

            $vendor = UserVendor::where('ID', $request->ID)->first();

            if(!empty($vendor)){
                UserVendor::where('ID', $request->ID)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

    }
    //UserVendor
    public function UserVendor()
    {
        if($this->PermissionActionMenu('uservendor')->c==1 || $this->PermissionActionMenu('uservendor')->r==1 || $this->PermissionActionMenu('uservendor')->v==1 || $this->PermissionActionMenu('uservendor')->u==1){
        $data = UserVendor::all();
        $CountryCode  = UserVendor::select('CountryCode')
        ->distinct()->get();
        $VendorType  = UserVendor::select('VendorType')
        ->distinct()->get();

        return view('po-tracking/master/UserVendor', compact('data','VendorType','CountryCode'));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function cariUserVendor(Request $request)
            {
            $data = UserVendor::where('ID', $request->id)->first();

            echo json_encode($data);
            }
    public function UserVendorInsert(Request $request)
    {


            $appsmenu = UserVendor::where('Name', $request->Name)->first();

            if(empty($appsmenu)){
            $create = UserVendor::create([
                'Name'=>$request->Name,
                'VendorCode'=>$request->VendorCode,
                'VendorCode_new'=>$request->VendorCode_new,
                'PhoneNo'=>$request->PhoneNo,
                'VendorType'=>$request->VendorType,
                'Email'=>$request->Email,
                'CountryCode'=>$request->CountryCode,
                'PostalCode'=>$request->PostalCode,
                'Address'=>$request->Address,

            ]);

            $password = 'password';

            $this->UserDirectCreate($request->Name,$request->VendorCode,$request->Email,$request->PhoneNo,$password);
                if($create){
                    return redirect('uservendor')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }
    //UservendorUpdate
    public function UservendorUpdate (Request $request)
    {

        $update = UserVendor::where('ID', $request->ID)->first();

        if(!empty($update)){
            $update = UserVendor::where('ID', $request->ID)
            ->update([
                'Name'=>$request->Name,
                'VendorCode'=>$request->VendorCode,
                'VendorCode_new'=>$request->VendorCode_new,
                'PhoneNo'=>$request->PhoneNo,
                'VendorType'=>$request->VendorType,
                'Email'=>$request->Email,
                'CountryCode'=>$request->CountryCode,
                'PostalCode'=>$request->PostalCode,
                'Address'=>$request->Address,

            ]);
            if($update){
                return redirect('uservendor')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }

    }
    //MaintainVendorsSubcont
    public function maintainvendorsubcont()
    {
        if($this->PermissionActionMenu('maintainvendorsubcont')->c==1 || $this->PermissionActionMenu('maintainvendorsubcont')->r==1 || $this->PermissionActionMenu('maintainvendorsubcont')->v==1 || $this->PermissionActionMenu('maintainvendorsubcont')->u==1){

        $data = VwPosubcont::get();

        return view('po-tracking/master/Maintainvendorsubcont', compact('data'));
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }

    }
    public function exportmaintainvendorsubcont()
        {
            return Excel::download(new ExportSubcontVendors(), 'PoTrackingFile.xlsx');
        }

    public function maintainvendorsubcontImport(Request $request)
        {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);

            // menangkap file excel
            $file = $request->file('file');

            // membuat nama file unik
            $nama_file = rand().$file->getClientOriginalName();

            // upload ke folder file_siswa di dalam folder public
            $file->move('file_siswa',$nama_file);

            // import data
            $create = Excel::import(new VwPosubcont, public_path('/file_siswa/'.$nama_file));


            // alihkan halaman kembali
            if($create){
                return redirect('maintainvendorsubcont')->with('suc_message', 'Data berhasil ditambahkan!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
            }


    }

    public function MaintainVendorsubcontInsert(Request $request)
    {

            $dailicapacity = $request->MonthlyCapacity / 22 ;
            $appsmenu = VendorsSubcont::where('VendorCode', $request->VendorCode)->where('Material', $request->Material)->first();
            if(empty($appsmenu)){
            $create = VendorsSubcont::create([
                'VendorCode'=>$request->VendorCode,
                'Material'=>$request->Material,
                'Description'=>$request->Description,
                'DailyLeadTime'=>$request->DailyLeadTime,
                'MonthlyLeadTime'=>$request->MonthlyLeadTime,
                'PB'=>$request->PB,
                'Setting'=>$request->Setting,
                'Fullweld'=>$request->Fullweld,
                'Primer'=>$request->Primer,
                'isNeedSequence'=>1,
                'MonthlyCapacity'=>$request->MonthlyCapacity,
                'DailyCapacity'=>$dailicapacity,
                'CreatedBy'=>Auth::user()->name,
                'LastModifiedBy'=>Auth::user()->name,
            ]);
                if($create){
                    return redirect('maintainvendorsubcont')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }

    public function cariVwposubcont(Request $request)
    {

    $data = VwPosubcont::where('ID', $request->id)->first();
    echo json_encode($data);

    }

    //VendorsSubcont
    public function VendorsSubcont()
    {
        if($this->PermissionActionMenu('vendorssubcont')->c==1 || $this->PermissionActionMenu('vendorssubcont')->r==1 || $this->PermissionActionMenu('vendorssubcont')->v==1 || $this->PermissionActionMenu('vendorssubcont')->u==1){

        $vendor = Vendors::all();
        $data = VendorsSubcont::all();
        return view('po-tracking/master/VendorsSubcont', compact('data', 'vendor'));
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }
    }
    public function cariVendorsSubcont(Request $request)
    {
    $data = VendorsSubcont::where('ID', $request->id)->first();
    echo json_encode($data);
    }
    public function VendorsSubcontInsert(Request $request)
    {

            $dailicapacity = $request->MonthlyCapacity / 22 ;
            $appsmenu = VendorsSubcont::where('VendorCode', $request->VendorCode)->where('Material', $request->Material)->first();
            if(empty($appsmenu)){
            $create = VendorsSubcont::create([
                'VendorCode'=>$request->VendorCode,
                'Material'=>$request->Material,
                'Description'=>$request->Description,
                'DailyLeadTime'=>$request->DailyLeadTime,
                'MonthlyLeadTime'=>$request->MonthlyLeadTime,
                'PB'=>$request->PB,
                'Setting'=>$request->Setting,
                'Fullweld'=>$request->Fullweld,
                'Primer'=>$request->Primer,
                'isNeedSequence'=>1,
                'MonthlyCapacity'=>$request->MonthlyCapacity,
                'DailyCapacity'=>$dailicapacity,
                'CreatedBy'=>Auth::user()->name,
                'LastModifiedBy'=>Auth::user()->name,
            ]);
                if($create){
                    return redirect('vendorssubcont')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }
    public function VendorsSubcontUpdate(Request $request)
    {

        $update = VendorsSubcont::where('ID', $request->id)->first();
        if(!empty($update)){
            $update = VendorsSubcont::where('ID', $request->id)
            ->update([
                'Material'=>$request->Material,
                'Description'=>$request->Description,
                'DailyLeadTime'=>$request->DailyLeadTime,
                'MonthlyLeadTime'=>$request->MonthlyLeadTime,
                'PB'=>$request->PB,
                'Setting'=>$request->Setting,
                'Fullweld'=>$request->Fullweld,
                'Primer'=>$request->Primer,
                'isNeedSequence'=>1,
                'MonthlyCapacity'=>$request->MonthlyCapacity,
                'DailyCapacity'=>$request->DailyCapacity,

            ]);
            if($update){
                return redirect('vendorssubcont')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }

    }
    public function VendorsSubcontDelete(Request $request)
    {

            $deletereason = VendorsSubcont::where('ID', $request->ID)->first();
            if(!empty($deletereason)){
                VendorsSubcont::where('ID', $request->ID)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

    }

     //Vendors
    public function Vendors()
    {
        if($this->PermissionActionMenu('vendors')->c==1 || $this->PermissionActionMenu('vendors')->r==1 || $this->PermissionActionMenu('vendors')->v==1 || $this->PermissionActionMenu('vendors')->u==1){

        $data = Vendors::all();
        return view('po-tracking/master/Vendors')->with('data', $data);
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }
    }
    public function cariVendors(Request $request)
            {
            $data = Vendors::where('Code', $request->id)->first();

            echo json_encode($data);
            }
    public function VendorsInsert(Request $request)
    {


            $appsmenu = Vendors::where('Code', $request->code)->first();
            if(empty($appsmenu)){
            $create = Vendors::create([
                'Code'=>$request->code,
                'Name'=>$request->name,
                'City'=>$request->city,
                'PostalCode'=>$request->postalcode,
                'Region'=>$request->region,
                'Street'=>$request->street,
                'Address'=>$request->address,
                'AccountGroup'=>$request->accountgroup,
                'Telephone1'=>$request->telephone1,
                'FaxNumber'=>$request->faxnumber,

            ]);
                if($create){
                    return redirect('vendors')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }
    public function VendorsUpdate(Request $request)
    {

        $update = Vendors::where('Code', $request->code)->first();
        if(!empty($update)){
            $update = Vendors::where('Code', $request->code)
            ->update([
                'City'=>$request->city,
                'PostalCode'=>$request->postalcode,
                'Region'=>$request->region,
                'Street'=>$request->street,
                'Address'=>$request->address,
                'AccountGroup'=>$request->accountgroup,
                'Telephone1'=>$request->telephone1,
                'FaxNumber'=>$request->faxnumber,

            ]);
            if($update){
                return redirect('vendors')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }

    }
    public function VendorsDelete(Request $request)
    {

            $deletereason = Vendors::where('Code', $request->Code)->first();
            if(!empty($deletereason)){
                Vendors::where('Code', $request->Code)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

    }

     //DelayReason
     public function DelayReason()
     {
        if($this->PermissionActionMenu('delayreason')->c==1 || $this->PermissionActionMenu('delayreason')->r==1 || $this->PermissionActionMenu('delayreason')->v==1 || $this->PermissionActionMenu('delayreason')->u==1){

         $data = DelayReason::all();
         return view('po-tracking/master/Delayreason')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
     }
     public function cariDelayreason(Request $request)
            {
            $data = DelayReason::where('ID', $request->id)->first();
            echo json_encode($data);
            }

     public function delayReasonInsert(Request $request)
     {


             $appsmenu = DelayReason::where('Name', $request->name)->first();
             if(empty($appsmenu)){
             $create = DelayReason::create([
                 'Name'=>$request->Name,

             ]);
                 if($create){
                     return redirect('delayreason')->with('suc_message', 'Data berhasil ditambahkan!');
                 }else{
                     return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                 }
             }else{
             return redirect()->back()->with('err_message', 'Menu link Already Exist!');
             }

     }
     public function delayreasonUpdate(Request $request)
    {



        $update = DelayReason::where('ID', $request->ID)->first();
        if(!empty($update)){
            $update = DelayReason::where('ID', $request->ID)
            ->update([
                'Name'=>$request->Name,

            ]);
            if($update){
                return redirect('delayreason')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }

    }
    public function delayReasonDelete(Request $request)
    {

            $deletereason = DelayReason::where('ID', $request->ID)->first();
            if(!empty($deletereason)){
                DelayReason::where('ID', $request->ID)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

    }

     //ReasonSubCont
     public function ReasonSubCont(Request $request)
     {
        if($this->PermissionActionMenu('reasonsubcont')->c==1 || $this->PermissionActionMenu('reasonsubcont')->r==1 || $this->PermissionActionMenu('reasonsubcont')->v==1 || $this->PermissionActionMenu('reasonsubcont')->u==1){


        $data = ReasonSubCont::all();

        return view('po-tracking/master/ReasonSubCont')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
     }

     public function carireason(Request $request)
            {
            $data = ReasonSubCont::where('ID', $request->id)->first();
            echo json_encode($data);
            }

     public function reasonsubcontInsert(Request $request)
    {


            $appsmenu = ReasonSubCont::where('Name', $request->name)->first();
            if(empty($appsmenu)){
            $create = ReasonSubCont::create([
                'Name'=>$request->name,

            ]);
                if($create){
                    return redirect('reasonsubcont')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Menu link Already Exist!');
            }

    }
    public function reasonsubcontUpdate(Request $request)
    {

        $update = ReasonSubCont::where('ID', $request->ID)->first();
        if(!empty($update)){
            $update = ReasonSubCont::where('ID', $request->ID)
            ->update([
                'Name'=>$request->Name,

            ]);
            if($update){
                return redirect('reasonsubcont')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }

    }

    public function reasonsubcontDelete(Request $request)
    {

            $deletereason = ReasonSubCont::where('ID', $request->ID)->first();
            if(!empty($deletereason)){
                ReasonSubCont::where('ID', $request->ID)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

    }

    //userprocurementsuperior

    public function Userprocurementsuperior(Request $request)
    {
        if($this->PermissionActionMenu('userprocurementsuperior')->c==1 || $this->PermissionActionMenu('userprocurementsuperior')->r==1 || $this->PermissionActionMenu('userprocurementsuperior')->v==1 || $this->PermissionActionMenu('userprocurementsuperior')->u==1){

       $data = UserProcurementSuperior::all();

       return view('po-tracking/master/userprocurementsuperior')->with('data', $data);
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }
    }

    public function cariuserprocurementsuperior(Request $request)
           {
           $data = UserProcurementSuperior::where('ID', $request->id)->first();
           echo json_encode($data);
           }

    public function userprocurementsuperiorInsert(Request $request)
   {


           $appsmenu = UserProcurementSuperior::where('Username', $request->Username)->first();
           if(empty($appsmenu)){
           $create = UserProcurementSuperior::create([
               'ParentID'=>$request->ParentID,
               'Username'=>$request->Username,
               'FullName'=>$request->FullName,
               'NRP'=>$request->NRP,
               'Email'=>$request->Email,

           ]);
               if($create){
                   return redirect('userprocurementsuperior')->with('suc_message', 'Data berhasil ditambahkan!');
               }else{
                   return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
               }
           }else{
           return redirect()->back()->with('err_message', 'Menu link Already Exist!');
           }

   }
   public function userprocurementsuperiorUpdate(Request $request)
   {

       $update = UserProcurementSuperior::where('ID', $request->id)->first();

       if(!empty($update)){
           $update = UserProcurementSuperior::where('ID', $request->id)
           ->update([
            'Username'=>$request->Username,
            'FullName'=>$request->FullName,
            'NRP'=>$request->NRP,
            'Email'=>$request->Email,

           ]);
           if($update){
               return redirect('userprocurementsuperior')->with('suc_message', 'Data berhasil diupdate!');
           }else{
               return redirect()->back()->with('err_message', 'Data gagal diupdate!');
           }
       }else{
           return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
       }

   }

   public function userprocurementsuperiorDelete(Request $request)
   {

           $deletereason = UserProcurementSuperior::where('ID', $request->id)->first();
           if(!empty($deletereason)){
            UserProcurementSuperior::where('ID', $request->id)->delete();
               return redirect()->back()->with('suc_message', 'Data telah dihapus!');
           } else {
               return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
           }

   }

   //Subcont Leadtime Master
   public function Subcontleadtimemaster()
   {
    if($this->PermissionActionMenu('subcontleadtimemaster')->c==1 || $this->PermissionActionMenu('subcontleadtimemaster')->r==1 || $this->PermissionActionMenu('subcontleadtimemaster')->v==1 || $this->PermissionActionMenu('subcontleadtimemaster')->u==1){

       $data = SubcontLeadtimeMaster::all();
       return view('po-tracking/master/subcontleadtimemaster')->with('data', $data);
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }
   }

   public function cariSubcontleadtimemaster(Request $request)
   {
        $data = SubcontLeadtimeMaster::where('ID', $request->id)->first();
        echo json_encode($data);
   }

   public function SubcontleadtimemasterInsert(Request $request)
   {
        $appsmenu = SubcontLeadtimeMaster::where('LeadtimeName', $request->name)->first();
        if(empty($appsmenu)){
        $create = SubcontLeadtimeMaster::create([
            'LeadtimeName'=>$request->name,
            'Value'=>$request->value / 100,
        ]);
            if($create){
                return redirect('subcontleadtimemaster')->with('suc_message', 'Data berhasil ditambahkan!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
            }
        }else{
        return redirect()->back()->with('err_message', 'Menu link Already Exist!');
        }
   }

   public function SubcontleadtimemasterUpdate(Request $request)
    {
        $update = SubcontLeadtimeMaster::where('ID', $request->ID)->first();
        if(!empty($update)){
            $update = SubcontLeadtimeMaster::where('ID', $request->ID)
            ->update([
                'LeadtimeName'=>$request->Name,
                'Value'=>$request->Value / 100,
            ]);
            if($update){
                return redirect('subcontleadtimemaster')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }

    public function SubcontleadtimemasterDelete(Request $request)
    {
        $delete = SubcontLeadtimeMaster::where('ID', $request->ID)->first();
        if(!empty($delete)){
            SubcontLeadtimeMaster::where('ID', $request->ID)->delete();
            return redirect()->back()->with('suc_message', 'Data telah dihapus!');
        } else {
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }

    //ParameterSla
    public function ParameterSla()
    {
        if($this->PermissionActionMenu('parametersla')->c==1 || $this->PermissionActionMenu('parametersla')->r==1 || $this->PermissionActionMenu('parametersla')->v==1 || $this->PermissionActionMenu('parametersla')->u==1){
            $data = ParameterSla::all();
            return view('po-tracking/master/ParameterSla')->with('data', $data);
        }
        else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function cariparametersla(Request $request)
    {
        $data = ParameterSla::where('id', $request->id)->first();
        echo json_encode($data);
    }


    public function ParameterSlaUpdate(Request $request)
    {
        $update = ParameterSla::where('id', $request->id)->first();
        if(!empty($update)){
            if($request->action == "editpopr"){
                $update = ParameterSla::where('id', $request->id)
                ->update([
                    'prcreatetoprrel'=>$request->prcreatetoprrel,
                    'prtopocreate'=>$request->prtopocreate,
                    'pocreatetoporel'=>$request->pocreatetoporel,
                    'updated_by'=>Auth::user()->name
                ]);
            }
            else if($request->action == "edittickethour"){
                $update = ParameterSla::where('id', $request->id)
                ->update([
                    'ticket_hour'=>$request->tickethour,
                    'updated_by'=>Auth::user()->name
                ]);
            }
            else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }

            if($update){
               return redirect('parametersla')->with('suc_message', 'Data berhasil diupdate!');
            }else{
                return redirect()->back()->with('err_message', 'Data gagal diupdate!');
            }
        }
        else{
           return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }

    /*Disable days Full Calendar*/
    public function view_date_fullcalendar()
    {
        $get_data = DisabledDays::where('is_active',1)->where('is_disabled',1)->get();
        if(count($get_data) > 0){
            foreach($get_data as $item){
                $date[] = $item->event_date;
            }
        }
        else{
            $date = '';
        }

        echo json_encode($date);
    }

    public function update_date_fullcalendar(Request $request)
    {
        try{
            if($request != null){
                $request_data = $request->date_disabled;
    
                //enabling the date that has been disabled
                $get_data = DisabledDays::where('is_active',1)->where('is_disabled',1)->get();
                if(count($get_data) > 0){
                    foreach($get_data as $data){
                        $date_record[] = $data->event_date;
                    }
                }
                if($request_data != null && count($get_data) > 0){
                    $enable_date = array_diff($date_record,$request_data);
                    foreach($enable_date as $item2){
                        DisabledDays::where('event_date',$item2)->update(['is_disabled'=>0,'updated_by'=>Auth::user()->name]);
                    }
                }
                else{
                    DisabledDays::where('is_disabled',1)->update(['is_disabled'=>0,'updated_by'=>Auth::user()->name]);
                }
    
                //data from application
                if(!is_null($request_data)){
                    foreach($request_data as $item){
                        $date = DisabledDays::where('event_date', $item)->first();
                        if (!is_null($date)) {
                            DisabledDays::where('event_date', $item)->update(['is_disabled'=> 1,'is_active'=>1,'updated_by'=>Auth::user()->name]);
                        }else{
                            $years = substr($item, 0, 4);
                            DisabledDays::create([
                                'event_date'    => $item,
                                'event_years'   => $years,
                                'is_disabled'   => 1,
                                'is_active'     => 1,
                                'created_by'    => Auth::user()->name
                            ]);
                        }
                    }
                }
                
    
                return redirect()->back()->with('suc_message', 'Calendar completely saved!');
            }else{
                DisabledDays::where('is_disabled',1)->update(['is_disabled'=>0,'updated_by'=>Auth::user()->name]);
                return redirect()->back()->with('suc_message', 'Calendar completely saved!');
            }
        }catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }
    /*END of Disable days Full Calendar*/


}
