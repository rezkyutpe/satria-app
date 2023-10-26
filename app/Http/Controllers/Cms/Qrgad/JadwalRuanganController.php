<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsPerusahaan;
use App\Models\Table\Qrgad\MsRuangan;
use App\Models\Table\Qrgad\MsToken;
use App\Models\View\Qrgad\VwJadwalRuangan;
use App\Models\Table\Qrgad\TbJadwalRuangan;
use App\Models\View\Qrgad\VwKeluhan;
use App\Models\View\Qrgad\VwTabelInventory;
use App\Models\View\Qrgad\VwTrip;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JadwalRuanganController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
        if ($this->PermissionMenu('jadwal-ruangan') == 0){
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function index()
    {
        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->r==1){

            $jadwal_arr = array();
            $jadwals = VwJadwalRuangan::all();
            $breadcrumb = [
                [
                    'nama' => "Jadwal Peminjaman Ruangan",
                    'url' => "/jadwal-ruangan"
                ]
            ];
            
            foreach ($jadwals as $jadwal){
                $jadwal_arr[] = [
                    'title' => " | ".$jadwal->agenda,
                    'start' => $jadwal->start,
                    'end' => $jadwal->end,
                    'backgroundColor' => $jadwal->color,
                ];
            }
            
            return view('Qrgad/jadwal_ruangan/index', [
                'jadwals' => $jadwal_arr,
                'breadcrumbs' => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('jadwal-ruangan')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 

    }

    public function getByDay(){

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->r==1){


            $date = $_GET['date'];
            $jadwals = VwJadwalRuangan::where('start', 'like', '%' . $date . '%')->orWhere('end', 'like', '%' . $date . '%')->get();
            $data = array(
                "actionmenu" => $this->permissionActionMenu('jadwal-ruangan')
            );
            
            return view('Qrgad/jadwal_ruangan/byDate', [
                'tanggal' => $date,
                'jadwals' => $jadwals,
                'isConflict' => false,
                'isValidTime' => true
            ])->with('data', $data);
            
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
    public function create(Request $request)
    {

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->c==1){
            
            $breadcrumb = [
                [
                    'nama' => "Jadwal Peminjaman Ruangan",
                    'url' => "/jadwal-ruangan"
                ],
                [
                    'nama' => "Tambah",
                    'url' => "/jadwal-ruangan/create"
                ],
            ];
            
            return view('Qrgad/jadwal_ruangan/create', [
                'perusahaans' => MsPerusahaan::all(),
                'ruangans' => MsRuangan::where('status', 1)->orderBy('lantai', 'ASC')->get(),
                'tanggal' => $request->date,
                'breadcrumbs' => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('jadwal-ruangan')
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->c==1){

            $validated = $request->validate([
                "agenda" => "required",
                "perusahaan" => "required",
                "ruangan" => "required",
                "tanggal" => "required",
                "start" => "required",
                "end" => "required"
            ]);
    
            $validated['perusahaan'] = $request->perusahaan;
            if($request->color == null || $request->color == ''){
                $validated['color'] = '#7771D2';
            } else {
                $validated['color'] = $request->color;
            }

            $req_start = $validated['start'];
            $req_end = $validated['end'];
    
            $start = date('Y-m-d H:i:s', strtotime($req_start));
            $end = date('Y-m-d H:i:s', strtotime($req_end));
    
            $ruangan = $validated['ruangan'];
    
            $jadwals = TbJadwalRuangan::all()->where("ruangan", $ruangan);
            $jadwalSelisih = array();
    
            $isValidTime = true;
            $isConflict = false;
    
    
            //cek apakah inputan jam benar (end harus lebih dari start)
            if( $end < $start){
                $isValidTime = false; //input jam salah
            } else {
                $isValidTime = true; //input jam benar
            }
    
            //cek apakah inputan jam berselisih dengan jadwal peminjaman ruangan lain
            foreach($jadwals as $j){
            
                if(
                    ($start >= $j->start && $start <= $j->end)  //start_input nya berada di antara start dan end yang sudah ada
                    || ($end >= $j->start and $end <= $j->end) //end_input nya berada di antara start dan end yang sudah ada
                    || ($start <= $j->start and $end >= $j->end) //start_input melebihi start yang ada dan end_input melebihi end yang ada
                    
                    ){
                        $isConflict = true;
                        $jadwal = VwJadwalRuangan::find($j->id);
                        array_push($jadwalSelisih, $jadwal);
                }
            }
    
            $kode = TbJadwalRuangan::idOtomatis();
            if($isValidTime && !$isConflict){

                $id = $kode;
                $create = TbJadwalRuangan::create([
                    "id" => $id,
                    "peminjam" => Auth::user()->email,
                    "agenda" => $validated['agenda'],
                    "perusahaan" => $validated['perusahaan'],
                    "ruangan" => $validated['ruangan'],
                    "start" => $start,
                    "end" => $end,
                    "color" => $validated['color']
                ]);

                if($create){
                    $token = MsToken::orderBy('created_at', 'DESC')->first();
                    $jadwal_ruangan = VwJadwalRuangan::where('id', $kode)->first();

                    if(date("Y-m-d",strtotime($jadwal_ruangan->start)) == date("Y-m-d",strtotime($jadwal_ruangan->end))){
                        $waktu = date("d M Y H:i",strtotime($jadwal_ruangan->start))." - ".date("H:i",strtotime($jadwal_ruangan->end));
                    }
                    else{
                        $waktu = date("d M Y H:i",strtotime($jadwal_ruangan->start))." - ".date("d M Y H:i",strtotime($jadwal_ruangan->end));
                    }

                    try {
                        $response = Http::withToken($token->token)->post('https://graph.facebook.com/v13.0/101439039293669/messages', [
                            "messaging_product"=> "whatsapp", 
                            "to"=> "62".Auth::user()->phone, 
                            "type"=> "template",
                            "template"=> [ 
                                "name"=> "test_jadwal_ruangan", 
                                "language"=> [ "code"=> "id" ],
                                "components" => [
                                    0 => [
                                        "type" => "body",
                                        "parameters" => [
                                            0 => [
                                                "type" => "text",
                                                "text" => $jadwal_ruangan->perusahaan 
                                            ],
                                            1 => [
                                                "type" => "text",
                                                "text" => $jadwal_ruangan->ruangan
                                            ],
                                            2 => [
                                                "type" => "date_time",
                                                "date_time" => [
                                                    "fallback_value" => $waktu
                                                ] 
                                            ]
                                        ]
                                    ]
                
                                ]
                                
                            ]
                            
                        ]);

                    } catch (Exception $e){
                        return redirect('/jadwal-ruangan')->with('alert', 'danger-sendwhatsapp-');
                    }
                    
                    return redirect('/jadwal-ruangan')->with('alert', 'success-add-jadwal ruangan');

                } else {
                    return redirect('/jadwal-ruangan')->with('alert', 'danger-add-jadwal ruangan');
                }
                
           } else {
                return back()->withInput($request->input())->with('errorDate', 'error date');
           }
            
            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 

    }

    public function validateDate(){

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->c==1){

            $date = $_GET['date'];
            $room = $_GET['room'];
            $r_start = $_GET['start'];
            $r_end = $_GET['end'];
    
            $start = date('Y-m-d H:i:s', strtotime($r_start));
            $end = date('Y-m-d H:i:s', strtotime($r_end));
    
            $jadwals = TbJadwalRuangan::all()->where('ruangan', $room);
            $ruangan = MsRuangan::find($room);
            $jadwalSelisih = array();
    
            $isValidTime = false;
            $isConflict = false;
    
            //cek apakah inputan jam benar (end harus lebih dari start)
            if( $end < $start){
                $isValidTime = false; //input jam salah
            } else {
                $isValidTime = true; //input jam benar
            }
    
            //cek apakah inputan jam berselisih dengan jadwal peminjaman ruangan lain
            foreach($jadwals as $j){
              
                if(
                    ($start >= $j->start && $start <= $j->end)  //start_input nya berada di antara start dan end yang sudah ada
                    || ($end >= $j->start and $end <= $j->end) //end_input nya berada di antara start dan end yang sudah ada
                    || ($start <= $j->start and $end >= $j->end) //start_input melebihi start yang ada dan end_input melebihi end yang ada
                    
                    ){
                        $isConflict = true;
                        $jadwal = VwJadwalRuangan::find($j->id);
                        array_push($jadwalSelisih, $jadwal);
                }
            } 
    
            return view('Qrgad/jadwal_ruangan/byDate', [
                'tanggal' => $date,
                'ruangan' => $ruangan->nama,
                'jadwals' => $jadwalSelisih,
                'isConflict' => $isConflict,
                'isValidTime' => $isValidTime
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function history(){

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->r==1){


            $jadwals = VwJadwalRuangan::all()->where('email', Auth::user()->email);
            
            $breadcrumb = [
                [
                    'nama' => "Jadwal Peminjaman Ruangan",
                    'url' => "/jadwal-ruangan"
                ],
                [
                    'nama' => "Riwayat",
                    'url' => "/jadwal-ruangan-history"
                ],
            ];

            return view('Qrgad/jadwal_ruangan/history', [
                "jadwals" => $jadwals,
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('jadwal-ruangan')
            ]);
            
            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
        
        
    }

    public function ticket($id){

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->v==1){


            $jadwal = VwJadwalRuangan::findOrFail($id);

            return view('Qrgad/jadwal_ruangan/ticket', [
                "jadwal" => $jadwal
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
        //
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
        //
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
