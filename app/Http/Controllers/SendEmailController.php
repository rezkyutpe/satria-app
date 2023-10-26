<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class SendEmailController extends Controller
{
    function index()
    {
     return view('send_email');
    }

    function send()
    {
     // $this->validate($request, [
     //  'name'     =>  'required',
     //  'subject'     =>  'required',
     //  'email'  =>  'required|email',
     //  'message' =>  'required',
     // ]);

        $data = array(
            'name'      =>  'Rezky',
            'subject'      =>  'Test',
            'email'      =>  'mchrezky@gmail.com',
            'message'   =>   'Test email'
        );

     Mail::to('mochr.patria@gmail.com')->send(new SendMail($data));
     return "Thanks for contacting us!";

    }
}
