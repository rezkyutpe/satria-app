<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsKendaraan;
use App\Models\Table\Qrgad\MsSupir;
use App\Models\Table\Qrgad\MsToken;
use App\Models\Table\Qrgad\TbTrip;
use App\Models\Table\Qrgad\TbTripHistori;
use App\Models\Table\Qrgad\TbTripRequest;
use App\Models\Table\Qrgad\TbTripVoucher;
use App\Models\User;
use App\Models\View\Qrgad\VwTrip;
use App\Models\View\Qrgad\VwTripRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TripController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if($this->PermissionActionMenu('trip')->r==1){

            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
            ];

            return view('Qrgad/trip/index' , [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function indexAdmin()
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->r==1){

            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
            ];
            
            return view('Qrgad/trip/indexAdmin' , [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip-dashboard')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function schedule()
    {
        try{
            if($this->PermissionActionMenu('trip-schedule')->r==1){

            $breadcrumb = [
                [
                    'nama' => "Jadwal TMS",
                    'url' => "/trip-schedule"
                ],
            ];
            
            return view('Qrgad/trip/schedule' , [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip-schedule')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function read(Request $request)
    {
        try{
            if($this->PermissionActionMenu('trip')->r==1){
      
            $trip_request = VwTrip::all()->where('email', Auth::user()->email);
            
            if($request->awal != '' && $request->akhir != ''){
                $trip_request = $trip_request->whereBetween('waktu_berangkat',[$request->awal,$request->akhir])->whereBetween('waktu_pulang',[$request->awal,$request->akhir]);
            } 
            
            return view('Qrgad/trip/read' , [
                "trip_request" => $trip_request,
            ]);


            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function readAdmin(Request $request)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->r==1){
            

            $trip_request = VwTrip::orderBy('id_trip_request', 'DESC')->get();

            if($request->awal != '' && $request->akhir != ''){
                $trip_request = $trip_request->whereBetween('waktu_berangkat',[$request->awal,$request->akhir])->whereBetween('waktu_pulang',[$request->awal,$request->akhir]);
            } 
            
            return view('Qrgad/trip/readAdmin', [
                "trip_request" => $trip_request,
            ]);


            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function readSchedule(Request $request)
    {
        try{
            if($this->PermissionActionMenu('trip-schedule')->r==1){

            $trip = VwTrip::where('status', 3 )->orWhere('status', 4 )->whereNotNull('kendaraan')->orderBy('departure_time', 'DESC')->get();

            if($request->awal != '' && $request->akhir != ''){
                $trip = $trip->whereBetween('waktu_berangkat',[$request->awal,$request->akhir])->orWhereBetween('waktu_berangkat_aktual',[$request->awal,$request->akhir])->get();
            }
            
            return view('Qrgad/trip/readSchedule' , [
                "trip" => $trip,
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function tripFilter(Request $request)
    {
        try{
            if($this->PermissionActionMenu('trip-check')->r==1){
            
            $trip = VwTrip::all()->whereNotNull('id_trip')->whereNotNull('set_trip_time')
            ->whereNotNull('kendaraan');
            
            if($request->awal != '' && $request->akhir != ''){
                $trip = $trip->whereBetween('waktu_berangkat',[$request->awal,$request->akhir])
                ->whereBetween('waktu_pulang',[$request->awal,$request->akhir]);
            } 

            foreach($trip as $t){
                echo "<option value=".$t->id_trip.">".$t->id_trip." | ".$t->kendaraan." | ".$t->nopol."</option>";
            }

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if($this->PermissionActionMenu('trip')->c==1){

            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
                [
                    'nama' => "Form TMS",
                    'url' => "/trip/create"
                ],
            ];
            
            return view('Qrgad/trip/formTms', [
                "tanggal" => Carbon::now(),
                "penumpangs" => User::getUserSF(999), //ganti API sunfish
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function pickCar($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){

            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
                [
                    'nama' => "Pilih Kendaraan",
                    'url' => "/trip-pick-car/".$id
                ],
            ];

            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed

            
            $kendaraan = MsKendaraan::all()->where('status', 1);
            $date = Carbon::now();
            $trips = VwTrip::all()
            ->whereNotNull('id_trip') //yang telah dibuat tripnya
            ->whereNotIn("status", 4) //yang status tripnya belum closed
            ->where('departure_time', '>=', Carbon::now()->format('Y-m-d')); //yang waktu keberangkatannya hari ini atau lebih

            $trip = VwTrip::where('id_trip_request', $id)->first();
            // $p =  $trip->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));

            foreach($kendaraan as $k){
                
                $i = 0;
                
                $k->setAttribute('booked', 0);

                foreach($trips as $t){
                    // dd($t->departure_time ."==". $date);
                    if($t->kendaraan_id == $k->id){
                        $i++;
                        $k->booked = $i;
                    }
                }
            }

            return view('Qrgad/trip/pickCar', [
                "trip" => $trip,
                // "penumpang" => $penumpang,
                "kendaraans" => $kendaraan,
                "supirs" => MsSupir::all()->where('status', 1),
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip-dashboard')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }


    public function checkTrip()
    {
        try{
            if($this->PermissionActionMenu('trip-check')->r==1){

            $breadcrumb = [
                [
                    'nama' => "Jadwal TMS",
                    'url' => "/trip-schedule"
                ],
                [
                    'nama' => "Pilih Kendaraan",
                    'url' => "/trip-check"
                ],
            ];

            return view('Qrgad/trip/checkTrip', [
                "breadcrumbs" => $breadcrumb,
                "tanggal" => Carbon::now(),
                "trips" => VwTrip::all()->whereNotNull('id_trip')->whereNotNull('set_trip_time')->whereNotNull('kendaraan'),
                "actionmenu" => $this->permissionActionMenu('trip-check')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function checkTripById($id)
    {
        try{
            if($this->PermissionActionMenu('trip-check')->r==1){

            $breadcrumb = [
                [
                    'nama' => "Jadwal TMS",
                    'url' => "/trip-schedule"
                ],
                [
                    'nama' => "Check Trip",
                    'url' => "/trip-check/".$id
                ],
            ];

            $trip = VwTrip::where('id_trip', $id)->first();
            
            // $pp = $trip->penumpang;
            // $penumpang_plan = User::all()->whereIn('email', explode("," , $pp));

            // $pa = $trip->penumpang_aktual;
            // $penumpang_aktual = User::all()->whereIn('email', explode("," , $pa));
            
            return view('Qrgad/trip/checkTripById', [
                "breadcrumbs" => $breadcrumb,
                "tanggal" => Carbon::now(),
                "trips" => VwTrip::all()->whereNotNull('id_trip')->whereNotNull('set_trip_time')->whereNotNull('kendaraan'),
                "trip" => $trip,
                "penumpangs" => $this->getUserSF(999), //ganti API sunfish
                // "penumpangs_plan" => $penumpang_plan,
                // "penumpangs_aktual" => $penumpang_aktual,
                "actionmenu" => $this->permissionActionMenu('trip-check')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function checkTripScan()
    {
        try{
            if($this->PermissionActionMenu('trip-check')->v==1){

            $breadcrumb = [
                [
                    'nama' => "Jadwal TMS",
                    'url' => "/trip-schedule"
                ],
                [
                    'nama' => "Check Trip",
                    'url' => "/trip-check"
                ],
                [
                    'nama' => "Scan",
                    'url' => "/trip-check-scan"
                ],
            ];
            
            return view('Qrgad/trip/scanner', [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip-check')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function checkTripIdTrip($id)
    {
        try{
            if($this->PermissionActionMenu('trip-check')->r==1){
           
            $trip = TbTrip::all()->where('id', $id)->where('status', 1)->first();

            if ($trip != '' && $trip != '[]'){
                $result = true;
            } else {
                $result = false;
            }

            return $result;

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if($this->PermissionActionMenu('trip')->c==1){

            $kode = TbTripRequest::idOtomatis();

            if($request->jenis_perjalanan == 2){
                $validated = $request->validate([
                    "id" => "",
                    "keperluan" => "required",
                    "jenis_perjalanan" => "required",
                    "tujuan" => "required",
                    "wilayah" => "required",
                    "waktu_berangkat" => "required",
                    "waktu_pulang" => "required",
                ]);

            } else {
                $validated = $request->validate([
                    "id" => "",
                    "keperluan" => "required",
                    "jenis_perjalanan" => "required",
                    "tujuan" => "required",
                    "wilayah" => "required",
                    "waktu_berangkat" => "required",
                ]);

                $validated['waktu_pulang'] = null;
            }

            $penumpang = $request->penumpang != null?  implode(",",$request->penumpang) : null;
            $count_penumpang = $request->penumpang != null?  count($request->penumpang) : 0;

            //jenis perjalanan
            // 1 - one way
            // 2 - round trip

            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed
            $create = TbTripRequest::create([
                "id" => $kode,
                "keperluan" => $validated['keperluan'],
                "jenis_perjalanan" => $validated['jenis_perjalanan'],
                "tujuan" => $validated['tujuan'],
                "wilayah" => $validated['wilayah'],
                "penumpang" => $penumpang,
                "count_people" => $count_penumpang,
                "waktu_berangkat" => $validated['waktu_berangkat'],
                "waktu_pulang" => $validated['waktu_pulang'],
                "status" => 1,
                "input_time" => Carbon::now(),
                "pemohon" => Auth::user()->email,
                "departemen" => Auth::user()->department
            ]);
            
            $alert = '';
    
            if($create){
                $alert = 'success-add-permintaan kendaraan';
            } else {
                $alert = 'danger-add-permintaan kendaraan';
            }
    
            return redirect('/trip')->with('alert', $alert);
            
            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function checkOut(Request $request){
        try{
            if($this->PermissionActionMenu('trip-check')->c==1){
            $kode = TbTripHistori::idOtomatis();
            $validated = $request->validate([
                "id" => "",
                "kilometer_berangkat" => "required",
                "waktu_berangkat" => "required",
            ]);
            
            $penumpang = $request->penumpang != null?  implode(",",$request->penumpang) : null;

            $create = TbTripHistori::create([
                "id" => $kode,
                "trip" => $request->trip,
                "kilometer_berangkat" => $request->kilometer_berangkat,
                "waktu_berangkat" => $request->waktu_berangkat,
                "penumpang" => $penumpang
            ]);

            $alert = '';

            if($create){
                $alert = 'success-add-check out kendaraan';
            } else {
                $alert = 'danger-add-check out kendaraan';
            }
            
            return redirect('/trip-schedule')->with('alert', $alert);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function checkIn(Request $request){
        try{
            if($this->PermissionActionMenu('trip-check')->c==1){
            
            $validated = $request->validate([
                "id" => "",
                "kilometer_pulang" => "required",
                "waktu_pulang" => "required",
            ]);

            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed

            $trip_histori = TbTripHistori::where('id', $request->trip_histori)->first();

            $updateTripHistory = $trip_histori->update([
                "kilometer_pulang" => $request->kilometer_pulang,
                "waktu_pulang" => $request->waktu_pulang,
                "kilometer_total" => $request->kilometer_pulang - $trip_histori->kilometer_berangkat,
            ]);

            $trip = VwTrip::where('id_trip_histori', $request->trip_histori)->first();
            $updateTripRequest = TbTripRequest::where('id', $trip->id_trip_request)->update([
                "status" => 4
            ]);

            $alert = '';
    
            if($updateTripHistory && $updateTripRequest){
                $alert = 'success-add-check in kendaraan';
            } else {
                $alert = 'danger-add-check in kendaraan';
            }
            
            return redirect('/trip-schedule')->with('alert', $alert);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            if($this->PermissionActionMenu('trip')->v==1){        
            
            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
                [
                    'nama' => "Detail Trip",
                    'url' => "/trip/".$id
                ],
            ];
            
            $trip = VwTrip::where('id_trip_request', $id)->first();
            $voucher = TbTripVoucher::all()->where('trip', $trip->id_trip);
            
            // $p =  $trip->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));
            
            // $pa =  $trip->penumpang_aktual;
            // $penumpang_aktual = User::all()->whereIn('email', explode("," , $pa));
            
            return view('Qrgad/trip/show' , [
                "trip" => $trip,
                "vouchers" => $voucher,
                // "penumpangs" => $penumpang,
                // "penumpang_aktuals" => $penumpang_aktual,
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function showAdmin($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->v==1){        
            
            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
                [
                    'nama' => "Detail Trip",
                    'url' => "/trip/".$id
                ],
            ];
            
            $trip = VwTrip::where('id_trip_request', $id)->first();
            $voucher = TbTripVoucher::all()->where('trip', $trip->id_trip);
            
            // $p =  $trip->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));
            
            // $pa =  $trip->penumpang_aktual;
            // $penumpang_aktual = User::all()->whereIn('email', explode("," , $pa));
            
            return view('Qrgad/trip/showAdmin' , [
                "trip" => $trip,
                "vouchers" => $voucher,
                // "penumpangs" => $penumpang,
                // "penumpang_aktuals" => $penumpang_aktual,
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip-dashboard')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function showSchedule($id)
    {
        try{
            if($this->PermissionActionMenu('trip-schedule')->v==1){        
            
            $breadcrumb = [
                [
                    'nama' => "Jadwal TMS",
                    'url' => "/trip-schedule"
                ],
                [
                    'nama' => "Detail",
                    'url' => "/trip-schedule/".$id
                ],
            ];
            
            $trip = VwTrip::where('id_trip', $id)->first();
            
            // $p = $trip->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));
            
            // $pa = $trip->penumpang_aktual;
            // $penumpang_aktual = User::all()->whereIn('email', explode("," , $pa));
            
            return view('Qrgad/trip/showSchedule' , [
                "trip" => $trip,
                // "penumpangs" => $penumpang,
                // "penumpang_aktuals" => $penumpang_aktual,
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('trip-schedule')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function ticket($id)
    {
        try{
            if($this->PermissionActionMenu('trip')->v==1){         

            $breadcrumb = [
                [
                    'nama' => "TMS",
                    'url' => "/trip"
                ],
                [
                    'nama' => "Ticket",
                    'url' => "/trip-ticket/".$id
                ],
            ];
            
            $trip = VwTrip::where('id_trip', $id)->first();
            // $penumpang_trip = explode("," , $trip->penumpang);
            // $penumpang = array();
            
            // foreach($penumpang_trip as $p){
            //     array_push($penumpang, User::where('email', $p));
            // }

            if($trip->id_trip != '' && $trip->kendaraan != ''){
                $qrcode = QrCode::size(200)->generate($trip->id_trip);
            } else {
                $qrcode = '';
            }
            
            return view('Qrgad/trip/ticket' , [
                "breadcrumbs" => $breadcrumb,
                "trip" => $trip,
                // "penumpangs" => $penumpang,
                "qrcode" => $qrcode,
                "actionmenu" => $this->permissionActionMenu('trip')
            ]);
                
                    }else{
                            return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
                        }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    public function confirmApprove($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
           
            // $p = VwTrip::where('id_trip_request', $id)->first()->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));

            return view('Qrgad/trip/confirmApprove', [
                "trip_request" => VwTrip::all()->where('id_trip_request', $id)->first(),
                // "penumpangs" => $penumpang 
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    
    }

    public function confirmReject($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
           
            // $p = VwTrip::where('id_trip_request', $id)->first()->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));

            return view('Qrgad/trip/confirmReject', [
                "trip_request" => VwTrip::all()->where('id_trip_request', $id)->first(),
                // "penumpangs" => $penumpang
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function confirmResponse($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
           
            // $p = VwTrip::where('id_trip_request', $id)->first()->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));

            return view('Qrgad/trip/confirmResponse', [
                "trip_request" => VwTrip::all()->where('id_trip_request', $id)->first(),
                // "penumpangs" => $penumpang
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    
    }

    public function confirmSetTrip(Request $request, $id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
            echo $request->kendaraan;

            if($request->kendaraan != ''){
                $kendaraan = MsKendaraan::where('id', $request->kendaraan)->first();
                $trips = VwTrip::all()
                    ->whereNotNull('id_trip') //yang telah dibuat tripnya
                    ->whereNotIn('status', 4) //yang status tripnya belum closed
                    ->where('departure_time', '>=', Carbon::now()->format('Y-m-d')) //yang waktu keberangkatannya hari ini atau lebih
                    ->where('kendaraan_id', $request->kendaraan); //yang kendaraannya seperti kendaraan yang dipilih

            } else {
                $kendaraan = '';
                $trips = '';
            }


            return view('Qrgad/trip/confirmSetTrip', [
                "trip" => VwTrip::where('id_trip', $id)->first(),
                "trips" => $trips,
                "supirs" => MsSupir::all()->where('status', 1),
                "kendaraan" => $kendaraan
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    
    }

    public function confirmCancel($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
           
            // $p = VwTrip::where('id_trip_request', $id)->first()->penumpang;
            // $penumpang = User::all()->whereIn('email', explode("," , $p));

            return view('Qrgad/trip/confirmCancel', [
                "trip" => VwTrip::all()->where('id_trip', $id)->first(),
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
    }

    public function approve($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
            
            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed

            $trip_request = TbTripRequest::where('id', $id)->update([
                "approve_time" => Carbon::now(),
                "approve_by" => Auth::user()->email,
                "status" => 2
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function reject(Request $request, $id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
            
            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed

            $trip_request = TbTripRequest::where('id', $id)->update([
                "reject_time" => Carbon::now(),
                "reject_by" => Auth::user()->email,
                "keterangan" => $request->keterangan,
                "status" => 0
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function response($id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
            
            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed

            $id_trip = TbTrip::IdOtomatis();
            $trip = TbTrip::create([
                "id" => $id_trip,
                "trip_request" => $id,
                "status" => 1
            ]);

            $trip_request = TbTripRequest::where('id', $id)->update([
                "response_time" => Carbon::now(),
                "status" => 3
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function cancel(Request $request, $id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
            
            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GADsho
            // 3 - Responded
            // 4 - Closed

            //status trip
            // 0 - canceled
            // 1 - active

            $trip = TbTrip::where('id', $id)->update([
                "cancel_time" => Carbon::now(),
                "cancel_by" => Auth::user()->email,
                "keterangan" => $request->keterangan,
                "status" => 0
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function sendWhatsappDriver($id){

        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){

            $token = MsToken::orderBy('created_at', 'DESC')->first();
            $trip = VwTrip::where('id_trip', $id)->first();

            try{
                
                $response = Http::withToken($token->token)->post('https://graph.facebook.com/v13.0/101439039293669/messages', [
                    "messaging_product"=> "whatsapp", 
                    "to"=> "62".$trip->wa_supir, 
                    "type"=> "template",
                    "template"=> [ 
                        "name"=> "test_driver_trip_confirm", 
                        "language"=> [ "code"=> "id" ],
                        "components" => [
                            0 => [
                                "type" => "body",
                                "parameters" => [
                                    0 => [
                                        "type" => "text",
                                        "text" => $trip->id_trip
                                    ],
                                    1 => [
                                        "type" => "text",
                                        "text" => $trip->supir
                                    ],
                                    2 => [
                                        "type" => "text",
                                        "text" => $trip->kendaraan
                                    ],
                                    3 => [
                                        "type" => "text",
                                        "text" => $trip->nopol
                                    ],
                                    4 => [
                                        "type" => "text",
                                        "text" => $trip->tujuan.", ".$trip->wilayah
                                    ],
                                    5 => [
                                        "type" => "date_time",
                                        "date_time" => [
                                            "fallback_value" => date('d M Y H:i', strtotime($trip->departure_time))
                                        ] 
                                    ]
                                ]
                            ]
        
                        ]
                        
                    ]
                    
                ]);
            }catch(Exception $e){

            }

            return $response;

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
        
    }
    
    public function sendWhatsappPemohon($id){

        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){

            $token = MsToken::orderBy('created_at', 'DESC')->first();
            $trip = VwTrip::where('id_trip', $id)->first();
            $wa = $trip->wa_supir != ''? "+62".$trip->wa_supir : "-";
           
    
            try{
                $response = Http::withToken($token->token)->post('https://graph.facebook.com/v13.0/101439039293669/messages', [
                    "messaging_product"=> "whatsapp", 
                    "to"=> "62".$trip->wa_pemohon, 
                    "type"=> "template",
                    "template"=> [ 
                        "name"=> "test_employee_trip_confirm", 
                        "language"=> [ "code"=> "id" ],
                        "components" => [
                            0 => [
                                "type" => "body",
                                "parameters" => [
                                    0 => [
                                        "type" => "text",
                                        "text" => $trip->id_trip
                                    ],
                                    1 => [
                                        "type" => "text",
                                        "text" => $trip->supir
                                    ],
                                    2 => [
                                        "type" => "text",
                                        "text" => $wa
                                    ],
                                    3 => [
                                        "type" => "text",
                                        "text" => $trip->kendaraan
                                    ],
                                    4 => [
                                        "type" => "text",
                                        "text" => $trip->nopol
                                    ],
                                    5 => [
                                        "type" => "date_time",
                                        "date_time" => [
                                            "fallback_value" => date('d M Y H:i', strtotime($trip->departure_time))
                                        ] 
                                    ]
                                ]
                            ]
        
                        ]
                        
                    ]
                    
                ]);
            
            }catch(Exception $e){
     
            }
    
            return $response;

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
        
    }

    public function sendWhatsappPemohonGrab($id){

        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){

            $token = MsToken::orderBy('created_at', 'DESC')->first();
            $trip = VwTrip::where('id_trip', $id)->first();
            $trip_voucher = TbTripVoucher::all()->where('trip', $id);
            $voucher = '';
            foreach($trip_voucher as $t){
                if($trip_voucher->count() > 1){
                    $voucher = $t->kode_voucher.", ".$voucher;
                } else {
                    $voucher = $t->kode_voucher;
                }
            }

            try{
                
                $response = Http::withToken($token->token)->post('https://graph.facebook.com/v13.0/101439039293669/messages', [
                    "messaging_product"=> "whatsapp", 
                    "to"=> "62".$trip->wa_pemohon, 
                    "type"=> "template",
                    "template"=> [ 
                        "name"=> "test_employee_grab_trip_confirm", 
                        "language"=> [ "code"=> "id" ],
                        "components" => [
                            0 => [
                                "type" => "body",
                                "parameters" => [
                                    0 => [
                                        "type" => "text",
                                        "text" => $trip->id_trip
                                    ],
                                    1 => [
                                        "type" => "text",
                                        "text" => "GRAB"
                                    ],
                                    2 => [
                                        "type" => "date_time",
                                        "date_time" => [
                                            "fallback_value" => date('Y-m-d H:i:s', strtotime($trip->departure_time))
                                        ] 
                                    ],
                                    3 => [
                                        "type" => "text",
                                        "text" => $voucher
                                    ]
                                ]
                            ]
        
                        ]
                        
                    ]
                    
                ]);
            
            }catch(Exception $e){

            }

            

            return $response;

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 

        
    }
    
    public function setTrip(Request $request, $id)
    {
        try{
            if($this->PermissionActionMenu('trip-dashboard')->u==1){
            
            //status
            // 0 - rejected
            // 1 - Waiting Head
            // 2 - Waiting GAD
            // 3 - Responded
            // 4 - Closed

            $trip = TbTrip::where('id', $id)->first();

            if($request->kendaraan != null){

                $trip_update = TbTrip::where('id', $id)->update([
                    "kendaraan" => $request->kendaraan,
                    "supir" => $request->supir,
                    "departure_time" => $request->departure_time
                ]);

            } else {

                //update departure time trip
                $trip_update = TbTrip::where('id', $id)->update([
                    "departure_time" => $request->departure_time
                ]);

                //create trip voucher 
                if($request->kode_voucher != ''){
                    foreach($request->kode_voucher as $k){
                        if($k != ''){
                            TbTripVoucher::create([
                                "id" => TbTripVoucher::idOtomatis(),
                                "trip" => $id,
                                "kode_voucher" => $k
                            ]);
                        }
                    }
                }

                //update trip request closed karena grab

                $trip_request_update = TbTripRequest::where('id', $trip->trip_request)->update([
                    "status" => 4,
                    "close_time" => Carbon::now()
                ]);
            }

            //update trip request set trip
            $update = TbTripRequest::where('id', $trip->trip_request)->update([
                "set_trip_time" => Carbon::now()
            ]);

           
            if($trip_update != null){

                $trip_view = VwTrip::where('id_trip', $id)->first();

                if($request->kendaraan == null || $request->kendaraan == ''){
                    $this->sendWhatsappPemohonGrab($id);
                    
                } else {
                    
                    $this->sendWhatsappPemohon($id);
                    if($trip_view->wa_supir != null){
    
                        $this->sendWhatsappDriver($id);
                    }
                }


                session()->flash('alert', 'success-add-set trip');

                return response()->json([
                    'status'=>true,
                    "redirect_url"=>url('/trip-dashboard'),
                    
                ]);

            } else {

                session()->flash('alert', 'danger-add-set trip');

                return response()->json([
                    'status'=>true,
                    "redirect_url"=>url('/trip-dashboard'),
                    
                ]);
            }

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
