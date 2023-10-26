<?php

namespace App\Http\Controllers\Cms\PoTracking\POSubcontractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;;

use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\Users;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwViewTicket;
use PDF;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class ReadyDeliveryController extends Controller
{

    // subcontractor

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('subcontractorreadydelivery') == 0  && $this->PermissionMenu('subcontractorreadydelivery-proc') == 0 && $this->PermissionMenu('subcontractorreadydelivery-management') == 0 && $this->PermissionMenu('subcontractorreadydelivery-nonmanagement') == 0 && $this->PermissionMenu('subcontractorreadydelivery-vendor') == 0 && $this->PermissionMenu('subcontractorreadydelivery-whs') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }


    //ReadyDelivery
    public function readyDelivery()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorreadydelivery')->r == 1) {

                $header_title                   = "PO SUBCONT - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ready TO Delivery',
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

                $link_newPO                     = 'subcontractornewpo';
                $link_ongoing                   = 'subcontractorongoing';
                $link_planDelivery              = 'subcontractorplandelivery';
                $link_readyToDelivery           = 'subcontractorreadydelivery';
                $link_historyPO                 = 'subcontractorhistory';
                $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                    ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $data->count();

                return view(
                    'po-tracking/subcontractor/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'countNewpoSubcont',
                        'countOngoingSubcont',
                        'countHistorySubcont',
                        'countPlanDeliverySubcont',
                        'countReadyToDeliverySubcont',
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
    //ReadyDelivery
    public function readyDeliveryManagement()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorreadydelivery-management')->r == 1) {

                $header_title                   = "PO SUBCONT - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ready TO Delivery',
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

                $link_newPO                     = 'subcontractornewpo-management';
                $link_ongoing                   = 'subcontractorongoing-management';
                $link_planDelivery              = 'subcontractorplandelivery-management';
                $link_readyToDelivery           = 'subcontractorreadydelivery-management';
                $link_historyPO                 = 'subcontractorhistory-management';
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                if ((strchr($datauser->assign_plant, "UCKR") == 'UCKR') || (strchr($datauser->assign_plant, "PCKR") == 'PCKR')) {
                    $kodex = ['A', 'D'];
                } else {
                    $kodex = ['A', 'D', 'S', 'R'];
                }
                $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-management');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.status', $kodex)->whereIn('detailticketingdelivery.Plant', $plant)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $data->count();

                return view(
                    'po-tracking/subcontractor/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'countNewpoSubcont',
                        'countOngoingSubcont',
                        'countHistorySubcont',
                        'countPlanDeliverySubcont',
                        'countReadyToDeliverySubcont',
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
            if ($this->PermissionActionMenu('subcontractorreadydelivery-nonmanagement')->r == 1) {

                $header_title                   = "PO SUBCONT - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ready TO Delivery',
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

                $link_newPO                     = 'subcontractornewpo-nonmanagement';
                $link_ongoing                   = 'subcontractorongoing-nonmanagement';
                $link_planDelivery              = 'subcontractorplandelivery-nonmanagement';
                $link_readyToDelivery           = 'subcontractorreadydelivery-nonmanagement';
                $link_historyPO                 = 'subcontractorhistory-nonmanagement';
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                if ((strchr($datauser->assign_plant, "UCKR") == 'UCKR') || (strchr($datauser->assign_plant, "PCKR") == 'PCKR')) {
                    $kodex = ['A', 'D'];
                } else {
                    $kodex = ['A', 'D', 'S', 'R'];
                }
                $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-nonmanagement');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.status', $kodex)->whereIn('detailticketingdelivery.Plant', $plant)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $data->count();

                return view(
                    'po-tracking/subcontractor/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'countNewpoSubcont',
                        'countOngoingSubcont',
                        'countHistorySubcont',
                        'countPlanDeliverySubcont',
                        'countReadyToDeliverySubcont',
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
            if ($this->PermissionActionMenu('subcontractorreadydelivery-proc')->r == 1) {

                $header_title                   = "PO SUBCONT - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ready TO Delivery',
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

                $link_newPO                     = 'subcontractornewpo-proc';
                $link_ongoing                   = 'subcontractorongoing-proc';
                $link_planDelivery              = 'subcontractorplandelivery-proc';
                $link_readyToDelivery           = 'subcontractorreadydelivery-proc';
                $link_historyPO                 = 'subcontractorhistory-proc';

                $kodex = ['A', 'D', 'S', 'R'];

                $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-proc');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = VwViewTicket::select('Number', 'ItemNumber', 'TicketID')->where('NRP', Auth::user()->email)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftjoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                    ->where('po.CreatedBy', Auth::user()->email)->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                    ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $data->count();

                return view(
                    'po-tracking/subcontractor/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'countNewpoSubcont',
                        'countOngoingSubcont',
                        'countHistorySubcont',
                        'countPlanDeliverySubcont',
                        'countReadyToDeliverySubcont',
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
            if ($this->PermissionActionMenu('subcontractorreadydelivery-vendor')->r == 1) {

                $header_title                   = "PO SUBCONT - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ready TO Delivery',
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

                $link_newPO                     = 'subcontractornewpo-vendor';
                $link_ongoing                   = 'subcontractorongoing-vendor';
                $link_planDelivery              = 'subcontractorplandelivery-vendor';
                $link_readyToDelivery           = 'subcontractorreadydelivery-vendor';
                $link_historyPO                 = 'subcontractorhistory-vendor';

                $kodex = ['A', 'D'];

                $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-vendor');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                if ($request->Number) {
                    $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('uservendors.VendorCode', Auth::user()->email)->where('detailticketingdelivery.Number', $request->Number)
                        ->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                        ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                        ->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                } else {
                    $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('uservendors.VendorCode', Auth::user()->email)->whereIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D'])
                        ->OrWhereNotIn('detailticketingdelivery.plant', ['UCKR', 'PCKR', '1000'])->whereIn('detailticketingdelivery.status', ['A', 'D', 'S', 'R'])
                        ->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                }

                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $data->count();

                return view(
                    'po-tracking/subcontractor/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'countNewpoSubcont',
                        'countOngoingSubcont',
                        'countHistorySubcont',
                        'countPlanDeliverySubcont',
                        'countReadyToDeliverySubcont',
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
            if ($this->PermissionActionMenu('subcontractorreadydelivery-whs')->r == 1) {

                $header_title                   = "PO SUBCONT - READY TO DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ready TO Delivery',
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

                $link_newPO                     = 'subcontractornewpo-whs';
                $link_ongoing                   = 'subcontractorongoing-whs';
                $link_planDelivery              = 'subcontractorplandelivery-whs';
                $link_readyToDelivery           = 'subcontractorreadydelivery-whs';
                $link_historyPO                 = 'subcontractorhistory-whs';
                $datauser = Users::where('email', Auth::user()->email)->first();
                if ((strchr($datauser->assign_plant, "UCKR") == 'UCKR') || (strchr($datauser->assign_plant, "PCKR") == 'PCKR')) {
                    $kodex = ['A', 'D'];
                } else {
                    $kodex = ['A', 'D', 'S', 'R'];
                }
                $plant = explode(', ', $datauser->assign_plant);
                $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-whs');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.status', $kodex)->whereIn('detailticketingdelivery.Plant', $plant)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $data->count();

                return view(
                    'po-tracking/subcontractor/readytodelivery',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'countNewpoSubcont',
                        'countOngoingSubcont',
                        'countHistorySubcont',
                        'countPlanDeliverySubcont',
                        'countReadyToDeliverySubcont',
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
            if ($this->PermissionActionMenu('subcontractorreadydelivery-whs')) {
                $link = "subcontractorreadydelivery-whs";
            } else if ($this->PermissionActionMenu('subcontractorreadydelivery')) {
                $link = "subcontractorreadydelivery";
            } else if ($this->PermissionActionMenu('subcontractorreadydelivery-vendor')) {
                $link = "subcontractorreadydelivery-vendor";
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
                'menu'  => 'PO Subcont Ready TO Delivery',
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
                            'AcceptedDate' => $datetime,
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
            ->where('detailticketingdelivery.TicketID', $request->TicketID)->whereIn('detailticketingdelivery.status', ['D', 'A'])->WhereNotIn('detailticketingdelivery.status', ['C'])->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.ID', 'asc')->get();
        foreach ($data as $a) {
            $id_tiket[] = $a->IDTicket;
        }

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
                'menu'  => 'PO Subcont Ready TO Delivery',
                'description' => 'Download Ticket',
                'date'  => $date->toDateString(),
                'time'     => $date->toTimeString(),
                'ponumber' => $dataviewticket->Number,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title,
                'vendortype' => 'Subcont',
                'CreatedBy'  => Auth::user()->name,
            ]);
            Notification::where('Number', $dataviewticket->Number)->where('Subjek', 'Approve Ticket Subcont')
                ->update([
                    'is_read' => 3,
                ]);
            $deliverydate = date('d/m/Y', strtotime($dataviewticket->DeliveryDate));
            $deliverytime = date('H:i:s', strtotime($dataviewticket->DeliveryDate));

            $dataallviewticket = DetailTicket::select('detailticketingdelivery.ItemNumber', 'detailticketingdelivery.Material', 'detailticketingdelivery.Description', 'purchasingdocumentitem.DeliveryDate as DeliveryDates','detailticketingdelivery.Quantity')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->where('detailticketingdelivery.TicketID', $request->TicketID)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->get()->toArray();

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

            $pdf = PDF::loadView('po-tracking/subcontractor/subcontractorticket', $data);

            // return view('po-non-sap/po-local/ticketpdf')->with('data', $data);
            return $pdf->stream();
        } else {
            return redirect()->back()->with('err_message', 'Data Tidak Ditemukan');
        }
    }
}
