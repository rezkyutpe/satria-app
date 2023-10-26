<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Exception;

class MmsController extends Controller
{
    
    public function index()
    {
        try {
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        if(auth()->user()->role_id==''){
           $projectUrl =$actual_link ."/satria-mms?redirect=".auth()->user()->email;
        }else{
           $projectUrl =$actual_link ."/satria-mms?redirect=".auth()->user()->email;
        }
           // echo $projectUrl;
        return redirect($projectUrl);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
