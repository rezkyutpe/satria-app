<?php

namespace App\Http\Controllers\Cms\Tsm;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

use App\Models\View\Tsm\vw_count_sn;
use App\Models\View\Tsm\vw_list_plant;
use App\Models\Table\Tsm\Population;
use App\Models\Table\Tsm\Area;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('tsm') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access TSM denied!');
            }
            return $next($request);
        });
    }

    
    public function Dashboard()
    {
        try{ 
            if(isset($this->PermissionActionMenu('tsm')->v) AND $this->PermissionActionMenu('tsm')->v==1){
                $data = array(
                    'title' => 'Dashboard',
                    'CountPopulation' => count(Population::select('*')->get()),
                    'CountJakarta' => count(Population::select('*')->where('plant','=','Jakarta')->get()),
                    'CountKalsel' => count(Population::select('*')->where('plant','=','Kalsel')->get()),
                );
                return view('tsm/dashboard')->with('data', $data);;
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }


    public function VwCountSN()
    {
        return response()->json([
            'CountSN' => vw_count_sn::all(),
        ], 200);
        
    }

    public function VwListPlant()
    {
        return response()->json([
            'ListPlant' => vw_list_plant::all(),
        ], 200);

    }
}
