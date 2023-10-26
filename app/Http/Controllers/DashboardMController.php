<?php

namespace App\Http\Controllers;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ErrorLogs;
use Illuminate\Http\Request;
use App\Exports\TicketExport;
use App\Models\UserRoleGroup;
use App\Models\View\Elsa\VwPr;
use App\Models\View\VwErrorLogs;
use App\Models\Table\Elsa\MstSla;
use App\Models\Table\Elsa\Ticket;
use App\Models\View\Elsa\VwTicket;
use App\Models\View\Elsa\VwTicketHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Table\Elsa\UsingAsset;
use App\Models\View\Elsa\VwInventory;
use Illuminate\Support\Facades\Route;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Elsa\InventoryRequest;

class DashboardMController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('ticket-management') == 0) {
                // $cekhead = User::where('email', Auth::user()->email)->whereRaw("title REGEXP 'Department Head| Division Head| Chief|Director'")->first();
                // if (!empty($cekhead)) {
                //     return redirect('dashboard-head');
                // }
                // return redirect()->back()->with('err_message', 'Akses Ditolak!');
                return redirect('dashboard-user');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // try {
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::now()->endOfWeek(Carbon::FRIDAY);
        $tanggal = VwTicket::whereBetween('created_at', [$start, $end])->get();
        // dd($tanggal);

