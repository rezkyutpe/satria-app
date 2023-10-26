<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\MstApps;
use App\Models\View\VwPermissionAppMenu;
use Exception;
class UserPerformanceManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('user-performance') == 0){
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function UserPerformanceMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-performance')->r==1){
                $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),'x-api-type'=>'PKBNK'])->get('http://satria2.patria.co.id/satria-api-man/public/api/performance-list');
                $res = json_decode($response,true);
                $data = array(
                    'pis' => $res['data'],
                    // 'actionmenu' => $this->PermissionActionMenu('pis-management'),
                );
                // dd($data);
                return view('user-management/user-performance')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }
    public function UserPerformanceMgmtPreview(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-performance')->r==1){
                $lines = explode(PHP_EOL, $request->raw_data);
                $array = array();
                foreach ($lines as $line) {
                    $array[] = str_getcsv($line,";");
                }
                $data = array(
                    'pis' => $array,
                    'lastrows' =>$request->lastrows
                    // 'actionmenu' => $this->PermissionActionMenu('pis-management'),
                );
                return view('user-management/raw-preview-performance')->with('data', $data);
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }
    
    public function UserPerformanceMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('user-performance')->r==1){
                if($request->input('letterno'))
                {
                    foreach($request->input('letterno') as $item=>$v){
                        if($request->input('letterno')!=''){
                            
                            $data2=array(
                                'letterno'=>$request->input('letterno')[$item],
                                'datesigned'=>$request->input('datesigned')[$item],
                                'locationsigned'=>$request->input('locationsigned')[$item],
                                'nrp'=>$request->input('nrp')[$item],
                                'name'=>$request->input('name')[$item],
                                'worklocation'=>$request->input('worklocation')[$item],
                                'performance'=>$request->input('performance')[$item],
                                'president'=>$request->input('president')[$item],
                                'manager'=>$request->input('manager')[$item],
                                'company'=>$request->input('company')[$item],
                                'keypass'=>$request->input('key')[$item],
                                'amount'=>$request->input('amount')[$item],
                                'gender'=>$request->input('gender')[$item],
                                'type'=>'PKBNK',
                                'created_by' => Auth::user()->id,
                            );
                            // Pis::insert($data2);
                            $response = Http::withHeaders(['Authorization' => env('ENV_TOKEN'),])->post('http://satria2.patria.co.id/satria-api-man/public/api/performance-store',$data2);
                            // $data = json_decode($response,true);
                            // return $data;
                        }
                    }
                    return redirect('user-performance')->with('suc_message', 'Data berhasil ditambahkan!');
                
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }
}
