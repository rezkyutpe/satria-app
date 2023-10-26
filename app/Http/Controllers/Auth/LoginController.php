<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Exception;

class LoginController extends Controller
{

    protected $maxAttempts = 3; 
    protected $decayMinutes = 2; 
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function authenticate(Request $request){

         try {
        
        $res = $this->LoginSF($request->email,$request->password);
        // $credentials = $request->only('email', 'password');
        if(isset($res['data'])){
        	$credentials = array(
		        'email'=>$res['data']['email'],
		        'password'=>$request->password
		    );
            if (Auth::attempt($credentials)) {
                $this->LogLogin('SUCC_LOGIN','Login Success','Login Success by '.$request->email,$request->email);
                return redirect('home');
            }
            $this->LogLogin('ERR_LOGIN','Login Error','Login Error SF1SA0 by '.$request->email,$request->email);
            return redirect('login')->with('err_message', 'Invalid Username or Password!');
        }else{
            $credentials = array(
                'email'=>$request->email,
                'password'=>$request->password
            );
            if (Auth::attempt($credentials)) {
                $this->LogLogin('SUCC_LOGIN','Login Satria Success','Login Success by '.$request->email,$request->email);
                return redirect('home');
            }else{
                $user = User::where('personal_number',$request->email)->first();
                if(!empty($user)){
	                $credentials = array(
		                'email'=> $user->email,
		                'password'=>$request->password
	                );
	                if (Auth::attempt($credentials)) {
	                    $this->LogLogin('SUCC_LOGIN','Login Satria Success','Login Success by '.$request->email,$request->email);
	                    return redirect('home');
	                }
                }
            }
            $this->LogLogin('ERR_LOGIN','Login Error',$res['message'].' '.$request->email,$request->email);
            return redirect('login')->with('err_message', $res['message']);
        }
     } catch (Exception $e) {
           $this->ErrorLogLogin($e,$request->email);
            return redirect('login')->with('err_message', 'Exception Error');
        }
    }
}
