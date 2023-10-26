<?php


namespace App\Http\Controllers\api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\View\VwPermissionAppsMenu;
use Illuminate\Support\Facades\Http;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
    public static function getUserSF($nrp)
    {
        $response = Http::get('http://webportal.patria.co.id/apisunfish/allempMagang.php');
        $arr = json_decode($response,true);
        $datas=array();
        foreach ($arr['emp'] as $key ) {
            if($key['nrp']==$nrp){
                array_push($datas, $key); 
            }
        }
        return $datas[0];
    }
}