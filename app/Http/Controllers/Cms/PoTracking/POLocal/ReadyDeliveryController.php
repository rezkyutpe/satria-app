<?php

namespace App\Http\Controllers\Cms\PoTracking\POLocal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;;

use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\Users;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwPoLocalOngoing;
use PDF;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class ReadyDeliveryController extends Controller
{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('polocalreadydelivery') == 0 && $this->PermissionMenu('polocalreadydelivery-management') == 0 && $this->PermissionMenu('polocalreadydelivery-nonmanagement') == 0 && $this->PermissionMenu('polocalreadydelivery-proc') == 0 && $this->PermissionMenu('polocalreadydelivery-vendor') == 0 && $this->PermissionMenu('polocalreadydelivery-whs') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }


    //ReadyDelivery
    public function readyDelivery()
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery')->r == 1) {

                $header_title                   = "PO LOCAL - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ready TO Delivery',
                        'description' => 'Display Ready TO Delivery',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'time'     => $date->toTimeString()
                    ]
                );

                $link_newPO                     = 'polocalnewpo';
                $link_ongoing                   = 'polocalongoing';
                $link_planDelivery              = 'polocalplandelivery';
                $link_readyToDelivery           = 'polocalreadydelivery';
                $link_historyPO                 = 'polocalhistory';
                $kodex = ['A', 'D', 'S', 'R'];
                $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                    ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $HistoryPolocal->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $data->count();

                $datafinishlocal = VwPoLocalOngoing::all();

                return view(
                    'po-tracking/polocal/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'datafinishlocal',
                        'countNewpoPolocal',
                        'countOngoingPolocal',
                        'countHistoryPolocal',
                        'countplanDelivery',
                        'countreadyToDelivery',
                        'actionmenu'
                    )
                );
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //ReadyDeliveryManagement
    public function readyDeliveryManagement()
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery-management')->r == 1) {

                $header_title                   = "PO LOCAL - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ready TO Delivery',
                        'description' => 'Display Ready TO Delivery',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'time'     => $date->toTimeString()
                    ]
                );

                $link_newPO                     = 'polocalnewpo-management';
                $link_ongoing                   = 'polocalongoing-management';
                $link_planDelivery              = 'polocalplandelivery-management';
                $link_readyToDelivery           = 'polocalreadydelivery-management';
                $link_historyPO                 = 'polocalhistory-management';

                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                if ((strchr($datauser->assign_plant, "UCKR") == 'UCKR') || (strchr($datauser->assign_plant, "PCKR") == 'PCKR')) {
                    $kodex = ['A', 'D'];
                } else {
                    $kodex = ['A', 'D', 'S', 'R'];
                }

                $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-management');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.status', $kodex)->whereIn('detailticketingdelivery.Plant', $plant)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $HistoryPolocal->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $data->count();
                $datafinishlocal = VwPoLocalOngoing::all();
                return view(
                    'po-tracking/polocal/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'datafinishlocal',
                        'countNewpoPolocal',
                        'countOngoingPolocal',
                        'countHistoryPolocal',
                        'countplanDelivery',
                        'countreadyToDelivery',
                        'actionmenu'
                    )
                );
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //ReadyDeliveryProc
    public function readyDeliveryNonManagement()
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery-nonmanagement')->r == 1) {

                $header_title                   = "PO LOCAL - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ready TO Delivery',
                        'description' => 'Display Ready TO Delivery',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'time'     => $date->toTimeString()
                    ]
                );

                $link_newPO                     = 'polocalnewpo-nonmanagement';
                $link_ongoing                   = 'polocalongoing-nonmanagement';
                $link_planDelivery              = 'polocalplandelivery-nonmanagement';
                $link_readyToDelivery           = 'polocalreadydelivery-nonmanagement';
                $link_historyPO                 = 'polocalhistory-nonmanagement';
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                if ((strchr($datauser->assign_plant, "UCKR") == 'UCKR') || (strchr($datauser->assign_plant, "PCKR") == 'PCKR')) {
                    $kodex = ['A', 'D'];
                } else {
                    $kodex = ['A', 'D', 'S', 'R'];
                }

                $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-nonmanagement');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.status', $kodex)->whereIn('detailticketingdelivery.Plant', $plant)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $HistoryPolocal->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $data->count();

                $datafinishlocal = VwPoLocalOngoing::all();

                return view(
                    'po-tracking/polocal/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'datafinishlocal',
                        'countNewpoPolocal',
                        'countOngoingPolocal',
                        'countHistoryPolocal',
                        'countplanDelivery',
                        'countreadyToDelivery',
                        'actionmenu'
                    )
                );
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //ReadyDeliveryProc
    public function readyDeliveryProc()
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery-proc')->r == 1) {

                $header_title                   = "PO LOCAL - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ready TO Delivery',
                        'description' => 'Display Ready TO Delivery',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'time'     => $date->toTimeString()
                    ]
                );

                $link_newPO                     = 'polocalnewpo-proc';
                $link_ongoing                   = 'polocalongoing-proc';
                $link_planDelivery              = 'polocalplandelivery-proc';
                $link_readyToDelivery           = 'polocalreadydelivery-proc';
                $link_historyPO                 = 'polocalhistory-proc';

                $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-proc');
                $NewpoPolocal = VwLocalnewpo::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftjoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                    ->where('po.CreatedBy', Auth::user()->email)->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                    ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $HistoryPolocal->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $data->count();

                $datafinishlocal = VwPoLocalOngoing::all();

                return view(
                    'po-tracking/polocal/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'datafinishlocal',
                        'countNewpoPolocal',
                        'countOngoingPolocal',
                        'countHistoryPolocal',
                        'countplanDelivery',
                        'countreadyToDelivery',
                        'actionmenu'
                    )
                );
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //ReadyDeliveryVendor
    public function readyDeliveryVendor(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery-vendor')->r == 1) {

                $header_title                   = "PO LOCAL - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ready TO Delivery',
                        'description' => 'Display Ready TO Delivery',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'time'     => $date->toTimeString()
                    ]
                );
                $link_newPO                     = 'polocalnewpo-vendor';
                $link_ongoing                   = 'polocalongoing-vendor';
                $link_planDelivery              = 'polocalplandelivery-vendor';
                $link_readyToDelivery           = 'polocalreadydelivery-vendor';
                $link_historyPO                 = 'polocalhistory-vendor';



                $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-vendor');
                $NewpoPolocal = VwLocalnewpo::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                if ($request->Number) {
                    $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('uservendors.VendorCode', Auth::user()->email)->where('detailticketingdelivery.Number', $request->Number)
                        ->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                        ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                        ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                } else {
                    $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('uservendors.VendorCode', Auth::user()->email)->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                        ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                        ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                }
                $HistoryPolocal          = VwHistoryLocal::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $HistoryPolocal->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $data->count();

                $datafinishlocal = VwPoLocalOngoing::all();

                return view(
                    'po-tracking/polocal/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'datafinishlocal',
                        'countNewpoPolocal',
                        'countOngoingPolocal',
                        'countHistoryPolocal',
                        'countplanDelivery',
                        'countreadyToDelivery',
                        'actionmenu'
                    )
                );
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //ReadyDeliveryProc
    public function readyDeliveryWhs()
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery-whs')->r == 1) {

                $header_title                   = "PO LOCAL - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Ready TO Delivery',
                    'description' => 'Display Ready TO Delivery',
                    'date'  => $date->toDateString(),
                    'time'     => $date->toTimeString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => 'Local',
                    'CreatedBy'  => Auth::user()->name,
                ]);

                $link_newPO                     = 'polocalnewpo-whs';
                $link_ongoing                   = 'polocalongoing-whs';
                $link_planDelivery              = 'polocalplandelivery-whs';
                $link_readyToDelivery           = 'polocalreadydelivery-whs';
                $link_historyPO                 = 'polocalhistory-whs';

                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                if ((strchr($datauser->assign_plant, "UCKR") == 'UCKR') || (strchr($datauser->assign_plant, "PCKR") == 'PCKR')) {
                    $kodex = ['A', 'D'];
                } else {
                    $kodex = ['A', 'D', 'S', 'R'];
                }

                $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-whs');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.status', $kodex)->whereIn('detailticketingdelivery.Plant', $plant)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistoryPolocal          = VwHistoryLocal::whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $HistoryPolocal->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $data->count();

                $datafinishlocal = VwPoLocalOngoing::all();

                return view(
                    'po-tracking/polocal/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'datafinishlocal',
                        'countNewpoPolocal',
                        'countOngoingPolocal',
                        'countHistoryPolocal',
                        'countplanDelivery',
                        'countreadyToDelivery',
                        'actionmenu'
                    )
                );
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //ProsesUpdate
    public function ProsesUpdate(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('polocalreadydelivery-whs')) {
                $link = "polocalreadydelivery-whs";
            } else if ($this->PermissionActionMenu('polocalreadydelivery')) {
                $link = "polocalreadydelivery";
            } else if ($this->PermissionActionMenu('polocalreadydelivery-vendor')) {
                $link = "polocalreadydelivery-vendor";
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            if ($request->status == "Update") {
                $status = "D";
                $tanggal = "DeliveryDate";
            } else {
                $status = "C";
                $tanggal = "ConfirmTicketDate";
            }

            $date   = Carbon::now();
            $dataviewticket = DetailTicket::where('TicketID', $request->ID)->first();
            $datetime = $date->format('Y-m-d h:i:s');
            LogHistory::create([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ready TO Delivery',
                'description' => 'Update Ticket',
                'date'  => $date->toDateString(),
                'time'     => $date->toTimeString(),
                'ponumber' => $dataviewticket->Number,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
            ]);
            if ($request->status == "DeliveryNote") {
                if ($dataviewticket->AcceptedDate == NULL) {
                    $update = DetailTicket::where('TicketID', $request->ID)->whereIn('status', ['D', 'A'])
                        ->update([
                            'DeliveryNote' => $request->DeliveryNote,
                            'headertext' => $request->HeaderText,
                            'AcceptedDate' => $datetime
                        ]);
                } else {
                    $update = DetailTicket::where('TicketID', $request->ID)->whereIn('status', ['D', 'A'])
                        ->update([
                            'DeliveryNote' => $request->DeliveryNote,
                            'headertext' => $request->HeaderText,
                        ]);
                }
            } else {

                $update = DetailTicket::where('TicketID', $request->ID)->whereIn('status', ['D', 'A', 'S'])
                    ->update([
                        'remarks' => $request->remarks,
                        'status' => $status,
                        $tanggal => Carbon::createFromFormat('d/m/Y', $request->ConfirmTicketDate)->format('Y-m-d'),
                    ]);
            }


            if ($update) {
                return redirect($link)->with('suc_message', 'Ticket Succes !');
            } else {
                return redirect()->back()->with('err_message', 'Data gagal di Proses!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //DownloadTIcket
    public function TicketPdf(Request $request)
    {

        $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDates', 'purchasingdocumentitem.POID', 'uservendors.Name as Name', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
            $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
        })
            ->where('detailticketingdelivery.TicketID', $request->TicketID)->whereIn('detailticketingdelivery.status', ['D', 'A', 'S', 'R'])->WhereNotIn('detailticketingdelivery.status', ['C'])->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.ID', 'asc')->get();
        if (!empty($data)) {
            DetailTicket::where('TicketID', $request->TicketID)
                ->update([
                    'status' => 'D',
                ]);
            $date   = Carbon::now();

            $dataviewticket = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDates', 'purchasingdocumentitem.POID', 'uservendors.Name as Name', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->where('detailticketingdelivery.TicketID', $request->TicketID)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->first();

            LogHistory::create([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ready TO Delivery',
                'description' => 'Download Ticket',
                'date'  => $date->toDateString(),
                'time'     => $date->toTimeString(),
                'ponumber' => $dataviewticket->Number,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
            ]);
            Notification::where('Number', $dataviewticket->Number)->where('Subjek', 'Approve Ticket Local')
                ->update([
                    'is_read' => 3,
                ]);
            $deliverydate = date('d/m/Y', strtotime($dataviewticket->DeliveryDate));
            $deliverytime = date('H:i:s', strtotime($dataviewticket->DeliveryDate));


            $dataallviewticket = DetailTicket::select('detailticketingdelivery.ItemNumber', 'detailticketingdelivery.Material', 'detailticketingdelivery.Description', 'purchasingdocumentitem.DeliveryDate as DeliveryDates','detailticketingdelivery.Quantity')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->where('detailticketingdelivery.TicketID', $request->TicketID)->get()->toArray();

            foreach ($dataallviewticket as $b) {
                $date               = date('d/m/Y', strtotime($b['DeliveryDates']));
                $b['DeliveryDates'] = $date;
                $n_item[]           = implode("|", $b);
            }
            $dataallviewticket1 = implode("|", $n_item);
            // dd($dataallviewticket1);

            $podatenya = date('d/m/Y', strtotime($dataviewticket->Date));
            $spbdatenya = date('d/m/Y', strtotime($dataviewticket->SPBDate));
            //string di QR-nya



            $qrstr = $dataviewticket->TicketID . '|' .
                $dataviewticket->Number . '|' .
                $podatenya . '|' .
                $dataviewticket->Name . '|' .
                $dataviewticket->VendorCode_new . '|' .
                $dataviewticket->DeliveryNote . '|' .
                $dataviewticket->headertext . '|' .
                $deliverydate . '|' . $deliverytime;
            if (strlen($dataallviewticket1) > 1000) {
                $dataallviewticket1 = substr($dataallviewticket1, 0, 500) . '...';
            }

            $qrcode         = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($qrstr . '||' . $dataallviewticket1 . '||' . $spbdatenya . '|'));

            $data = array(
                'data'              => $data,
                'dataviewticket'    => $dataviewticket,
                'qrcode'            => $qrcode,
            );

            $pdf = PDF::loadView('po-tracking/polocal/ticket', $data);

            // return view('po-non-sap/po-local/ticketpdf')->with('data', $data);
            return $pdf->stream();
        } else {
            return redirect()->back()->with('err_message', 'Data Tidak Ditemukan');
        }
    }
}
