<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\DtRuangan;
use App\Models\Table\Qrgad\MsLokasi;
use App\Models\Table\Qrgad\MsPerusahaan;
use App\Models\Table\Qrgad\MsRuangan;
use App\Models\Table\Qrgad\MsToken;
use App\Models\View\Qrgad\VwJadwalRuangan;
use App\Models\Table\Qrgad\TbJadwalRuangan;
use App\Models\Table\Qrgad\TbTripRequest;
use App\Models\Table\Qrgad\TbTripVoucher;
use App\Models\User;
use App\Models\View\Qrgad\VwFasilitasRuangan;
use App\Models\View\Qrgad\VwKeluhan;
use App\Models\View\Qrgad\VwTabelInventory;
use App\Models\View\Qrgad\VwTrip;
use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FeQrgadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showRoomLoanByDate($date){
        try{
            //detail
            // $date = ''; //tanggal yang dipilih oleh user
            $roomBookingsByDay = VwJadwalRuangan::where('start', 'like', '%' . $date . '%')->orWhere('end', 'like', '%' . $date . '%')->get();
            // $roomBookingsByDay = VwJadwalRuangan::all();

            return $roomBookingsByDay;
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    // public function userLoanRequest(Request $request)
    // {
    //     try{
    //         if($request->input('actionloan') == "car"){

    //             //---------PEMINJAMAN KENDARAAN--------
                
    //             //jenis perjalanan
    //             // 1 - one way
    //             // 2 - round trip

    //             //validasi
    //             // if($request->jenis_perjalanan == 2){
    //             //     $validated = $request->validate([
    //             //         "keperluan" => "required",
    //             //         "jenis_perjalanan" => "required",
    //             //         "tujuan" => "required",
    //             //         "wilayah" => "required",
    //             //         "waktu_berangkat" => "required",
    //             //         "waktu_pulang" => "required",
    //             //     ]);

    //             // } else {
    //             //     $validated = $request->validate([
    //             //         "keperluan" => "required",
    //             //         "jenis_perjalanan" => "required",
    //             //         "tujuan" => "required",
    //             //         "wilayah" => "required",
    //             //         "waktu_berangkat" => "required",
    //             //     ]);

    //             //     $validated['waktu_pulang'] = null;
    //             // }

    //             //status
    //             // 0 - rejected
    //             // 1 - Waiting Head
    //             // 2 - Waiting GAD
    //             // 3 - Responded
    //             // 4 - Closed
                
    //             // [P]+[S]+[0-9]*
    //             $waktu_pulang = "";
    //             $request->input('waktu_pulang') != ''? $waktu_pulang = $request->input('waktu_pulang') : $waktu_pulang = "";
    //             $penumpang = $request->penumpang != null?  implode(",",$request->input('penumpang')) : null;
    //             $count_penumpang = $request->penumpang != null?  count($request->input('penumpang')) : 0;
                
    //             //simpan data
    //             $create = TbTripRequest::create([
    //                 "id" => TbTripRequest::idOtomatis(),
    //                 "keperluan" => $request->input('keperluan'),
    //                 "jenis_perjalanan" => $request->input('jenis_perjalanan'),
    //                 "tujuan" => $request->input('tujuan'),
    //                 "wilayah" => $request->input('wilayah'),
    //                 "penumpang" => $penumpang,
    //                 "count_people" => $count_penumpang,
    //                 "waktu_berangkat" => $request->input('waktu_berangkat'),
    //                 "waktu_pulang" => $waktu_pulang,
    //                 "status" => 1,
    //                 "input_time" => Carbon::now(),
    //                 "pemohon" => Auth::user()->email,
    //                 "departemen" => Auth::user()->department
    //             ]);
    //             if($create){
    //                 return redirect()->back()->with('suc_message', 'Request Booking Success!');
    //             }else{
    //                 return redirect()->back()->with('err_message', 'Request Booking Fail!');
    //             }
    //         } else {

    //             //---------PEMINJAMAN RUANGAN---------
        
    //             //validasi
    //             // $validated = $request->validate([
    //             //     "agenda" => "required",
    //             //     "perusahaan" => "required",
    //             //     "ruangan" => "required",
    //             //     "tanggal" => "required",
    //             //     "start" => "required",
    //             //     "end" => "required"
    //             // ]);
        
    //             // $data = $this->validateRoomBooking($request->date, $request->input('start'), $request->input('end'), $request->input('ruangan'));
                
    //             //simpan data
    //             $request->input('color') == null || $request->color == ''? $colour = '#7771D2' : $colour = $request->color;
                
    //             // dd(preg_match("/PS/i", $request->input('perusahaan')));
                
    //             if(preg_match("/PS/i", $request->input('perusahaan'))){
    //                 $perusahaan = $request->input('perusahaan');
    //             } else {
    //                 $perusahaan = MsPerusahaan::idOtomatis();
    //                 MsPerusahaan::create([
    //                     "id" => $perusahaan,
    //                     "nama" => $request->input('perusahaan'),
    //                     "created_by" => Auth::user()->name,
    //                     "status" => 1,
    //                 ]);
    //             }
        
    //             $start = new DateTime(date('Y-m-d H:i:s', strtotime($request->input('date')." ".$request->input('start'))));
    //             $end = new DateTime(date('Y-m-d H:i:s', strtotime($request->input('date')." ".$request->input('end'))));
                
                
    //             $create = TbJadwalRuangan::create([
    //                 "id" => TbJadwalRuangan::idOtomatis(),
    //                 "peminjam" => Auth::user()->email,
    //                 "agenda" => $request->input('agenda'),
    //                 "perusahaan" => $perusahaan,
    //                 "ruangan" => $request->input('ruangan'),
    //                 "start" =>  $start->modify('+1 minutes'),
    //                 "end" =>  $end->modify('-1 minutes'),
    //                 "color" => $colour,
    //             ]);
    //             if($create){
    //                 return redirect()->back()->with('suc_message', 'Request Booking Success!');
    //             }else{
    //                 return redirect()->back()->with('err_message', 'Request Booking Fail!');
    //             }
    //         }

    //         //---------PEMINJAMAN IT--------- 

    //     } catch (Exception $e) {
    //         $this->ErrorLog($e);
    //         return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
    //     }
    // }

    public function userLoanRequest(Request $request)
    {
        try{
            if($request->input('actionloan') == "car"){

                //---------PEMINJAMAN KENDARAAN--------
                
                //jenis perjalanan
                // 1 - one way
                // 2 - round trip

                //validasi
                // if($request->jenis_perjalanan == 2){
                //     $validated = $request->validate([
                //         "keperluan" => "required",
                //         "jenis_perjalanan" => "required",
                //         "tujuan" => "required",
                //         "wilayah" => "required",
                //         "waktu_berangkat" => "required",
                //         "waktu_pulang" => "required",
                //     ]);

                // } else {
                //     $validated = $request->validate([
                //         "keperluan" => "required",
                //         "jenis_perjalanan" => "required",
                //         "tujuan" => "required",
                //         "wilayah" => "required",
                //         "waktu_berangkat" => "required",
                //     ]);

                //     $validated['waktu_pulang'] = null;
                // }

                //status
                // 0 - rejected
                // 1 - Waiting Head
                // 2 - Waiting GAD
                // 3 - Responded
                // 4 - Closed

                // [P]+[S]+[0-9]*
                $waktu_pulang = null;
                $request->input('jenis_perjalanan') == 2 ? $waktu_pulang = $request->input('waktu_pulang') : $waktu_pulang = null;
                $penumpang = $request->penumpang != null?  implode(",",$request->input('penumpang')) : null;
                $count_penumpang = $request->penumpang != null?  count($request->input('penumpang')) : 0;
                
                //simpan data
                $create = TbTripRequest::create([
                    "id" => TbTripRequest::idOtomatis(),
                    "keperluan" => $request->input('keperluan'),
                    "jenis_perjalanan" => $request->input('jenis_perjalanan'),
                    "tujuan" => $request->input('tujuan'),
                    "wilayah" => $request->input('wilayah'),
                    "penumpang" => $penumpang,
                    "count_people" => $count_penumpang,
                    "waktu_berangkat" => $request->input('waktu_berangkat'),
                    "waktu_pulang" => $waktu_pulang,
                    "status" => 1,
                    "input_time" => Carbon::now(),
                    "pemohon" => Auth::user()->email,
                    "departemen" => Auth::user()->department
                ]);
                if($create){
                    return redirect()->back()->with('suc_message', 'Request Booking Success!');
                }else{
                    return redirect()->back()->with('err_message', 'Request Booking Fail!');
                }
            } else {

                //---------PEMINJAMAN RUANGAN---------
        
                //validasi
                // $validated = $request->validate([
                //     "agenda" => "required",
                //     "perusahaan" => "required",
                //     "ruangan" => "required",
                //     "tanggal" => "required",
                //     "start" => "required",
                //     "end" => "required"
                // ]);
        
                // $data = $this->validateRoomBooking($request->date, $request->input('start'), $request->input('end'), $request->input('ruangan'));
                
                //simpan data
            
                $request->input('color') == null || $request->color == ''? $colour = '#7771D2' : $colour = $request->color;
                
                // dd(preg_match("/PS/i", $request->input('perusahaan')));
                
                if(preg_match("/PS/i", $request->input('perusahaan'))){
                    $perusahaan = $request->input('perusahaan');
                } else {
                    $perusahaan = MsPerusahaan::idOtomatis();
                    MsPerusahaan::create([
                        "id" => $perusahaan,
                        "nama" => $request->input('perusahaan'),
                        "created_by" => Auth::user()->name,
                        "status" => 1,
                    ]);
                }
        
                // $start = $request->date.$request->start;
                // $end = $request->date.$request->end;

                $start = new DateTime(date('Y-m-d H:i:s', strtotime($request->input('date')." ".$request->input('start'))));
                $end = new DateTime(date('Y-m-d H:i:s', strtotime($request->input('date')." ".$request->input('end'))));
                
                $create = TbJadwalRuangan::create([
                    "id" => TbJadwalRuangan::idOtomatis(),
                    "peminjam" => Auth::user()->email,
                    "agenda" => $request->input('agenda'),
                    "perusahaan" => $perusahaan,
                    "ruangan" => $request->input('ruangan'),
                    "start" =>  $start->modify('+1 minutes'),
                    "end" =>  $end->modify('-1 minutes'),
                    "color" => $colour,
                ]);
                if($create){
                    return redirect()->back()->with('suc_message', 'Request Booking Success!');
                }else{
                    return redirect()->back()->with('err_message', 'Request Booking Fail!');
                }
            }

            //---------PEMINJAMAN IT--------- 

        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function validateRoomBooking(Request $request){
        $date = $request->date;
        $start = $request->input('start');
        $end = $request->input('end'); 

        $roomBookings = TbJadwalRuangan::
        where(function($query) use ($start, $end){
            $query->orWhere(function($q1) use ($start, $end){ //start_input nya berada di antara start dan end yang sudah ada
                $q1->where('start', '<=', $start)
                    ->where('end', '>=', $start);
            })->orWhere(function($q2) use ($start, $end){ //end_input nya berada di antara start dan end yang sudah ada
                $q2->where('start', '<=', $end)
                    ->where('end', '>=', $end);
            })->orWhere(function($q3) use ($start, $end){ //start_input kurang dari start yang ada dan end_input melebihi end yang ada
                $q3->where('start', '<=', $start)
                    ->where('end', '>=', $end);
            })->orWhere(function($q4) use ($start, $end){ //start_input melebihi dari start yang ada dan end_input kurang dari end yang ada
                $q4->where('start', '>=', $start)
                    ->where('end', '<=', $end);
            });
        })->get();

        $rooms = MsRuangan::all()->where("status", 1);
        foreach($rooms as $r){
            $r->setAttribute("booked", 0);
            foreach($roomBookings as $rb){
                if($rb->ruangan == $r->id){
                    $r->setAttribute("booked", 1);
                }
            }
        }

        return $data[] = [
            'roomBookings' => $roomBookings,
            'start'=> $start,
            'end'=> $end, 
            'rooms' => $rooms,
        ];

    }

    public function showFacility($id){
        try{
            $facilities = DtRuangan::
            join('ms_fasilitas_ruangans', 'dt_ruangans.fasilitas', 'ms_fasilitas_ruangans.id')
            ->where("ruangan", $id)
            ->select(['ms_fasilitas_ruangans.nama as fasilitas', 'dt_ruangans.jumlah'])->get();
            $room = MsRuangan::where("id", $id)->first();
            $location = MsLokasi::where("id", $room->lokasi)->first();
            
            return $data[] = [
                'facilities' => $facilities,
                'room' => $room,
                'location' => $location,
            ];
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function userTms(){
        try{
            $carLoans = VwTrip::where('email', Auth::user()->email)->orderBy('departure_time', 'DESC')->get();
            $data = array(
                'carLoans' => $carLoans,
            );
            return view('user-tms')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function userRoom(){
        try{
            $roomLoans = VwJadwalRuangan::all()->where('email', Auth::user()->email);
            $data = array(
                'roomLoans' => $roomLoans,
                'date' => Carbon::now(),
            );
            return view('user-room')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function detailRoom($id){
        try{
            $jadwal = VwJadwalRuangan::findOrFail($id);
            return $jadwal;
        } catch (Exception $e) {    
            //     $this->ErrorLog($e);
            //     return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        } 
    }

    public function detailTms($id){
        // try{
            $trip = VwTrip::where('id_trip_request', $id)->first();
            $voucher = TbTripVoucher::where('trip', $trip->id_trip)->get('kode_voucher');
            
            $p =  $trip->penumpang;
            $penumpang = User::whereIn('email', explode("," , $p))->get('name');
            
            $pa =  $trip->penumpang_aktual;
            $penumpang_aktual = User::all()->whereIn('email', explode("," , $pa));

            if($trip->id_trip != '' && $trip->kendaraan != ''){
                $qrcode = QrCode::size(100)->generate($trip->id_trip);
                
            } else {
                $qrcode = '';
            }
            
            
            return view("detail-tms", [
                "trip" => $trip,
                "penumpang" => $penumpang,
                "qrcode" => $qrcode,
                "voucher" => $voucher,
            ]);

        // } catch (Exception $e) {    
            //     $this->ErrorLog($e);
            //     return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        // } 
    }

}
