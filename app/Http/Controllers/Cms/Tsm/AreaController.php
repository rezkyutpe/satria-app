<?php

namespace App\Http\Controllers\Cms\Tsm;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Exception;

use App\Models\Table\Tsm\Area;


class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('tsm-area') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access TSM denied!');
            }
            return $next($request);
        });
    }

    public function get_last_datetime($data) 
    {
        if (count($data) > 0){
            return date_format(date_create(substr($data[array_key_last(json_decode(json_encode($data),true))]["updated_at"],0,-3)),"Y-m-d H:i:s");
        }else{
            return date('m/d/Y h:i:s a', time()); 
        }
    }

    
    public function Area()
    {
        try{ 
            if(isset($this->PermissionActionMenu('tsm-area')->v) AND $this->PermissionActionMenu('tsm-area')->v==1){
                $data = Area::all();
                $updated_at = $this->get_last_datetime($data);
                $actionmenu = $this->PermissionActionMenu('tsm-area');
                return view('tsm/area', compact('data','updated_at','actionmenu'));
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } 
        catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function ImportArea()
    {
        try{ 
            $response = Http::get('http://10.48.10.61:8009/api/area'); 
            $message = $response["message"];
            if ($response->successful()){
                Area::truncate();
                $data = $response["data"];
                $new_data = [];
                foreach ($data as $data){
                    $new_col_data = [];
                    foreach ($data as $key => $data){
                        if ($key == "created_at"){
                            $new_col_data[$key] = date('Y-m-d H:i:s');
                        }
                        elseif ($key == "updated_at"){
                            $new_col_data[$key] = date('Y-m-d H:i:s');
                        }
                        else{
                            $new_col_data[$key] = $data;
                        }
                    }
                    array_push($new_data, $new_col_data);
                }

                $area = array_chunk($new_data, 5000, true);
                foreach ($area as $area) {
                    Area::insert($area);
                }
                return redirect('tsm-area')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('tsm-area')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }   
    }


}
