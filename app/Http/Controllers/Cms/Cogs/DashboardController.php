<?php

namespace App\Http\Controllers\Cms\Cogs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\View\Cogs\vwAPCR;
use Exception;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access COGS denied!');
            }
            return $next($request);
        });
    }

    
    public function Dashboard()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs')->v) AND $this->PermissionActionMenu('cogs')->v==1){
                $data = array(
                    'title' => 'Dashboard',
                    'CountAPCR' => count(vwAPCR::select('*')->where('Status','=','Open')->get()),
                    'CountCreatedCOGS' => count(vwAPCR::select('*')->where('Status','=','Open')->whereNotNull('COGSID')->get()),
                    'CountNotCreatedCOGS' => count(vwAPCR::select('*')->where('Status','=','Open')->whereNull('COGSID')->get()),
                );
                return view('cogs/dashboard')->with('data', $data);
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

}
