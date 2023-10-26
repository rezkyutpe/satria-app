<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Mail\SendMom;
use App\Models\User;
use App\Models\ErrorLogs;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Incentive\Customer;
use App\Models\Table\Incentive\Gpm;
use App\Models\Table\Incentive\Aging;
use App\Models\Table\Incentive\IncEF;
use App\Models\Table\Incentive\CustType;
use App\Models\Table\Incentive\TargetPercent;
use App\Models\Table\Incentive\SalesTarget;
use App\Models\View\Incentive\VwUserGrade;
use App\Models\View\Elsa\VwAssist;
use App\Models\Table\Elsa\InventoryHistory;
use App\Models\Table\MstPlant;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function MapPublicPath() {
        $path = public_path().'/';
        if (env('DEPLOYMENT_STATUS', 0) == 1){
            $path = "";
        }
        return $path;
    }
    
    public function TimeInterval($date1,$date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $interval = abs($time1-$time2);
        return $interval;
    }
    public function cekElsaAssist($id)
    {
        $checkuser = User::where('id',$id)->first();
        return $checkuser;
    }
    public static function PermissionMenu($menu) {
        $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link',$menu)->count();

        return $appsmenu;
    }
    public static function PermissionActionMenu($menu) {
        $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link',$menu)->first();

        return $appsmenu;
    }
    public function UserDirectCreate($nama,$email,$email_sf,$wa,$password)
    {
        try{
            $create = User::create([
                'name' => $nama,
                'email' => $email,
                'email_sf' => $email_sf,
                'phone' => $wa,
                'role_id' => 30,
                'password' => Hash::make($password),
            ]);
            return $create;
        } catch (Exception $e) {    
            $this->ErrorLog($e);     
        }
    }

    public function UserSubcontDirectCreate($data)
    {
        try {
            $user = User::where('email', $data['email'])->first();
            if (empty($user)) {
                $create = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'email_sf' => $data['email_sf'],
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'phone' => $data['phone'],
                    'department' => $data['departement'],
                    'section' => $data['section'],
                    'division' => $data['division'],
                    'dept' => $data['department_code'],
                    'section_code' => $data['section_code'],
                    'divid' => $data['division_code'],
                    'company_name' => $data['company_name'],
                    'companyid' => $data['company_id'],
                    'worklocation_code' => $data['worklocation_code'],
                    'worklocation_name' => $data['worklocation_name'],
                    'worklocation_lat_long' => $data['worklocation_lat_long'],
                    'title' => $data['title'],
                    'role_id' => 10,
                    'password' => Hash::make($data['password'])
                ]);
                return $create;
            } else {
                return 'Email telah digunakan! Gunakan alamat email yang belum terdaftar!';
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return 'Error Request, Exception Error';
        }
    }

    public static function LoginSF($username,$password)
    {
        try{
            $postdata=array(
            'email'=>$username,
            'password'=>$password,
            );
            $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->post(env('ENV_API_MAN_URL').'sf-login',$postdata);
            $data = json_decode($response,true);
            return $data;
        } catch (Exception $e) {
        //     $this->ErrorLog($e);
        }
    }
    public static function getDirectManager($nrp)
    {
        try{
            $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'sf-emp-atasan/'.$nrp);
            $data = json_decode($response,true);
            return $data['data'];
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }
    public static function getUserSF($nrp)
    {
        try{
            if($nrp==999){
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'sf-emp-list');
                $arr = json_decode($response,true);
                return $arr['data'];
            }else{
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'sf-emp-detail/'.$nrp);
                $arr = json_decode($response,true);
                return $arr['data'];
            }
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }

    public static function getSafetyHour($year = null, $month = null, $company = null)
    {
        try{
            if($year == null && $month == null && $company == null){
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'safety-hour-report/patria/'.date("Y/m", strtotime("-1 month", strtotime(date("Y-m-d H:i:s")))));
                $arr = json_decode($response,true);
                return $arr['data'];
            }else{
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'safety-hour-report/'.$company.'/'.$year.'/'.$month);
                $arr = json_decode($response,true);
                return $arr['data'];
            }
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }

    public static function getAttendanceRatio($year = null, $month = null, $company = null, $departement = null)
    {
        try{
            if($year == null && $month == null && $company == null && $departement == null){
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'attendance-ratio-report/patria/'.Auth::user()->dept.'/'.date("Y/m", strtotime("-1 month", strtotime(date("Y-m-d H:i:s")))));
                $arr = json_decode($response,true);
                return $arr['data'];
            }else{
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'attendance-ratio-report/'.$company.'/'.$departement.'/'.$year.'/'.$month);
                $arr = json_decode($response,true);
                return $arr['data'];
            }
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }

    public static function getDepartement($company)
    {
        try{
            $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'sf-dept?company='.$company);
            $arr = json_decode($response,true);
            return $arr['data'];
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }

    function getTotalWeekdaysInMonth($monthYear) {
        try{
            list($year, $month) = explode('-', $monthYear);

            //Mendapatkan total holiday di bulan yang ditentukan
            $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->get(env('ENV_API_MAN_URL').'ima-get-holiday-new?year='.$year);
            $arr = json_decode($response,true);
            // Membuat array untuk menyimpan hari libur yang bukan Sabtu atau Minggu
            $hariLiburBukanWeekend = [];
            $bulan = $month;
    
            foreach ($arr['data'] as $libur) {
                $startDate = Carbon::parse($libur["StartDate"]);
                $endDate = Carbon::parse($libur["EndDate"]);
    
                // Cek apakah hari libur jatuh pada bulan yang diminta
                if ($startDate->format("n") == $bulan) {
                    // Cek apakah hari libur bukan Sabtu (6) atau Minggu (7)
                    if (!$startDate->isWeekend()) {
                        $jumlahHariLibur = 1; // Default 1 hari libur
    
                        if (!$startDate->equalTo($endDate)) {
                            // Jika StartDate dan EndDate berbeda, hitung jumlah hari libur di antara keduanya
                            $interval = $startDate->diff($endDate);
                            $jumlahHariLibur = $interval->days + 1; // Termasuk hari StartDate dan EndDate
                        }
    
                        // Tambahkan hari libur ke array jika bukan weekend
                        for ($i = 0; $i < $jumlahHariLibur; $i++) {
                            $hariLiburBukanWeekend[] = $startDate->format("Y-m-d");
                            $startDate->addDay();
                        }
                    }
                }
            }
    
            // Mendapatkan jumlah holiday yang bukan Sabtu atau Minggu pada bulan yang diminta
            $totalHoliday = count($hariLiburBukanWeekend);

            // menghitung total weekdays di luar holiday
            $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
            $lastDayOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $totalWeekdays = 0;

            while ($firstDayOfMonth <= $lastDayOfMonth) {
                // Cek jika hari saat ini bukan Sabtu atau Minggu
                if ($firstDayOfMonth->isWeekday()) {
                    $totalWeekdays++;
                }

                $firstDayOfMonth->addDay();
            }

            // mengembalikan nilai dengan weekdays dikurang totalholiday di luar sabtu dan minggu
            return ($totalWeekdays - $totalHoliday);

        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }
    
    public static function getPerformance($nrp,$password,$type,$year)
    {
        try{
            $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),'x-api-userid'=>$nrp,'x-api-type'=>$type,'x-api-year'=>$year,'x-api-password'=>$password])->get('http://satria2.patria.co.id/satria-api-man/public/api/get-performance-emp');
            $data = json_decode($response,true);
            return $data;
        } catch (Exception $e) {
            // $this->ErrorLog($e);
        }
    }
    public function emailElsa($ticket_id,$msg_color,$asset_name,$message,$assist_name,$sts)
    {
        $body ="Tiket #".$ticket_id."</b> telah ".$sts."<br>
        Berikut detail tiket anda : <br>
            <table
                <tr>
                  <td>ID Tiket</td>
                  <td> : </td>
                  <td>".$ticket_id."</td>
                </tr>
                <tr>
                  <td>Asset</td>
                  <td> : </td>
                  <td>".$asset_name." </td>
                </tr>
                <tr>
                  <td>Assist</td>
                  <td> : </td>
                  <td>".$assist_name." </td>
                </tr>
                <tr>
                  <td>Description</td>
                  <td> : </td>
                  <td>".$message."  </td>
                </tr>
            </table>  <br>
        Anda dapat melihat status tiket Anda di sini: https://satria.patria.co.id<br>
        Terimakasih. <br><br>
        <p style='color : red;'>* Ini merupakan pesan otomatis yang digenerate oleh sistem ".$msg_color."</p>.";
        return $body;
    }
    public function emailPR($pr_id,$user,$cat,$message,$qty,$sts)
    {
        $body ="PR #".$pr_id."</b> dari <b>".$user."</b> membutuhkan persetujuan anda <br>
        Berikut detail PR : <br>
            <table
                <tr>
                  <td>PR ID</td>
                  <td> : </td>
                  <td>".$pr_id."</td>
                </tr>
                <tr>
                  <td>Category</td>
                  <td> : </td>
                  <td>".$cat." </td>
                </tr>
                <tr>
                  <td>Qty</td>
                  <td> : </td>
                  <td>".$qty." </td>
                </tr>
                <tr>
                  <td>Description</td>
                  <td> : </td>
                  <td>".$message."  </td>
                </tr>
            </table>  <br>
        Anda dapat melihat status purchasing request Anda di sini: http://satria.patria.co.id<br>
        Terimakasih. <br><br>
        <p style='color : red;'>* Ini merupakan pesan otomatis yang digenerate oleh sistem atas pembuatan purchasing request baru</p>.";
        return $body;
    }
    function send($name,$subject,$email,$message)
    {

        try {
            $data = array(
                'name'      =>  $name,
                'subject'      =>  $subject,
                'email'      =>  $email,
                'message'   =>   $message
            );

            Mail::to($email)->send(new SendMail($data));
            if (Mail::failures()) {
                return "Email Not Sending!";
            }

        return "Send Email Successfully!";
        } catch (Exception $e) {
            $this->ErrorLog($e);     
        }
    }
    function sendMom($name,$subject,$email,$matnum,$matdesc,$noso,$cust,$qty,$note,$req_deliv_date,$message,$bom)
    {
        try {
            $data = array(
                'name'      =>  $name,
                'subject'      =>  $subject,
                'email'      =>  $email,
                'matnum'      =>  $matnum,
                'matdesc'      =>  $matdesc,
                'noso'      =>  $noso,
                'cust'      =>  $cust,
                'qty'      =>  $qty,
                'note'      =>  $note,
                'req_deliv_date'      =>  $req_deliv_date,
                'message'   =>   $message,
                'bom'   =>   $bom
            );

            Mail::to($email)->send(new SendMom($data));
            if (Mail::failures()) {
                return "Email Not Sending!";
            }

            return "Send Email Successfully!";
        } catch (Exception $e) {    
            $this->ErrorLog($e);     
        }
    }
    public static function Grade($id) {
        $grade = VwUserGrade::select('percentage')->where('email',$id)->first();

        return isset($grade['percentage']) ? $grade['percentage'] :  0;
    }
    public static function Gpm($cost,$price,$cat) {

        $gp = round((round($price,0)-round($cost,0) )/round($price,0)*100,0);
        if($gp<0){
            $gp=0;
        }elseif($gp>100){
            $gp=100;
        }
        $gpm = Gpm::select('percentage')->where('cat',$cat)->whereRaw('? between min and max', [$gp])->first();

        return isset($gpm['percentage']) ? $gpm['percentage'] : 0;
    }
    public static function Aging($date1,$date2,$type,$cat) {
        $datediff1=date_create($date1);
        $datediff2=date_create($date2);
        $diff=date_diff($datediff1,$datediff2);
        $aging = Aging::select('percentage')->where('type',$type)->where('cat',$cat)->whereRaw('? between min and max', [$diff->format("%a")])->first();

        return isset($aging['percentage']) ? $aging['percentage'] : 0;
    }
    public static function IncEF($cat) {
        $incef = incEF::select('percentage')->where('descrip',$cat)->first();

        return isset($incef['percentage']) ? $incef['percentage'] : 0;
    }
    public static function Target($total,$sales,$cat) {
        
        $salestarget = VwUserGrade::where('email',$sales)->first();
        $target = round($total/$salestarget->month*100,0);
        $targetpercent = TargetPercent::select('percentage')->where('cat',$cat)->whereRaw('? between min and max', [$target])->first();

        return isset($targetpercent['percentage']) ? $targetpercent['percentage'] : 0;
    }
    public static function CekCust($id) {
        $customer = Customer::where('id', $id)->first();
        $custtype = CustType::select('percentage')->where('id',isset($customer['id']) ? 2 : 1)->first();
        return isset($custtype['percentage']) ? $custtype['percentage'] : 0;

    }
    public static function CustType($id) {
        $custtype = CustType::select('percentage')->where('id',$id)->first();

        return isset($custtype['percentage']) ? $custtype['percentage'] : 0;
    }
    public static function Total($grading,$aging,$gpm,$target,$inc_ef) {
        $total = round((($grading/100)*($aging/100)*($gpm/100)*($target)*$inc_ef)/100,2);
        return isset($total) ? $total : 0;

    }
    public static function setInventoryHistory($idinv,$cat,$text,$qty)
    {
        $create = InventoryHistory::create([
            'id_inventory'=>$idinv,
            'cat'=>$cat,
            'text'=>$text,
            'qty'=>$qty,
            'created_by' => Auth::user()->id,
        ]);
    }
    public static function ErrorLog($e)
    {
        try {
            $message = $e->getMessage();
            $code = $e->getCode();    
            $string = $e->__toString();   
            $create = ErrorLogs::create([
                'remote_addr'=>$_SERVER['REMOTE_ADDR'],
                'action'=>url()->current(),
                'code'=>$code,
                'message'=>$message,
                'ex_string'=>$string,
                'apps'=>Auth::user()->accessed_app,
                'created_by' => Auth::user()->email,
            ]);
        } catch (Exception $e) {
        }
    }
    public static function ErrorLogLogin($e,$id)
    {
        try {
            $message = $e->getMessage();
            $code = $e->getCode();    
            $string = $e->__toString();   
            $create = ErrorLogs::create([
                'remote_addr'=>$_SERVER['REMOTE_ADDR'],
                'action'=>url()->current(),
                'code'=>$code,
                'message'=>$message,
                'ex_string'=>$string,
                'apps'=>1,
                'created_by' => $id,
            ]);
        } catch (Exception $e) {
        }
    }
    public static function LogLogin($code,$message,$string,$id)
    {
        try {
            $create = ErrorLogs::create([
                'remote_addr'=>$_SERVER['REMOTE_ADDR'],
                'action'=>url()->current(),
                'code'=>$code,
                'message'=>$message,
                'ex_string'=>$string,
                'apps'=>1,
                'created_by' => $id,
            ]);
        } catch (Exception $e) {
        }
    }
    
    function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 
    
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
    }

    public function data_plant()
    {
        $plant = MstPlant::select('plant')->whereIn('plant', ['1000', 'UCKR', 'PCKR'])->get();
        $plant2 = MstPlant::select('plant')->whereNotIn('plant', $plant)->get();
        foreach ($plant as $item) {
            $kode_plant['plant'][] = $item->plant;
        }
        foreach ($plant2 as $item) {
            $kode_plant['plant2'][] = $item->plant;
        }
        return $kode_plant;
    }

}
