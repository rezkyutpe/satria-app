<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\TrxWfa;
use App\Models\View\VwTrxWfa;
use Illuminate\Support\Facades\Http;
use Exception;

class RadarMapsController extends Controller
{
    
    public function UserSubmission()
    {
        try {
            $submission = VwTrxWfa::where('id_user', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            $data = array(
                'submission' => $submission,
            );
            return view('user-submission')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function UserSubmissionDetail($id)
    {
        try {
            $submission = VwTrxWfa::where('id', $id)->orderBy('created_at', 'desc')->first();
            $data = array(
                'req' => $submission,
            );
            return response()->json($data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public static function searchAddresss($search)
    {
        try{
            $response = Http::withHeaders(['Authorization' =>'prj_live_sk_aeb8714a7f22e354110a275b71e40f53394d790b'])->get('https://api.radar.io/v1/search/autocomplete?query='.$search.'&near=40.7484405%2C-73.9878584');
            $data = json_decode($response,true);
            return $data;
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }
    public static function locationDistance($lat,$long)
    {
        try{
            $latlong_origin = Auth::user()->worklocation_lat_long;
            if($latlong_origin  == null){
                $latlong_origin = '-6.2822622, 107.1333345';
            }
            $response = Http::withHeaders(['Authorization' =>'prj_live_sk_aeb8714a7f22e354110a275b71e40f53394d790b'])->get('https://api.radar.io/v1/route/distance?origin='.$latlong_origin.'&destination='.$lat.','.$long.'&modes=car&units=metric');
            $data = json_decode($response,true);
            return $data;
        } catch (Exception $e) {
            $this->ErrorLog($e);
        }
    }
    
    public function PostSubmission(Request $request)
    {
        try {
            $deptheadtitle = strpos(Auth::user()->title,"Department Head");
            $divheadtitle = strpos(Auth::user()->title,"Division Head");
            $depth = isset($this->getDirectManager(Auth::user()->email)[0]['direct_manager']) ? $this->getDirectManager(Auth::user()->email)[0]['direct_manager'] : "";
            if($deptheadtitle != null){
                $depth = "not required";
                $divh = isset($this->getDirectManager(Auth::user()->email)[0]['direct_manager']) ? $this->getDirectManager(Auth::user()->email)[0]['direct_manager'] : "";
                if($request->input('country_code')!="Indonesia"){
                    $dich = isset($this->getDirectManager($divh)[0]['direct_manager']) ? $this->getDirectManager($divh)[0]['direct_manager'] : "";
                }
            }else if($divheadtitle != null){
                $depth = "not required";
                $divh = "not required";
                $dich = isset($this->getDirectManager(Auth::user()->email)[0]['direct_manager']) ? $this->getDirectManager(Auth::user()->email)[0]['direct_manager'] : "";
            }else{
                if($request->input('distance') > 80) {
                    $divh = isset($this->getDirectManager($depth)[0]['direct_manager']) ? $this->getDirectManager($depth)[0]['direct_manager'] : "";
                }else{
                    $divh = "not required"; 
                }
                if($request->input('country_code')!="Indonesia"){
                    // if($request->input('distance') > 80 ) {
                    $divh = isset($this->getDirectManager($depth)[0]['direct_manager']) ? $this->getDirectManager($depth)[0]['direct_manager'] : "";
                    $dich = isset($this->getDirectManager($divh)[0]['direct_manager']) ? $this->getDirectManager($divh)[0]['direct_manager'] : "";
                    // }else{
                    //     $dich = "not required";
                    // }
                }else{
                    $dich = "not required";
                }
            }
            $create = TrxWfa::create([
                'id_user' => Auth::user()->id,
                'worklocation' => Auth::user()->worklocation,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'destination_lat' => $request->input('lat'),
                'destination_long' => $request->input('long'),
                'destination_country_code' => $request->input('country_code'),
                'destination_province' => $request->input('province'),
                'destination_city' => $request->input('city'),
                'destination_address' => $request->input('address'),
                'distance' => $request->input('distance'),
                'reason' => $request->input('reason'),
                'approve_dept_to' => isset($depth) ? $depth : "",
                'approve_div_to' => isset($divh) ? $divh : "",
                'approve_dic_to' => isset($dich) ? $dich : "",
                'attendance_flag' => 1,
                'created_by' => Auth::user()->id,
            ]);
            if ($create) {
                return redirect()->back()->with('suc_message', 'Submit Submission Successfully!');
            } else {
                return redirect()->back()->with('err_message', 'Submit Submission Not Successfully!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