        #Trends 
        $topassist = VwTicket::select(DB::raw("assist_name,assist_photo, assist_id,COUNT(assist_id) as count"))->where('dept', Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('count', 'desc')->groupBy('assist_id')->limit(1)->get();
        $topsla = VwTicket::select(DB::raw("sla_name,sla,COUNT(sla) as count"))->where('dept', Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('count', 'desc')->groupBy('sla')->limit(1)->get();
        $topreporter = VwTicket::select(DB::raw("reporter_name,reporter_photo,reporter_nrp,COUNT(reporter_nrp) as count"))->where('dept', Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('count', 'desc')->groupBy('reporter_nrp')->limit(1)->get();
        $dialyticket = VwTicket::where('dept', Auth::user()->dept)->where('created_at', 'like', "%" . date('Y-m-d') . "%")->orderBy('created_at', 'desc')->get();

        $prosesticketbyassist = VwTicket::where('dept', Auth::user()->dept)->where('assist_id', Auth::user()->id)->whereIn('flag', [1, 2, 3, 4, 9])->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('created_at', 'desc')->get();
        // dd($prosesticketbyassist);

        #Ticket Requested Bar Chart
        $ticketreq = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(DATE(created_at)) as jml_ticket'))->where('dept', Auth::user()->dept)->orderBy('created_at', 'DESC')->groupBy('date')->limit(10)->get();
        $newtr = [];
        if ($ticketreq) {
            foreach ($ticketreq as $tr) {
                $newtr[] = $tr->jml_ticket;
            }
        }
        $newtr2 = json_encode(array_reverse($newtr));

        $ticketreqdate = VwTicket::select(DB::raw('DATE(created_at) as date'))->where('dept', Auth::user()->dept)->orderBy('created_at', 'DESC')->groupBy('date')->limit(10)->get();
        $newtrd = [];
        if ($ticketreqdate) {
            foreach ($ticketreqdate as $trd) {
                $newtrd[] = $trd->date;
            }
        }
        $newtrd2 = array_reverse($newtrd);
        // dd($ticketreqdate);

        #Your Ticket Assigned
        $ticketassign = VwTicket::where('dept', Auth::user()->dept)->where('assist_id', Auth::user()->id)->whereIn('flag', [1, 2, 3, 4, 9])->where('created_at', 'like', "%" . date('Y') . "%")->orderBy('created_at', 'desc')->get();
        // dd($ticketassign);

        #List Ticket Opened to Maintenance
        $ticketopened = VwTicket::where('dept', Auth::user()->dept)->whereIn('flag', [1])->where('assist_id', NULL)->orderBy('created_at', 'desc')->get();
        // $ticketopened = VwTicket::where('dept', Auth::user()->dept)->whereIn('flag', [1])->where('assist_id', NULL)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('created_at', 'desc')->get();
        // dd($ticketopened);

        #All Ticket Assigned
        $allticketassign = VwTicket::where('dept', Auth::user()->dept)->where('assist_id','!=', NULL)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('created_at', 'desc')->get();
        // dd($allticketassign);

        #Ticket Total Bar Chart
        $barticketcreated = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(ticket_id) as jml_ticket'))->where('dept', Auth::user()->dept)->orderBy('created_at', 'DESC')->groupBy('date')->limit(15)->get();
        $newtcbar = [];
        if ($barticketcreated) {
            foreach ($barticketcreated as $btc) {
                $newtcbar[] = $btc->jml_ticket;
            }
        }
        $newtcbar2 = json_encode(array_reverse($newtcbar));

        $barticketresolved = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(ticket_id) as jml_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Resolved')->orderBy('created_at', 'DESC')->groupBy('date')->limit(15)->get();
        $barticketclosed = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(ticket_id) as jml_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->orderBy('created_at', 'DESC')->groupBy('date')->limit(15)->get();
        $newtrbar = [];
        if ($barticketresolved) {
            foreach ($barticketresolved as $btr) {
                $newtrbar[] = $btr->jml_ticket;
            }
        }

        // dd($barticketclosed);
        // $newtrbar2 = json_encode(array_reverse($newtrbar));
        // dd($barticketresolved);

        $ticketcreateddate = VwTicket::select(DB::raw('DATE(created_at) as date'))->where('dept', Auth::user()->dept)->orderBy('created_at', 'DESC')->groupBy('date')->limit(15)->get();
        $newtcd = [];
        if ($ticketcreateddate) {
            foreach ($ticketcreateddate as $tcd) {
                $newtcd[] = $tcd->date;
            }
        }
        $newtcd2 = array_reverse($newtcd);

        $ticketresolveddate = VwTicket::select(DB::raw('DATE(resolve_time) as date'))->where('dept', Auth::user()->dept)->where('flow_name', 'Resolved')->orderBy('resolve_time', 'DESC')->groupBy('date')->limit(15)->get();
        $newtsolveddate = [];
        if ($ticketresolveddate) {
            foreach ($ticketresolveddate as $tsolveddate) {
                $newtsolveddate[] = $tsolveddate->date;
            }
        }
        $newtsolveddate2 = array_reverse($newtsolveddate);
        // dd($newtsolveddate2);

        // Bar Chart Total Ticket Resolved
        $newtrbar3 = [];
        foreach ($newtcd as $ntcd) {
            $true = 0;
            foreach ($barticketresolved as $newbtr) {
                if ($newbtr->date == $ntcd) {
                    $true = 1;
                    $newtrbar3[] = $newbtr->jml_ticket;
                }
            }

            if ($true == 0) {
                $newtrbar3[] = 0;
            }
        }

        // Bar Chart Total Ticket Closed
        // $barticketclosed = VwTicket::join('history', 'vw_ticket.id', '=', 'history.id_ticket')->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->where('history.order', '5')->groupBy('date')->orderBy('date', 'DESC')->select(DB::raw('DATE(history.created_at) as date, count(ticket_id) as jml_ticket'))->limit(15)->get();  

        $newtclbar3 = [];
        foreach ($newtcd as $ntcd) {
            $true = 0;
            foreach ($barticketclosed as $newbtcl) {
                if ($newbtcl->date == $ntcd) {
                    $true = 1;
                    $newtclbar3[] = $newbtcl->jml_ticket;
                }
            }

            if ($true == 0) {
                $newtclbar3[] = 0;
            }
        }
        $newtrclbar = [];
        for($i=0; $i<count($newtrbar3);$i++){
            $newtrclbar[] = $newtrbar3[$i]+$newtclbar3[$i];
        }
        $newtrclbar2 = json_encode(array_reverse($newtrclbar));
        

        // dd($newtrbar3, $newtclbar3, $newtrclbar, $barticketresolved);
        $newtrbar2 = json_encode(array_reverse($newtrbar3));

        // dd($newtrclbar, $newtrbar3, $newtclbar3, $newtrbar2);

        #Ticket Total Pie Chart
        $firsttcd = reset($newtcd2);
        $lasttcd = end($newtcd2);

        $tickettotalcreated = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        $tickettotalopened = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Opened')->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        $tickettotalinprogress = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'In Progress')->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        $tickettotalresolved = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Resolved')->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        $tickettotalclosed = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        // $tickettotalclosed = VwTicket::join('history', 'vw_ticket.id', '=', 'history.id_ticket')->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->where('history.order', '5')->whereBetween('history.created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->select(DB::raw('count(ticket_id) as total_ticket'))->get();
        $tickettotalcanceled = VwTicket::select(DB::raw('count(vw_ticket.ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Canceled')->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        $tickettotalescalated = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Escalated')->whereBetween('created_at', [$firsttcd . " 00:00:00", $lasttcd . " 23:59:59"])->get();
        $newttc = $tickettotalcreated->toArray();
        $newtto = $tickettotalopened->toArray();
        $newtti = $tickettotalinprogress->toArray();
        $newttr = $tickettotalresolved->toArray();
        $newttcl = $tickettotalclosed->toArray();
        $newttca = $tickettotalcanceled->toArray();
        $newtte = $tickettotalescalated->toArray();
        $ttc = $newttc[0]['total_ticket'];
        $tto = $newtto[0]['total_ticket'];
        $tti = $newtti[0]['total_ticket'];
        $ttr = $newttr[0]['total_ticket'];
        $ttcl = $newttcl[0]['total_ticket'];
        $ttca = $newttca[0]['total_ticket'];
        $tte = $newtte[0]['total_ticket'];
        $newttc2 = json_encode($ttc);
        $newtto2 = json_encode($tto);
        $newtti2 = json_encode($tti);
        $newttr2 = json_encode($ttr);
        $newttcl2 = json_encode($ttcl);
        $newttca2 = json_encode($ttca);
        $newtte2 = json_encode($tte);
        // dd($newttc2);

        $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
        $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
        $assist2 = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
        $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
        $subject = Ticket::select('subject')->where('dept', Auth::user()->dept)->orderBy('subject', 'asc')->groupBy('subject')->get();
        $subjectexport = Ticket::select('subject')->where('dept', Auth::user()->dept)->orderBy('subject', 'asc')->groupBy('subject')->get();

        // dd($subject);

        // dd($ticketreqdate);
        // dd($dialyticket);
        return view('dashboard-maintenance', compact('topassist', 'newtr2', 'newtrd2', 'newtto2', 'newtti2', 'newttca2', 'newtte2', 'newttcl2', 'newttc2', 'newttr2', 'newtcbar2', 'newtrbar2', 'newtrclbar2','newtcd2', 'ticketassign', 'ticketopened', 'allticketassign', 'topsla', 'topreporter', 'prosesticketbyassist', 'sla', 'assist', 'assist2', 'inventory', 'subject', 'subjectexport'));
        // } catch (Exception $e) {
        //     $this->ErrorLog($e);
        //     return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        // }
    }

    public function FilterTicketReq(Request $request)
    {
        $ticketreq = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(DATE(created_at)) as jml_ticket'))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_tr . " 00:00:00", $request->End_tr . " 23:59:59"])->orderBy('created_at', 'DESC')->groupBy('date')->get();
        $newtr = [];
        if ($ticketreq) {
            foreach ($ticketreq as $tr) {
                $newtr[] = $tr->jml_ticket;
            }
        }
        $newtr2 = array_reverse($newtr);
        // dd($newtr2);

        $ticketreqdate = VwTicket::select(DB::raw('DATE(created_at) as date'))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_tr . " 00:00:00", $request->End_tr . " 23:59:59"])->orderBy('created_at', 'DESC')->groupBy('date')->get();
        $newtrd = [];
        if ($ticketreqdate) {
            foreach ($ticketreqdate as $trd) {
                $newtrd[] = $trd->date;
            }
        }

        $newtrd2 = array_reverse($newtrd);
        $data = [
            'newtr2' => $newtr2,
            'newtrd2' => $newtrd2,
        ];
        echo json_encode($data);
        // dd($data, json_encode($data), $data['newtr2'][1]);
        // dd($request->all());
    }

