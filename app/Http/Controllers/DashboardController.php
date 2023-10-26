<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Table\Elsa\InventoryRequest;
use App\Models\Table\Elsa\Ticket;
use App\Models\Table\Elsa\UsingAsset;
use App\Models\View\Elsa\VwTicket;
use App\Models\View\Elsa\VwTicketSap;
use App\Models\View\Elsa\VwPr;
use App\Models\View\Elsa\VwInventory;
use App\Models\Table\Elsa\MstSla;
use App\Models\UserRoleGroup;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\View\VwErrorLogs;
use Carbon\Carbon;
use App\Models\ErrorLogs;
use PDF;
use Exception;
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
          $this->middleware(function ($request, $next) {
            
            
            if ($this->PermissionMenu('ticket-management') == 0){
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        try{
            $start=Carbon::now()->startOfWeek();
            $end= Carbon::now()->endOfWeek();
            $openticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereIn('flag',[1])->where('assist_id',NULL)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->limit(5)->get();
            $resolveticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereIn('flag',[3,5,6])->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
             $prosesticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereIn('flag',[2,3,4,9])->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
            $prosesticketbyassist = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->where('assist_id',Auth::user()->id)->whereIn('flag',[1,2,3,4,9])->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->get();
            $dialyticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
            $dialypagi = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereBetween('created_at',  [date('Y-m-d').' 00:00::00',date('Y-m-d').' 12:00::00'])->orderBy('created_at', 'desc')->count();
            $dialysiang = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereBetween('created_at',  [date('Y-m-d').' 12:00::01',date('Y-m-d').' 16:30::00'])->orderBy('created_at', 'desc')->count();
            $dialymalam = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereBetween('created_at',  [date('Y-m-d').' 16:30::01',date('Y-m-d').' 23:59::59'])->orderBy('created_at', 'desc')->count();
            $topassist = VwTicket::select(\DB::raw("assist_name,assist_photo, assist_id,COUNT(*) as count"))->where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('count', 'desc')->groupBy('assist_id')
                        ->limit(1)->get();
            $topsla = VwTicket::select(\DB::raw("sla_name,sla,COUNT(*) as count"))->where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('count', 'desc')->groupBy('sla')
                        ->limit(1)->get();
            $topreporter = VwTicket::select(\DB::raw("reporter_name,reporter_photo,reporter_nrp,COUNT(*) as count"))->where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('count', 'desc')->groupBy('reporter_nrp')
            ->limit(1)->get();
            $range =$dialypagi.','.$dialysiang.','.$dialymalam;

            $dialypr = VwPr::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->get();
            $weekpr = VwPr::select(\DB::raw("COUNT(*) as count"))->where('dept',Auth::user()->dept)->whereBetween('created_at', [$start,$end])->orderBy('created_at', 'desc')->groupBy(\DB::raw("Day(created_at)"))
                        ->pluck('count');
            $openpr = VwPr::where('dept',Auth::user()->dept)->where('status',0)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->limit(3)->get();
            $new = VwPr::where('dept',Auth::user()->dept)->where('status',0)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->count();
            $acc = VwPr::where('dept',Auth::user()->dept)->where('status',1)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->count();
            $aprv = VwPr::where('dept',Auth::user()->dept)->where('status',2)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->count();
            $deliv = VwPr::where('dept',Auth::user()->dept)->where('status',3)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('created_at', 'desc')->count();
            $rangepr =$new.','.$acc.','.$aprv.','.$deliv;

            $sla = MstSla::where('dept',Auth::user()->dept)->orderBy('name', 'asc')->get();
            $assist = UserRoleGroup::select('user_role_group.*','users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept',Auth::user()->dept)->whereIn('group', [14,16])->get();
            $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('inventory_nama', 'asc')->get();
            $subject = Ticket::select('subject')->where('dept',Auth::user()->dept)->orderBy('subject', 'asc')->groupBy('subject')->get();
            $sapticket = VwTicketSAP::get();
            $sapticketdate = [];
            $sapticketvalue = [];
            foreach ($sapticket as $key) {
                array_push($sapticketdate,$key->date);
                array_push($sapticketvalue,$key->value);
            }
        	$data = array(
                'dialyticket' => $dialyticket,
                'prosesticketbyassist' => $prosesticketbyassist,
                'dialyrange' => $range,
                'rangepr' => $rangepr,
                'openpr' => $openpr,
                'dialypr' => $dialypr,
                'ticketpercentage' => count($resolveticket) ? (count($resolveticket)/count($dialyticket))*100 :0,
                'openticket' => $openticket,
                'inventory' => $inventory,
                'sla' => $sla,
                'assist' => $assist,
                'topassist' => $topassist,
                'topsla' => $topsla,
                'topreporter' => $topreporter,
                'subject' => $subject,
                'sapticketdate' => $sapticketdate,
                'sapticketvalue' => $sapticketvalue,
            );
            return view('dashboard')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function getDashboard()
    {
        $start=Carbon::now()->startOfWeek();
        $end= Carbon::now()->endOfWeek();
        $openticket = VwTicket::where('dept',Auth::user()->dept)->whereIn('flag',[1])->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->limit(3)->get();
        $prosesticket = VwTicket::where('dept',Auth::user()->dept)->whereIn('flag',[2,3,4,9])->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
        $prosesticketbyassist = VwTicket::where('dept',Auth::user()->dept)->where('assist_id',Auth::user()->id)->whereIn('flag',[2,3,4,9])->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
        $dialyticket = VwTicket::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
        $dialypagi = VwTicket::where('dept',Auth::user()->dept)->whereBetween('created_at',  [date('Y-m-d').' 00:00::00',date('Y-m-d').' 12:00::00'])->orderBy('created_at', 'desc')->count();
        $dialysiang = VwTicket::where('dept',Auth::user()->dept)->whereBetween('created_at',  [date('Y-m-d').' 12:00::01',date('Y-m-d').' 16:30::00'])->orderBy('created_at', 'desc')->count();
        $dialymalam = VwTicket::where('dept',Auth::user()->dept)->whereBetween('created_at',  [date('Y-m-d').' 16:30::01',date('Y-m-d').' 23:59::59'])->orderBy('created_at', 'desc')->count();
        $topassist = VwTicket::select(\DB::raw("assist_name,assist_photo, assist_id,COUNT(*) as count"))->where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('count', 'desc')->groupBy('assist_id')
                    ->limit(1)->get();
        $topsla = VwTicket::select(\DB::raw("sla_name,sla,COUNT(*) as count"))->where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('count', 'desc')->groupBy('sla')
                    ->limit(1)->get();
        $topreporter = VwTicket::select(\DB::raw("reporter_name,reporter_photo,reporter_nrp,COUNT(*) as count"))->where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m'). "%")->orderBy('count', 'desc')->groupBy('reporter_nrp')
        ->limit(1)->get();
        $range =$dialypagi.','.$dialysiang.','.$dialymalam;

        $dialypr = VwPr::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->get();
        $weekpr = VwPr::select(\DB::raw("COUNT(*) as count"))->where('dept',Auth::user()->dept)->whereBetween('created_at', [$start,$end])->orderBy('created_at', 'desc')->groupBy(\DB::raw("Day(created_at)"))
                    ->pluck('count');
        $openpr = VwPr::where('dept',Auth::user()->dept)->where('status',0)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->limit(3)->get();
        $new = VwPr::where('dept',Auth::user()->dept)->where('status',0)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->count();
        $acc = VwPr::where('dept',Auth::user()->dept)->where('status',1)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->count();
        $aprv = VwPr::where('dept',Auth::user()->dept)->where('status',2)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->count();
        $deliv = VwPr::where('dept',Auth::user()->dept)->where('status',3)->where('created_at', 'like', "%" . date('Y'). "%")->orderBy('created_at', 'desc')->count();
        $rangepr =$new.','.$acc.','.$aprv.','.$deliv;

        $sla = MstSla::where('dept',Auth::user()->dept)->orderBy('name', 'asc')->get();
        $assist = UserRoleGroup::select('user_role_group.*','users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept',Auth::user()->dept)->whereIn('group', [14,16])->get();
        $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('inventory_nama', 'asc')->get();
        $sapticket = VwTicketSAP::get();
        $sapticketdate = [];
        $sapticketvalue = [];
        foreach ($sapticket as $key) {
            array_push($sapticketdate,$key->date);
            array_push($sapticketvalue,$key->value);
        }
        $data = array(
            // 'dialyticket' => $dialyticket,
            // 'prosesticketbyassist' => $prosesticketbyassist,
            // 'dialyrange' => $range,
            // 'rangepr' => $rangepr,
            // 'openpr' => $openpr,
            // 'dialypr' => $dialypr,
            // 'ticketpercentage' => count($prosesticket) ? (count($prosesticket)/count($dialyticket))*100 :0,
            // 'openticket' => $openticket,
            // 'inventory' => $inventory,
            // 'sla' => $sla,
            // 'assist' => $assist,
            // 'topassist' => $topassist,
            // 'topsla' => $topsla,
            // 'topreporter' => $topreporter,

                'sapticketdate' => $sapticketdate,
                'sapticketvalue' => $sapticketvalue,
        );
        return response()->json($data);
    }
    public function resolvepercentage()
    {
        $prosesticket = VwTicket::where('dept',Auth::user()->dept)->whereIn('flag',[2,3,4,9])->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
        $dialyticket = VwTicket::where('dept',Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m-d'). "%")->orderBy('created_at', 'desc')->get();
        
        $data = array(
            'ticketpercentage' => count($prosesticket) ? (count($prosesticket)/count($dialyticket))*100 :0,
        );
        return response()->json($data);
    }
    public function satriadashboard(Request $request)
    {
        try{
            $errorlogs = VwErrorLogs::orderBy('created_at', 'desc')->limit(10)->get();
            $ticket = VwTicket::orderBy('created_at', 'desc')->get();
            $sapticket = VwTicketSAP::get();
            $sapticketdate = [];
            $sapticketvalue = [];
            foreach ($sapticket as $key) {
                array_push($sapticketdate,$key->date);
                array_push($sapticketvalue,$key->value);
            }
            $data = array(
                'errorlogs' => $errorlogs,
                'ticket' => $ticket,
                'sapticket' => $sapticket,
                'sapticketdate' => $sapticketdate,
                'sapticketvalue' => $sapticketvalue,

            );
            return view('dashboard/satria-admin-dashboard')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function getDetailLogs($id){
        try{
            $logs = VwErrorLogs::where('id', $id)->first();
      
            $data = array(
                'logs' => $logs,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    
    function generateEletterPerformance(Request $request) {
        try{
            $res = $this->getPerformance(Auth::user()->email,$request->keypassword,'PKBNK',$request->year);
            if(!empty($res)){
                if($res['message']==1){
                $data = array(
                    'eletter' => $res['data'],
                );
                $pdf = PDF::loadView('performance-document', $data);
                $pdf_pass = $pdf->setEncryption($request->keypassword);
                return $pdf->stream('document.pdf');   
                }else{
                    return redirect()->back()->with('err_message', $res['message']);
                }     
            }else{
                return redirect()->back()->with('err_message', 'Eletter Not Found');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }  
    }
    function generateEletterCompetence(Request $request) {
        try{
            $res = $this->getPerformance(Auth::user()->email,$request->keypassword,'SKGOL',$request->year);
            if(!empty($res)){
                if($res['message']==1){
                $data = array(
                    'eletter' => $res['data'],
                );
                $pdf = PDF::loadView('competence-document', $data);
                $pdf_pass = $pdf->setEncryption($request->keypassword);
                return $pdf->stream('competence_'.Auth::user()->email.'_'.date('YmdHis').'.pdf');   
                }else{
                    return redirect()->back()->with('err_message', $res['message']);
                }     
            }else{
                return redirect()->back()->with('err_message', 'Eletter Not Found');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }  
    }
}