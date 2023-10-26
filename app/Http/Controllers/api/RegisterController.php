<?php
   
namespace App\Http\Controllers\api;
   
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $url="http://10.2.49.6/sfapi/index.cfm?endpoint=%2Fpatria_SF_EO_authUser&_=1534382967240";
        $linkurl = curl_init($url);
        curl_setopt($linkurl, CURLOPT_HTTPHEADER, array(
            "X-SFAPI-Account:patria",
            "X-SFAPI-UserName:".$request->email,
            "X-SFAPI-UserPass:".$request->password
        ));
        curl_setopt($linkurl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($linkurl);
        $sf6decoded = json_decode($data);
        // print_r($sf6decoded);
        $user = User::where('email', $request->email)->first();
        if(empty($user)){
            if ($sf6decoded->STATUS==true) {
                $data_sf = $this->getUserSF($request->email);
                User::create([
                    'name' => $data_sf['nama'],
                    'email' => $request->email,
                    'phone' => $data_sf['phone'],
                    'email_sf' => $data_sf['email'],
                    'department' => $data_sf['department'],
                    'division' => $data_sf['Division'],
                    'title' => $data_sf['tittle'],
                    'company_name' => $data_sf['company_name'],
                    'password' => Hash::make($request->password),
                ]);  
            }else{
                echo "gagal";
            }
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
                    $success['phone'] =  $user->phone;
                    $success['email_sf'] =  $user->email_sf;
                    $success['department'] =  $user->department;
                    $success['division'] =  $user->division;
                    $success['title'] =  $user->title;
                    $success['company_name'] =  $user->company_name;
            $success['is_blocked'] =  $user->is_blocked;
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            User::where('email', $request->email)
              ->update([
                    'token' => $success['token'],
                  ]
                );
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Invalid Username or Password!', ['error'=>'Unauthorised']);
        } 
    }
    public function show($id)
    {
        $user = User::where('token', $id)->first();
  
        if (is_null($user)) {
            return $this->sendError('User not found.');
        }
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $success['phone'] =  $user->phone;
        $success['email_sf'] =  $user->email_sf;
        $success['department'] =  $user->department;
        $success['division'] =  $user->division;
        $success['title'] =  $user->title;
        $success['company_name'] =  $user->company_name;
        $success['is_blocked'] =  $user->is_blocked;
        $success['created_at'] =  $user->created_at;
        $success['updated_at'] =  $user->updated_at;
        $success['token'] =  $user->token;
        // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        return $this->sendResponse($success, 'User login successfully.');
    }
}