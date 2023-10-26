<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Exception;

class EmsController extends Controller
{
    
    public function index()
    {
        try {
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        if(auth()->user()->role_id==''){
           $projectUrl =$actual_link ."/satria-ems-dev/authenticate?email=".auth()->user()->personal_number."&token=".base64_encode(Hash::make(config('auth.token')));
        }else{
           $projectUrl =$actual_link ."/satria-ems-dev/authenticate?email=".auth()->user()->email."&token=".base64_encode(Hash::make(config('auth.token')));
        }
           // echo $projectUrl;
        return redirect($projectUrl);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