    public function FilterYourTicketReq(Request $request)
    {
        $ticketassign = VwTicket::select(DB::raw('*,DATE_FORMAT(created_at, "%H:%i:%s, %d %b %Y ") as date, resolve_time as resolve_flag'))->where('dept', Auth::user()->dept)->where('assist_id', Auth::user()->id)->whereBetween('created_at', [$request->Start_yta . " 00:00:00", $request->End_yta . " 23:59:59"])->orderBy('created_at', 'desc')->get();

        foreach($ticketassign as $ta){
            $ta->resolve_flag = $this->TimeInterval(date('Y-m-d H:i:s'), $ta->resolve_time);
        }

        // dd($ticketassign);
        echo json_encode($ticketassign);
        // dd($ticketassign);
    }

    public function FilterListTicketOpen(Request $request)
    {
        $ticketopened = VwTicket::select(DB::raw('*,DATE_FORMAT(created_at, "%H:%i:%s, %d %b %Y ") as date'))->where('dept', Auth::user()->dept)->whereIn('flag', [1])->where('assist_id', NULL)->whereBetween('created_at', [$request->Start_lto . " 00:00:00", $request->End_lto . " 23:59:59"])->orderBy('created_at', 'desc')->get();

        // dd($ticketassign);
        echo json_encode($ticketopened);
        // dd($ticketassign);
    }

