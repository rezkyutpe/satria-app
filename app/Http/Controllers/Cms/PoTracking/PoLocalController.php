<?php

namespace App\Http\Controllers\Cms\PoTracking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\Ticket;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\ParkingInvoice;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwHistorytotal;
use App\Models\View\PoTracking\VwPoLocalOngoing;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use App\Models\View\CompletenessComponent\VwTotalStock;
use App\Models\View\VwUserRoleGroup;
use PDF;
use DateTime;
use Response;
use Illuminate\Support\Carbon;

use SimpleSoftwareIO\QrCode\Facades\QrCode;


class PoLocalController extends Controller
{
    public $newpo           = 'polocalnewpo';
    public $ongoing         = 'polocalongoing';
    public $planDelivery    = 'plandelivery';
    public $readyToDelivery = 'readydelivery';
    public $reportPO        = 'polocalreport';
    public $historyparking  = 'historyparking';
    public $historyPO       = 'polocalhistory';

    // polocal

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('polocalnewpo') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //Po Local New PO View
    public function polocalnewpo()
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1 || $this->PermissionActionMenu('polocalnewpo')->r==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 || $this->PermissionActionMenu('polocalnewpo')->u==1){
            $header_title                   = "PO LOCAL - New PO";
            $link_search                    = "caripolocalnewpo";
            $link_reset                     = "polocalnewpo";

            $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'PO Local New Po',
                    'date'  => $date->toDateString(),
                    'CreatedBy'  => Auth::user()->name,
                ],
                    ['time'     => $date->toTimeString()
                ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;

            $kodex = ['A','D','S','W','Q','R','X'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1){
                $data               = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'desc')->get();
                $OngoingPolocal     = VwOngoinglocal::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery       = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber')->count();
                $HistoryPolocal     = VwHistoryLocal::where('VendorCode',Auth::user()->email)->select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery    = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->count();

            }else if($actionmenu->u==1){
                $data = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->where('NRP',Auth::user()->email)->count();
                $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('NRP',Auth::user()->email)->where('status','P')->count();
                $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('NRP',Auth::user()->email)->count();
                $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->where('NRP',Auth::user()->email)->whereIn('status',$kodex)->count();

            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $data = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->whereIn('status',$kodex)->count();
            }

            $material_potracking = VwLocalnewpo::select('Material')->distinct()->get()->toArray();
            $ccr_reqdate = VwPoTrackingReqDateMaterial::whereIn('material',$material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
            foreach($data as $a){
                foreach($ccr_reqdate as $b){
                    if($a->Material == $b->material){
                        $a->setAttribute('req_date',$b->req_date);
                        break;
                    }
                    else{
                        continue;
                    }
                }
            }

            $countNewpoPolocal      = $data->count();
            $countOngoingPolocal    = $OngoingPolocal;
            $countHistoryPolocal    = $HistoryPolocal;
            $countplanDelivery      = $planDelivery;
            $countreadyToDelivery   = $readyToDelivery;
            return view('po-tracking/polocal/polocalnewpo',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }
    //Cari Po Local New PO View
    public function caripolocalnewpo($number)
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1 || $this->PermissionActionMenu('polocalnewpo')->r==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 || $this->PermissionActionMenu('polocalnewpo')->u==1){
            $header_title                   = "PO LOCAL - New PO";
            $link_search                    = "caripolocalnewpo";
            $link_reset                     = "polocalnewpo";

            $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'PO Local New Po',
                    'date'  => $date->toDateString(),
                    'CreatedBy'  => Auth::user()->name,
                ],
                    ['time'     => $date->toTimeString()
                ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;

            $kodex = ['A','D','S','W','Q','R','X'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1){
                $data               = VwLocalnewpo::where('VendorCode',Auth::user()->email)->where('Number',$number)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'desc')->get();

                $OngoingPolocal     = VwOngoinglocal::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery       = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber')->count();
                $HistoryPolocal     = VwHistoryLocal::where('VendorCode',Auth::user()->email)->select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery    = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->count();

            }else if($actionmenu->u==1){
                $data = VwLocalnewpo::where('NRP',Auth::user()->email)->where('Number',$number)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->where('NRP',Auth::user()->email)->count();
                $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('NRP',Auth::user()->email)->where('status','P')->count();
                $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('NRP',Auth::user()->email)->count();
                $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->where('NRP',Auth::user()->email)->whereIn('status',$kodex)->count();

            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $data = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->where('Number',$number)->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->whereIn('status',$kodex)->count();
            }

            $material_potracking = VwLocalnewpo::select('Material')->distinct()->get()->toArray();
            $ccr_reqdate = VwPoTrackingReqDateMaterial::whereIn('material',$material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
            foreach($data as $a){
                foreach($ccr_reqdate as $b){
                    if($a->Material == $b->material){
                        $a->setAttribute('req_date',$b->req_date);
                        break;
                    }
                    else{
                        continue;
                    }
                }
            }

            $countNewpoPolocal      = $data->count();
            $countOngoingPolocal    = $OngoingPolocal;
            $countHistoryPolocal    = $HistoryPolocal;
            $countplanDelivery      = $planDelivery;
            $countreadyToDelivery   = $readyToDelivery;
            return view('po-tracking/polocal/polocalnewpo',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }

    // PO Local Ongiong View
    public function polocalongoing()
    {
    if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1  || $this->PermissionActionMenu('polocalnewpo')->r==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 || $this->PermissionActionMenu('polocalnewpo')->u==1){
        $date   = Carbon::now();
        LogHistory::create([
            'user'  => Auth::user()->id,
            'menu'  => 'PO Local Ongoing',
            'date'  => $date->toDateString(),
            'CreatedBy'  => Auth::user()->name,
            'time'  => $date->toTimeString()
        ]);
        $header_title                   = "PO LOCAL - ONGOING";
        $link_search                    = "caripolocalongoing";
        $link_reset                     = "polocalongoing";
        $link_newPO                     = $this->newpo;
        $link_ongoing                   = $this->ongoing;
        $link_planDelivery              = $this->planDelivery;
        $link_readyToDelivery           = $this->readyToDelivery;
        $link_reportPO                  = $this->reportPO;
        $link_historyPO                 = $this->historyPO;
        $link_historyParking                = $this->historyparking;

        $kodex = ['A','D','S','W','Q','R','X'] ;
        $actionmenu =  $this->PermissionActionMenu('polocalnewpo');

     if($actionmenu->c==1){
        $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->count();
        $data  = VwOngoinglocal::select('*')->where('VendorCode',Auth::user()->email)->
        groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
        $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('VendorCode',Auth::user()->email)->where('status','P')->count();
        $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->count();
        $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->count();

    }else if($actionmenu->u==1){
        $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('NRP',Auth::user()->email)->count();
        $data  =  VwOngoinglocal::select('*')->where('NRP',Auth::user()->email)->
        groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
        $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('NRP',Auth::user()->email)->where('status','P')->count();
        $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('NRP',Auth::user()->email)->count();
        $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->where('NRP',Auth::user()->email)->whereIn('status',$kodex)->count();

    }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
        $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
        $data  = VwOngoinglocal::select('*')->
        groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
        $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
        $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
        $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->whereIn('status',$kodex)->count();

    }

    $countNewpoPolocal       = $NewpoPolocal;
    $countOngoingPolocal     = $data->count();
    $countHistoryPolocal     = $HistoryPolocal;
    $countplanDelivery      = $planDelivery;
    $countreadyToDelivery    = $readyToDelivery;
        return view('po-tracking/polocal/polocalongoing',
        compact(
            'data',
            'header_title',
            'link_search',
            'link_reset',
            'link_newPO',
            'link_ongoing',
            'link_planDelivery',
            'link_historyParking',
            'link_readyToDelivery',
            'link_reportPO',
            'link_historyPO',
            'countNewpoPolocal',
            'countOngoingPolocal',
            'countHistoryPolocal',
            'countplanDelivery',
            'countreadyToDelivery',
            'actionmenu'
        ));
    }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
    }
    }
         // Cari PO Local Ongiong View
    public function caripolocalongoing($number)
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1  || $this->PermissionActionMenu('polocalnewpo')->r==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 || $this->PermissionActionMenu('polocalnewpo')->u==1){

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - Ongoing PO',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title                   = "PO LOCAL Ongoing";
            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $link_historyParking            = $this->historyparking;

            $kodex              = ['A','D','S','W','Q','R','X'] ;
            $readyToDelivery    = VwViewTicket::whereIn('Status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
            $actionmenu         =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1){
                $data  = VwOngoinglocal::select('*')->where('VendorCode',Auth::user()->email)->where('Number',$number)->orderBy('Number', 'asc')->get();
                $NewpoPolocal          = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $planDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $HistoryPolocal = VwHistoryLocal::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
            }elseif($actionmenu->u==1){
                $data  = VwOngoinglocal::select('*')->where('NRP',Auth::user()->email)->where('Number',$number)->orderBy('Number', 'asc')->get();
                $NewpoPolocal          = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $planDelivery = VwViewTicket::where('NRP',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber')->orderBy('ItemNumber', 'asc')->get();
                $HistoryPolocal = VwHistoryLocal::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $data   =  VwOngoinglocal::select('*')->where('Number',$number)->orderBy('Number', 'asc')->get();
                $NewpoPolocal          = VwLocalnewpo::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $planDelivery = VwViewTicket::where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $HistoryPolocal = VwHistoryLocal::groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
            }
            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $data->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            return view('po-tracking/polocal/polocalongoing',
            compact(
                'data',
                'header_title',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'link_historyParking',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    //poHIstoryview
    public function Polocalhistory()
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1  || $this->PermissionActionMenu('polocalnewpo')->r==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 || $this->PermissionActionMenu('polocalnewpo')->u==1){

            $header_title                   = "PO LOCAL - PO HISTORY";
            $link_search                    = "caripolocalhistory";
            $link_reset                     = "polocalhistory";

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - History PO',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $link_historyParking            = $this->historyparking;

            $kodex = ['A','D','S','W','Q','R','X'] ;

            $datafinishlocal = VwHistorytotal::all();
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1){
                $NewpoPolocal = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('VendorCode',Auth::user()->email)->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
                $data = VwHistoryLocal::where('VendorCode',Auth::user()->email)->groupBy('DeliveryDate', 'ItemNumber', 'Quantity')->orderBy('id', 'desc')->get();

            }else if($actionmenu->u==1){
                $NewpoPolocal = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('NRP',Auth::user()->email)->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $data = VwHistoryLocal::where('NRP',Auth::user()->email)->groupBy('DeliveryDate', 'ItemNumber', 'Quantity')->orderBy('id', 'desc')->get();
                $planDelivery = VwViewTicket::where('NRP',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();

            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $NewpoPolocal = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
                $data = VwHistoryLocal::groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

            }
            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $data->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view('po-tracking/polocal/pohistory',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_historyParking',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'datafinishlocal',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }


    //PlanDeliveryvIEW
    public function plandelivery()
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1  || $this->PermissionActionMenu('polocalnewpo')->r==1  || $this->PermissionActionMenu('polocalnewpo')->u==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 ){

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $link_search                    = "caripolocalplandelivery";
            $link_reset                     = "plandelivery";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - Plan Delivery',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $link_historyParking            = $this->historyparking;
            $kodex = ['A','D','S','W','Q','R','X'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1){
                $NewpoPolocal = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('VendorCode',Auth::user()->email)->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $HistoryPolocal = VwHistoryLocal::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $readyToDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
                $data = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
            }else if($actionmenu->u==1){
                $NewpoPolocal = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('NRP',Auth::user()->email)->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $HistoryPolocal = VwHistoryLocal::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $data = VwViewTicket::where('NRP',Auth::user()->email)->where('status','P')
                ->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $NewpoPolocal = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $data = VwViewTicket::where('status','P')
                ->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
                $HistoryPolocal = VwHistoryLocal::groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
            }

            $material_potracking    = VwViewTicket::select('Material')->distinct()->get()->toArray();
            $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material',$material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
            $ccr_stock              = VwTotalStock::whereIn('material_number',$material_potracking)->select('material_number','stock')->get();
            foreach($data as $a){
                foreach($ccr_reqdate as $b){
                    if($a->Material == $b->material){
                        $a->setAttribute('req_date',$b->req_date);
                        break;
                    }
                    else{
                        continue;
                    }
                }
                foreach($ccr_stock as $c){
                    if($a->Material == $c->material_number){
                        $a->setAttribute('stock',$c->stock);
                        break;
                    }
                    else{
                        continue;
                    }
                }
            }

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            $datafinishlocal = VwPoLocalOngoing::all();
            return view('po-tracking/polocal/plandelivery',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_historyParking',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'datafinishlocal',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
         // Cari Po Local Plan Delivery
    public function caripolocalplandelivery($number)
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1  || $this->PermissionActionMenu('polocalnewpo')->r==1  || $this->PermissionActionMenu('polocalnewpo')->u==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 ){


            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - Plan Delivery',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $data                           = VwViewTicket::distinct()->where('Number',$number)->where('Status','P')->groupBy('Number','ItemNumber', 'Quantity')->get();
            $header_title                   = "PO LOCAL - Plan Delivery";
            $link_search                    = "caripolocalplandelivery";
            $link_reset                     = "plandelivery";

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $planDelivery = VwViewTicket::where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
            $kodex = ['A','D','S','W','Q','R','X'] ;
            $readyToDelivery = VwViewTicket::whereIn('Status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
            $NewpoPolocal            = VwLocalnewpo::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            $OngoingPolocal          = VwOngoinglocal::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber', 'Quantity')->get();


            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            return view('po-tracking/polocal/plandelivery',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    //ReadytoDeliveryvIEW
    public function readydelivery()
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1  || $this->PermissionActionMenu('polocalnewpo')->r==1 || $this->PermissionActionMenu('polocalnewpo')->u==1 || $this->PermissionActionMenu('polocalnewpo')->v==1 ){

            $header_title                   = "PO LOCAL - READY TO DELIVERY";
            $link_search                    = "caripolocalreadytodelivery";
            $link_reset                     = "readydelivery";

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - Ready to Delivery',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $link_historyParking            = $this->historyparking;

            $kodex = ['A','D','S','W','Q','R','X'] ;

            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1){
                $NewpoPolocal = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('VendorCode',Auth::user()->email)->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->orderBy('ID', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();
            }else if($actionmenu->u==1){
                $NewpoPolocal = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('NRP',Auth::user()->email)->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('NRP',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('status',$kodex)->orderBy('ID', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $NewpoPolocal = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = VwViewTicket::whereIn('status',$kodex)->orderBy('ID', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber')->get();

            }

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $data->count();

            $datafinishlocal = VwPoLocalOngoing::all();

            return view('po-tracking/polocal/readytodelivery',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_historyParking',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'datafinishlocal',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    //HistoryTicketView
    public function Historyticket()
    {
        if($this->PermissionActionMenu('polocalnewpo')->c==1 || $this->PermissionActionMenu('polocalnewpo')->d==1  || $this->PermissionActionMenu('polocalnewpo')->r==1  || $this->PermissionActionMenu('polocalnewpo')->v==1 || $this->PermissionActionMenu('polocalnewpo')->u==1){

            $header_title                   = "PO LOCAL - HISTORY TICKET";
            $link_search                    = "carihistoryticket";
            $link_reset                     = "historyticket";

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - History Ticket',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $link_historyParking            = $this->historyparking;

            $kodex = ['A','D','S','W','Q','R','X'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            if($actionmenu->c==1 || $actionmenu->u==1){
                $kode = ['C','E'] ;
                $NewpoPolocal = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->where('VendorCode',Auth::user()->email)->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = VwViewTicket::whereNotNull('Number')->where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->whereIn('status',$kode)->groupBy('ID','ItemNumber')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $kode = ['D','A','S','W','C','Q','R','X','E'] ;
                $NewpoPolocal = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('*')->
                leftjoin('vw_totalticket', 'vw_ongoinglocal.ID', '=', 'vw_totalticket.PDIID')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = VwViewTicket::whereIn('status',$kodex)->groupBy('DeliveryDate','Number')->orderBy('ID', 'asc')->get();
                $data = VwViewTicket::whereNotNull('Number')->whereIn('status',$kode)->groupBy('ID','ItemNumber')->orderBy('ItemNumber', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();


            $datafinishlocal = VwPoLocalOngoing::all();

            return view('po-tracking/polocal/historyticket',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_historyParking',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'datafinishlocal',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function Historyparking()
    {
        if($this->PermissionActionMenu('polocalnewpo')->r==1  ){

            $header_title                   = "PO LOCAL - HISTORY PARKING";
            $link_search                    = "caripolocalhistoryparking";
            $link_reset                     = "historyparking";

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Local - History Parking',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $link_newPO                     = $this->newpo;
            $link_ongoing                   = $this->ongoing;
            $link_planDelivery              = $this->planDelivery;
            $link_readyToDelivery           = $this->readyToDelivery;
            $link_reportPO                  = $this->reportPO;
            $link_historyPO                 = $this->historyPO;
            $link_historyParking            = $this->historyparking;
            $kodex = ['A','D','S','W','Q','R','X'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
            $data = ParkingInvoice::select('parkinginvoice.*','vw_ongoingall.Vendor','vw_ongoingall.VendorCode','vw_ongoingall.PurchaseOrderCreator','vw_ongoingall.ReleaseDate','vw_ongoingall.NRP')->
            leftjoin('vw_ongoingall', 'vw_ongoingall.Number', '=', 'parkinginvoice.Number')
            ->get();
            $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
            $OngoingPolocal  = VwOngoinglocal::orderBy('Number', 'asc')->count();
            $planDelivery = VwViewTicket::select('Number','ItemNumber')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
            $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
            $readyToDelivery = VwViewTicket::select('DeliveryDate','Number')->distinct('DeliveryDate','Number')->whereIn('status',$kodex)->count();

            $countNewpoPolocal       = $NewpoPolocal;
            $countOngoingPolocal     = $OngoingPolocal;
            $countHistoryPolocal     = $HistoryPolocal;
            $countplanDelivery      = $planDelivery;
            $countreadyToDelivery    = $readyToDelivery;
            $datafinishlocal = VwPoLocalOngoing::all();

            return view('po-tracking/polocal/historyparking',
            compact(
                'data',
                'header_title',
                'link_search',
                'link_reset',
                'link_newPO',
                'link_ongoing',
                'link_planDelivery',
                'link_historyParking',
                'link_readyToDelivery',
                'link_reportPO',
                'link_historyPO',
                'datafinishlocal',
                'countNewpoPolocal',
                'countOngoingPolocal',
                'countHistoryPolocal',
                'countplanDelivery',
                'countreadyToDelivery',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }


    public function view_cekpo(Request $request)
    {
        $datapo = VwOngoinglocal::where('ID', $id)->first();
        $data =  VwOngoinglocal::where('Number', $datapo->Number)->groupBy('Number','ItemNumber','Quantity')->get();

        $data = array(
            'data'        => $data,
            'datapo'        => $datapo,
        );

        echo json_encode($data);
    }
    // PO All- View cari proforma
    public function view_proforma(Request $request)
    {
        $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
        $dataid = VwOngoinglocal::where('ID', $request->id)->first();
        $data =  VwOngoinglocal::where('Number', $dataid->Number)->where('ParentID','!=','null')->groupBy('Number','ItemNumber','Quantity')->get();

        $data = array(
            'data'        => $data,
            'dataid'        => $dataid,
            'actionmenu'        => $actionmenu,
        );

        echo json_encode($data);
    }

    // PO All- View gr
    public function view_detailgr(Request $request)
    {
        $data    = Pdi::where('POID', $request->number)->where('ItemNumber', $request->item)->get();
        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }
    // PO All- View gr History
    public function view_detailgrhistory(Request $request)
    {
        $data    = PdiHistory::where('POID', $request->number)->where('ItemNumber', $request->item)->get();
        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }


    public function view_cekparking(Request $request)
    {
        $data = VwViewTicket::where('ID', $request->id)->groupBy('Number','ItemNumber','ID')->WhereNotIn('status' ,['C'])->orderBy('Status', 'asc')->get();
        $dataviewticket = VwViewTicket::where('ID', $request->id)->first();
        $dataparking = ParkingInvoice::where('ID_ticket', $dataviewticket->TicketID)->get();

        $data = array(
            'dataviewticket' =>$dataviewticket,
            'data' =>$data,
            'dataparking' =>$dataparking,
        );
        echo json_encode($data);
    }
    //cekticket
    public function view_cekticket(Request $request)
    {
        $dataid = VwOngoinglocal::where('ID', $request->id)->first();
        $dataticket = VwOngoinglocal::where('Number', $dataid->Number)->groupBy('Number','ItemNumber')->get();
        if($dataid->ConfirmedItem == 1){
            $dataall = VwOngoinglocal::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->get();
        }else{
            $dataall = VwOngoinglocal::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->groupBy('Number','ItemNumber')->get();
        }

        $data = array(
            'dataid' =>$dataid,
            'dataticket' =>$dataticket,
            'dataall' =>$dataall,
            );
        echo json_encode($data);
    }
      //viewticket
    public function cek_ticket(Request $request)
    {
        $data = DetailTicket::where('ID', $request->id)->groupBy('Number','ItemNumber','ID')->WhereNotIn('status' ,['C'])->orderBy('Status', 'asc')->get();

        $data = array(
            'data' =>$data
        );
        echo json_encode($data);
    }

    public function prosespolocalongoing(Request $request)
    {
         //ProsesConfirmPo
        if(!empty($request->ID)||!empty($request->Number)){
            $date   = Carbon::now();
            $datapo = VwOngoinglocal::where('Number', $request->Number)->first();
        if($request->ActiveStage== '2'){
            $appsmenu = VwOngoinglocal::where('Number', $request->Number)->get();
            if(!empty($appsmenu)){
		  Notification::where('Number',$datapo->Number)->where('Subjek','Confirm New PO')
                    ->update([
                        'is_read'=>3,
                     ]);
                foreach ($appsmenu as $p) {
                    $id[] = $p->ID;
                }
                if($request->action== "Need"){
                    $create = Pdi::whereIn('ID',$id)
                    ->update([
                        'ActiveStage'=>'2a',
                    ]);
                    Notification::create([
                        'Number'         => $datapo->Number,
                        'Subjek'         => "Proforma",
                        'user_by'=>Auth::user()->name,
                        'user_to'=> $datapo->Vendor,
                        'is_read'=>1,
                        'menu'=>"polocalongoing",
                        'comment'=>"Request And Uploud Proforma PO $datapo->Number",
                        'created_at'=>$date
                    ]);
                }
                else{
                    $create = Pdi::whereIn('ID',$id)
                    ->update([
                        'ActiveStage'=>4,
                    ]);

                    Notification::create([
                        'Number'         => $datapo->Number,
                        'Subjek'         => "Approve PO",
                        'user_by'=>Auth::user()->name,
                        'user_to'=> $datapo->Vendor,
                        'is_read'=>1,
                        'menu'=>"polocalongoing",
                        'comment'=>"PO NO.$datapo->Number Approve next Create Ticket  ",
                        'created_at'=>$date
                    ]);
                }
                if($create ){
                    return redirect('polocalongoing')->with('suc_message', 'PO Proforma Approve!');
                }
                else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
              //ProsesProforma
        }
        else if($request->ActiveStage== '3'){
            $appsmenu = VwOngoinglocal::where('Number', $request->Number)->get();
            $getpdf = VwOngoinglocal::where('Number', $request->Number)->first();

            if(!empty($appsmenu)){
                $file =  $request->file('filename');
                $nama_file = time()."_".$file->getClientOriginalName();

                        // isi dengan nama folder tempat kemana file diupload
                $tujuan_upload = 'public/potracking/uploudproforma';
                $file->move($tujuan_upload,$nama_file);
                if($request->action== "Save"){
                    $this->validate($request,[
                        'confirm_date' => 'required'
                    ]);
                    Notification::where('Number',$datapo->Number)->where('Subjek','Request Proforma')
                    ->update([
                        'is_read'=>3,
                     ]);
                    Notification::create([
                        'Number'         => $datapo->Number,
                        'Subjek'         => "Approve PO",
                        'user_by'=>Auth::user()->name,
                        'user_to'=> $datapo->Vendor,
                        'is_read'=>1,
                        'menu'=>"polocalongoing",
                        'comment'=>"PO NO.$datapo->Number Approve next Create Ticket",
                        'created_at'=>$date
                    ]);
                    foreach ($appsmenu as $p) {
                        $id[] = $p->ID;
                    }
                    $create = Pdi::whereIn('ID',$id)->update(['ActiveStage'=>4,'ApproveProformaInvoiceDocument' => $nama_file]);
                    if($create){
                        return redirect('polocalongoing')->with('suc_message', 'Proforma disetujui!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }elseif($request->action== "Download"){
                    $file= "public/potracking/uploudproforma/$getpdf->ProformaInvoiceDocument";
                    $headers = array(
                        'Content-Type: application/pdf',
                    );
                    return Response::download($file, $getpdf->ProformaInvoiceDocument, $headers);
                }elseif($request->action== "Revisi"){
                    foreach ($appsmenu as $p) {
                        $id[] = $p->ID;
                    }
                    $create = Pdi::whereIn('ID',$id)
                    ->update([
                        'ActiveStage'=>'2a',

                    ]);
                    Notification::where('Number',$datapo->Number)->where('Subjek','Request Proforma')
                        ->update([
                            'is_read'=>3,
                         ]);
                    Notification::create([
                            'Number'         => $datapo->Number,
                            'Subjek'         => "Revisi Proforma",
                            'user_by'=>Auth::user()->name,
                            'user_to'=> $datapo->Vendor,
                            'is_read'=>1,
                            'menu'=>"polocalongoing",
                            'comment'=>"Revisi Proforma PO NO.$datapo->Number",
                            'created_at'=>$date
                        ]);
                    if($create){
                        return redirect('polocalongoing')->with('suc_message', 'Proforma harus direvisi!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        //ProsesTicket
        }
        else if($request->ActiveStage== '4' ){

            $appsmenu = VwOngoinglocal::whereIn('ParentID', $request->ID)->orWhereIn('ID', $request->ID)->first();

            $ticket = DetailTicket::all();
            $jumlahticket = count($ticket);
            if($jumlahticket == 0){
                $c = 1;
            }else{
                $c = $jumlahticket;
            }
               if(!empty($appsmenu)){
                $data = count($request->ID) ;
                $idticket   = 'PTR/PR/'.date('Ymd').'/'.date('His').'/'.$appsmenu->Number.'/'.$c.'/'.$data;
                $spbdate    = Carbon::createFromFormat('d/m/Y', $request->SPBdate)->format('Y-m-d');

                $create1 = Ticket::create([
                        'POID'=>$appsmenu->POID,
                        'ticketid'=> $idticket
                    ]);
                    for ($i = 0; $i < $data; $i++) {
                        $newinsert = [
                            'PDIID'         => $request->ID[$i],
                            'Number'         => $request->number[$i],
                            'ItemNumber'         => $request->itemnumber[$i],
                            'Material'         => $request->material[$i],
                            'Description'         => $request->description[$i],
                            'ConfirmDeliveryDate'  => Carbon::createFromFormat('d/m/Y', $request->deliverydatesap)->format('Y-m-d'),
                            'DeliveryDate'  => Carbon::createFromFormat('d/m/Y', $request->deliverydate)->format('Y-m-d').' '.$request->deliverytime,
                            'TicketID'      => $idticket,
                            'headertext'    => $request->headertext,
                            'DeliveryNote'  => $request->DeliveryNote,
                            'status'        => 'P',
                            'QtySAP'         => $request->qtysap[$i],
                            'Quantity'      => $request->QtyDelivery[$request->ID[$i]][0],
                            'SPBDate'       => $spbdate
                        ];
                        $create2 = DetailTicket::insert($newinsert);
                    }
                        Notification::where('Number',$request->numbers)->where('menu','Cancel Ticket Local')
                        ->update([
                            'is_read'=>3,
                         ]);

                    $dataS    = VwUserRoleGroup::select('username')->where('username','!=',$request->Name)->where('group', 30)->get();

                       foreach ($dataS as $q) {
                           $datarole[] = $q->username;
                       }

                   $datawarehouse = count($datarole);


                           for ($i = 0; $i < $datawarehouse; $i++) {
                            Notification::create([
                               'Number'         => $request->numbers,
                               'Subjek'         => $idticket,
                               'user_by'=>$appsmenu->Vendor,
                               'user_to'=>$datarole[$i],
                               'menu'=>'Ticket Local',
                               'is_read'=>1,
                               'comment'=>"$idticket",
                               'created_at'=>$date
                           ]);

                           }

                if($create1 && $create2){
                    return redirect('polocalongoing')->with('suc_message', 'Ticket Berhasil Dibuat !');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
            //ProsesUploudProforma
        }
        else{
            $this->validate($request,[
                'filename' => 'required|mimes:PDF,pdf|max:5120'
            ]);
            $appsmenu = VwOngoinglocal::where('Number', $request->Number)->get();
            if(!empty($appsmenu)){
                $file =  $request->file('filename');
                $nama_file = time()."_".$file->getClientOriginalName();

                        // isi dengan nama folder tempat kemana file diupload
                $tujuan_upload = 'public/potracking/uploudproforma';
                $file->move($tujuan_upload,$nama_file);
                foreach ($appsmenu as $p) {
                    $id[] = $p->ID;
                    }
                $create = Pdi::whereIn('ID',$id)
                        ->update([
                            'ActiveStage'=> 3,
                            'InvoiceMethod'=> $request->invoice_no,
                            'ConfirmReceivedPaymentDate'=>$request->invoice_date,
                            'InvoiceMethod'=> $request->invoice_no,
                            'TaxInvoice'=>$request->tax_invoice_no,
                            'ProformaInvoiceDocument'=>$nama_file,
                        ]);
                        Notification::where('Number',$datapo->Number)->where('Subjek','Proforma')->orWhere('Subjek','Revisi Proforma')
                        ->update([
                            'is_read'=>3,
                         ]);
                        Notification::create([
                            'Number'         => $datapo->Number,
                            'Subjek'         => "Request Proforma",
                            'user_by'=>Auth::user()->name,
                            'user_to'=> $datapo->NRP,
                            'is_read'=>1,
                            'menu'=>"polocalongoing",
                            'comment'=>"Proforma PO NO.$datapo->Number",
                            'created_at'=>$date
                        ]);
                if($create){
                    return redirect('polocalongoing')->with('suc_message', 'Proforma Berhasi Disimpan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }

        }else{
            if($request->ActiveStage== '4'){
                return redirect()->back()->with('error', 'Please Select Item !!');
            }
            else{
                return redirect()->back()->with('err_message', 'Data Tidak Boleh Kosong!');
            }
        }

    }

    //Acceptticket
    public function Confirmticket(Request $request)
    {

        if(!empty($request->ID)){

          $date = new DateTime();
          $po    = VwViewTicket::whereIn('IDTicket', $request->ID)->groupBy('number')->get();
          $datavendor = count($po);
          $datetime = $date->format('Y-m-d h:i:s');
          $appsmenu = Ticket::whereIn('ID', $request->ID)->first();
            if(!empty($appsmenu)||!empty($request->ID)){
                if($request->action== "Confirm Delivery"){
                    foreach ($po as $q) {
                        $ticketid[] = $q->TicketID;
                        $number[] = $q->Number;
                        $name[] = $q->Name;
                    }


                        $date   = Carbon::now();
                        for ($i = 0; $i < $datavendor; $i++) {
                            Notification::create([
                            'Number'         => $number[$i],
                            'Subjek'         => $ticketid[$i],
                            'user_by'=>Auth::user()->name,
                            'user_to'=>$name[$i],
                            'is_read'=>1,
                            'menu'=>'Approve Ticket Local',
                            'comment'=>$request->remarks,
                            'created_at'=>$date
                        ]);

                        }
                        Notification::whereIn('Number',$number)->where('menu','Ticket Local')
                        ->update([
                            'is_read'=>3,
                         ]);
                    $update = DetailTicket::whereIn('ID',$request->ID)
                    ->update([
                        'status'=>'A',
                        'AcceptedDate'=>$datetime,
                    ]);
                    if($update){
                        return redirect('plandelivery')->with('suc_message', 'Ticket Accepted By Warehouse !');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }elseif($request->action== "Update Delivery"){
                    // dd($request);
                    $data = count($request->IDS) ;
                    if($this->PermissionActionMenu('polocalnewpo')->v==1){
                        for ($i = 0; $i < $data; $i++) {
                            $update = DetailTicket::where('ID',$request->IDS[$i])
                            ->update([
                                'DeliveryDate'=>Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d').' '.$request->time[$i],
                            ]);
                        }
                        $update = DetailTicket::whereIn('ID',$request->ID)
                        ->update([
                            'status'=>'A',
                            'AcceptedDate'=>$datetime,
                        ]);
                    }else{
                        for ($i = 0; $i < $data; $i++) {
                            $update = DetailTicket::where('ID',$request->IDS[$i])
                            ->update([
                                'DeliveryDate'=>Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d').' '.$request->time[$i],
                            ]);
                        }

                    }

                    if($update || !$update){
                        return redirect('plandelivery')->with('suc_message', 'Update Delivery Success !!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{

                        $datavendor = count($po);
                        foreach ($po as $q) {
                            $ticketid[] = $q->TicketID;
                            $number[] = $q->Number;
                            $name[] = $q->Name;

                        }

                            Notification::whereIn('Number',$number)->where('menu','Ticket Local')
                            ->update([
                                'is_read'=>3,
                             ]);
                                $date   = Carbon::now();
                                for ($i = 0; $i < $datavendor; $i++) {
                                Notification::create([
                                    'Number'         => $number[$i],
                                    'Subjek'         => $ticketid[$i],
                                    'user_by'=>Auth::user()->name,
                                    'user_to'=>$name[$i],
                                    'is_read'=>1,
                                    'menu'=>'Cancel Ticket Local',
                                    'comment'=>$request->remarks,
                                    'created_at'=>$date
                                ]);

                                }
                            foreach ($po as $q) {
                                    $ticketids[] = $q->TicketID;
                                    $numbers[] = $q->Number;
                                    $names[] = $q->NRP;

                                }

                            $date   = Carbon::now();
                            for ($i = 0; $i < $datavendor; $i++) {
                            Notification::create([
                                        'Number'         => $numbers[$i],
                                        'Subjek'         => $ticketids[$i],
                                        'user_by'=>Auth::user()->name,
                                        'user_to'=>$names[$i],
                                        'is_read'=>1,
                                        'menu'=>'Cancel Ticket Local',
                                        'comment'=>$request->remarks,
                                        'created_at'=>$date
                            ]);

                            }
                            $update = DetailTicket::whereIn('ID',$request->ID)
                            ->update([
                                'remarks'=>$request->remarks,
                                'status'=>'C',
                                'AcceptedDate'=>$date,
                            ]);
                    if($update){
                        return redirect('plandelivery')->with('suc_message', 'Ticket Di Cancel !');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
            }else{
                return redirect()->back()->with('error', 'Please Select Item !!');
            }
        }else{
            return redirect()->back()->with('error', 'Please Select Item !!');
        }
    }

    // //ViewTicket
    // public function viewticket(Request $request)
    // {
    //     $polocal = VwPo::where('ID', $request->id)->first();
    //     $ticket = VwQtytiket::where('Number', $polocal->Number)->get();
    //     $data = array(
    //         'ticket' =>$ticket,
    //         'po' =>$polocal,

    //     );

    // echo json_encode($data);
    // }

    //DownloadTIcket
    public function TicketPdf(Request $request)
    {

        $data = VwViewTicket::where('TicketID', $request->TicketID)->groupBy('ID','ItemNumber')->WhereNotIn('status' ,['C'])->orderBy('ID', 'asc')->get();
        foreach($data as $a){
            $id_tiket[] = $a->IDTicket;
        }

        if(!empty($data)){
           DetailTicket::whereIn('ID',$id_tiket)
            ->update([
                'status'=>'D',
            ]);

        $dataviewticket = VwViewTicket::where('TicketID', $request->TicketID)->first();
        Notification::where('Number',$dataviewticket->Number)->where('menu','Approve Ticket Local')
        ->update([
            'is_read'=>3,
         ]);
        $deliverydate = date('d/m/Y', strtotime($dataviewticket->DeliveryDate));
            $deliverytime = date('H:i:s', strtotime($dataviewticket->DeliveryDate));

            $dataallviewticket = VwViewTicket::where('TicketID', $request->TicketID)->select('ItemNumber','Material','Description','DeliveryDates','Quantity')->get()->toArray();
            foreach($dataallviewticket as $b){
                $date               = date('d/m/Y', strtotime($b['DeliveryDates']));
                $b['DeliveryDates'] = $date;
                $n_item[]           = implode("|",$b);
            }
            $dataallviewticket1 = implode("|",$n_item);
            // dd($dataallviewticket1);

            $podatenya = date('d/m/Y', strtotime($dataviewticket->Date));
            $spbdatenya = date('d/m/Y', strtotime($dataviewticket->SPBDate));
            //string di QR-nya



            $qrstr = $dataviewticket->TicketID.'|'.
                        $dataviewticket->Number.'|'.
                        $podatenya.'|'.
                        $dataviewticket->Name.'|'.
                        $dataviewticket->VendorCode.'|'.
                        $dataviewticket->DeliveryNote.'|'.
                        $dataviewticket->headertextgr.'|'.
                        $deliverydate.'|'.$deliverytime
                        ;

            $qrcode         = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($qrstr.'||'.$dataallviewticket1.'||'.$spbdatenya.'|'));

            $data = array(
                'data'              => $data,
                'dataviewticket'    => $dataviewticket,
                'qrcode'            => $qrcode,
            );

            $pdf = PDF::loadView('po-tracking/subcontraktor/subcontractorticket', $data);

        // return view('po-non-sap/po-local/ticketpdf')->with('data', $data);
        return $pdf->stream();
        }else{
            return redirect()->back()->with('err_message', 'Data Tidak Ditemukan');
        }

    }

       //Downloadfileparking
       public function Downloadfile($id)
       {

                   $file= "public/potracking/parking_invoice/$id";
                   $headers = array('Content-Type: application/pdf',);
                   return Response::download($file, $id, $headers);

       }
       //Downloadfileproforma
       public function Downloadproforma($id)
       {

                   $file= "public/potracking/uploudproforma/$id";
                   $headers = array('Content-Type: application/pdf',);
                   return Response::download($file, $id, $headers);

       }
     //ApproveParking
    public function Approveparking(Request $request)
    {
        $data = VwViewTicket::where('TicketID', $request->TicketID)->groupBy('ID','ItemNumber')->WhereNotIn('status' ,['C'])->orderBy('ID', 'asc')->get();
        foreach($data as $a){
            $id_tiket[] = $a->IDTicket;
        }

        if(!empty($data)){
            $parking = DetailTicket::whereIn('ID',$id_tiket)
                ->update([
                    'status'=>'X',
                ]);
                if($parking){
                    return redirect('historyparking')->with('suc_message', 'Parking Accept!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }

        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }





}