    public function FilterAllTicketAssign(Request $request)
    {
        $allticketassign = VwTicket::select(DB::raw('*,DATE_FORMAT(created_at, "%H:%i:%s, %d %b %Y ") as date'))->where('assist_id','!=',NULL)->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_ata . " 00:00:00", $request->End_ata . " 23:59:59"])->orderBy('created_at', 'desc')->get();

        // dd($ticketassign);
        echo json_encode($allticketassign);
        // dd($ticketassign);
    }

    public function FilterTrends(Request $request)
    {
        // dd($request->Start_trends);
        $topassist = VwTicket::select(DB::raw("assist_name,assist_photo, assist_id,COUNT(assist_id) as count"))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_trends . " 00:00:00", $request->End_trends . " 23:59:59"])->orderBy('count', 'desc')->groupBy('assist_id')->limit(1)->get();
        $topsla = VwTicket::select(DB::raw("sla_name,sla,COUNT(sla) as count"))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_trends . " 00:00:00", $request->End_trends . " 23:59:59"])->orderBy('count', 'desc')->groupBy('sla')->limit(1)->get();
        $topreporter = VwTicket::select(DB::raw("reporter_name,reporter_photo,reporter_nrp,COUNT(reporter_nrp) as count"))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_trends . " 00:00:00", $request->End_trends . " 23:59:59"])->orderBy('count', 'desc')->groupBy('reporter_nrp')->limit(1)->get();

        $data = [
            'topassist' => $topassist,
            'topsla' => $topsla,
            'topreporter' => $topreporter
        ];

        echo json_encode($data);
    }

    public function FilterTicketTotal(Request $request)
    {
        #Ticket Total Bar Chart
        $barticketcreated = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(ticket_id) as jml_ticket'))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->orderBy('created_at', 'DESC')->groupBy('date')->get();
        $newtcbar = [];
        if ($barticketcreated) {
            foreach ($barticketcreated as $btc) {
                $newtcbar[] = $btc->jml_ticket;
            }
        }
        $newtcbar2 = array_reverse($newtcbar);

        $barticketresolved = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(ticket_id) as jml_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Resolved')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->orderBy('created_at', 'DESC')->groupBy('date')->get();
        $barticketclosed = VwTicket::select(DB::raw('DATE(created_at) as date, COUNT(ticket_id) as jml_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->orderBy('created_at', 'DESC')->groupBy('date')->get();
        $newtrbar = [];
        if ($barticketresolved) {
            foreach ($barticketresolved as $btr) {
                $newtrbar[] = $btr->jml_ticket;
            }
        }

        $ticketcreateddate = VwTicket::select(DB::raw('DATE(created_at) as date'))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->orderBy('created_at', 'DESC')->groupBy('date')->get();
        $newtcd = [];
        if ($ticketcreateddate) {
            foreach ($ticketcreateddate as $tcd) {
                $newtcd[] = $tcd->date;
            }
        }
        $newtcd2 = array_reverse($newtcd);

        // Bar Chart Total Ticket Resolved
        $newtrbar3 = [];
        foreach ($newtcd as $ntcd) {
            $true = 0;
            foreach ($barticketresolved as $newbtr) {
                if ($newbtr->date == $ntcd) {
                    $true = 1;
                    $newtrbar3[] = $newbtr->jml_ticket;
                }
            }

            if ($true == 0) {
                $newtrbar3[] = 0;
            }
        }
        $newtrbar2 = array_reverse($newtrbar3);

        // Bar Chart Total Ticket Closed
        // $barticketclosed = VwTicket::join('history', 'vw_ticket.id', '=', 'history.id_ticket')->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->where('history.order', '5')->whereBetween('history.created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->groupBy('date')->orderBy('date', 'DESC')->select(DB::raw('DATE(history.created_at) as date, count(ticket_id) as jml_ticket'))->limit(15)->get();

        $newtclbar3 = [];
        foreach ($newtcd as $ntcd) {
            $true = 0;
            foreach ($barticketclosed as $newbtcl) {
                if ($newbtcl->date == $ntcd) {
                    $true = 1;
                    $newtclbar3[] = $newbtcl->jml_ticket;
                }
            }

            if ($true == 0) {
                $newtclbar3[] = 0;
            }
        }
        $newtrclbar = [];
        for($i=0; $i<count($newtrbar3);$i++){
            $newtrclbar[] = $newtrbar3[$i]+$newtclbar3[$i];
        }
        $newtrclbar2 = array_reverse($newtrclbar);
        // dd($newtrclbar, $newtrbar3, $newtclbar3, $newtcd, $barticketclosed);

        #Ticket Total Pie Chart
        $tickettotalcreated = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        $tickettotalopened = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Opened')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        $tickettotalinprogress = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'In Progress')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        $tickettotalresolved = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Resolved')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        $tickettotalclosed = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        // $tickettotalclosed = VwTicket::join('history', 'vw_ticket.id', '=', 'history.id_ticket')->where('dept', Auth::user()->dept)->where('flow_name', 'Closed')->where('history.order', '5')->whereBetween('history.created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->select(DB::raw('count(ticket_id) as total_ticket'))->get();
        $tickettotalcanceled = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Canceled')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        $tickettotalescalated = VwTicket::select(DB::raw('count(ticket_id) as total_ticket'))->where('dept', Auth::user()->dept)->where('flow_name', 'Escalated')->whereBetween('created_at', [$request->Start_totalticket . " 00:00:00", $request->End_totalticket . " 23:59:59"])->get();
        $newttc = $tickettotalcreated->toArray();
        $newtto = $tickettotalopened->toArray();
        $newtti = $tickettotalinprogress->toArray();
        $newttr = $tickettotalresolved->toArray();
        $newttcl = $tickettotalclosed->toArray();
        $newttca = $tickettotalcanceled->toArray();
        $newtte = $tickettotalescalated->toArray();
        $ttc = $newttc[0]['total_ticket'];
        $tto = $newtto[0]['total_ticket'];
        $tti = $newtti[0]['total_ticket'];
        $ttr = $newttr[0]['total_ticket'];
        $ttcl = $newttcl[0]['total_ticket'];
        $ttca = $newttca[0]['total_ticket'];
        $tte = $newtte[0]['total_ticket'];

        $data = [
            'newtcbar2' => $newtcbar2,
            'newtrbar2' => $newtrclbar2,
            'newtcd2' => $newtcd2,
            'newttc2' => $ttc,
            'newtto2' => $tto,
            'newtti2' => $tti,
            'newttr2' => $ttr,
            'newttcl2' => $ttcl,
            'newttca2' => $ttca,
            'newtte2' => $tte,
        ];

        echo json_encode($data);
    }

    public function ExportTicket(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->v == 1) {
                // dd($request->all());
                $start = isset($request->startdate_tickettotal) ? $request->startdate_tickettotal : null;
                $end = isset($request->enddate_tickettotal) ? $request->enddate_tickettotal : null;
                $startdef = isset($request->def_startdate_export) ? $request->def_startdate_export : null;
                $enddef = isset($request->def_enddate_export) ? $request->def_enddate_export : null;
                $subject = isset($request->subject) ? $request->subject : null;
                $nama_file = 'export-ticket-' . "-" . date('dmYHis') . '.xlsx';
                return Excel::download(new TicketExport($start, $end, $startdef, $enddef, $subject), $nama_file);
                // echo $start.$end.$subject;
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
