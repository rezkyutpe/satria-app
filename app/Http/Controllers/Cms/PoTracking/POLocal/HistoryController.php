<?php

namespace App\Http\Controllers\Cms\PoTracking\POLocal;

use App\Http\Controllers\Controller;
use App\Models\Table\PoTracking\DetailTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Users;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwHistorytotal;
use Illuminate\Support\Carbon;
use Exception;

class HistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if ($this->PermissionMenu('polocalhistory') == 0 && $this->PermissionMenu('polocalhistory-management') == 0 && $this->PermissionMenu('polocalhistory-nonmanagement') == 0 && $this->PermissionMenu('polocalhistory-whs') == 0 && $this->PermissionMenu('polocalhistory-proc') == 0 && $this->PermissionMenu('polocalhistory-vendor') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //poHIstoryview
    public function Polocalhistory()
    {
        try {
            if ($this->PermissionActionMenu('polocalhistory')->r == 1) {

                $header_title                   = "PO LOCAL - PO HISTORY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local History Po',
                        'description' => 'Display History PO',
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
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('polocalhistory');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
                $data = VwHistoryLocal::select(
                    'ID',
                    'Number',
                    'NRP',
                    'PurchaseOrderCreator',
                    'Vendor',
                    'VendorCode',
                    'VendorCode_new',
                    'POID',
                    'Date',
                    'ReleaseDate',
                    'DeliveryDate',
                    'ParentID',
                    'ItemNumber',
                    'Material',
                    'MaterialVendor',
                    'Description',
                    'NetPrice',
                    'Currency',
                    'Quantity',
                    'ConfirmedDate',
                    'ConfirmedQuantity',
                    'IsClosed',
                    'ActiveStage',
                    'totalgr'
                )->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $data->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $readyToDelivery->count();
                return view(
                    'po-tracking/polocal/pohistory',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
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
    //poHIstoryview
    public function PolocalhistoryManagement()
    {
        try {
            if ($this->PermissionActionMenu('polocalhistory-management')->r == 1) {
                $header_title                   = "PO LOCAL - PO HISTORY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local History Po',
                        'description' => 'Display History PO',
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
                $kodex = ['A', 'D'];
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $actionmenu =  $this->PermissionActionMenu('polocalhistory-management');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
                $data = VwHistoryLocal::select(
                    'ID',
                    'Number',
                    'NRP',
                    'PurchaseOrderCreator',
                    'Vendor',
                    'VendorCode',
                    'VendorCode_new',
                    'POID',
                    'Date',
                    'ReleaseDate',
                    'DeliveryDate',
                    'ParentID',
                    'ItemNumber',
                    'Material',
                    'MaterialVendor',
                    'Description',
                    'NetPrice',
                    'Currency',
                    'Quantity',
                    'ConfirmedDate',
                    'ConfirmedQuantity',
                    'ActiveStage',
                    'totalgr'
                )->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $data->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $readyToDelivery->count();
                return view(
                    'po-tracking/polocal/pohistory',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
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
    //poHIstoryviewNonManagement
    public function PolocalhistoryNonManagement()
    {
        try {
            if ($this->PermissionActionMenu('polocalhistory-nonmanagement')->r == 1) {

                $header_title                   = "PO LOCAL - PO HISTORY";
                $link_search                    = "caripolocalhistory";
                $link_reset                     = "polocalhistory";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local History Po',
                        'description' => 'Display History PO',
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
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('polocalhistory-nonmanagement');
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
                $data = VwHistoryLocal::select(
                    'ID',
                    'Number',
                    'NRP',
                    'PurchaseOrderCreator',
                    'Vendor',
                    'VendorCode',
                    'VendorCode_new',
                    'POID',
                    'Date',
                    'ReleaseDate',
                    'DeliveryDate',
                    'ParentID',
                    'ItemNumber',
                    'Material',
                    'MaterialVendor',
                    'Description',
                    'Quantity',
                    'totalgr',
                    'ConfirmedDate',
                    'ConfirmedQuantity',
                    'ActiveStage',
                    'IsClosed'
                )->groupBy('Number', 'ItemNumber')->whereIn('Plant', $plant)->orderBy('id', 'desc')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $data->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $readyToDelivery->count();

                return view(
                    'po-tracking/polocal/pohistory-nonmanagement',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
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
    //poHIstoryviewProc
    public function PolocalhistoryProc()
    {
        try {
            if ($this->PermissionActionMenu('polocalhistory-proc')->r == 1) {

                $header_title                   = "PO LOCAL - PO HISTORY";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local History Po',
                        'description' => 'Display History PO',
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
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('polocalhistory-proc');
                $NewpoPolocal = VwLocalnewpo::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $data = VwHistoryLocal::select('ID', 'Number', 'NRP', 'PurchaseOrderCreator', 'Vendor', 'VendorCode_new', 'VendorCode', 'POID', 'Date', 'ReleaseDate', 'DeliveryDate', 'ParentID', 'ItemNumber', 'Material', 'MaterialVendor', 'Description', 'NetPrice', 'Currency', 'Quantity', 'ConfirmedDate', 'ConfirmedQuantity', 'totalgr', 'ActiveStage', 'IsClosed')
                    ->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $data->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $readyToDelivery->count();

                return view(
                    'po-tracking/polocal/pohistory',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
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
    //poHIstoryviewVendor
    public function PolocalhistoryVendor()
    {
        try {
            if ($this->PermissionActionMenu('polocalhistory-vendor')->r == 1) {

                $header_title                   = "PO LOCAL - PO HISTORY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local History Po',
                        'description' => 'Display History PO',
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
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('polocalhistory-vendor');
                $NewpoPolocal = VwLocalnewpo::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('Number', 'asc')->get();
                $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $data = VwHistoryLocal::select('ID', 'Number', 'NRP', 'PurchaseOrderCreator', 'Vendor', 'VendorCode_new', 'VendorCode', 'POID', 'Date', 'ReleaseDate', 'DeliveryDate', 'ParentID', 'ItemNumber', 'Material', 'MaterialVendor', 'Description', 'NetPrice', 'Currency', 'Quantity', 'ConfirmedDate', 'totalgr', 'IsClosed', 'ConfirmedQuantity', 'ActiveStage')
                    ->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $data->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $readyToDelivery->count();

                return view(
                    'po-tracking/polocal/pohistory',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
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
    //poHIstoryviewVendor
    public function PolocalhistoryWhs()
    {
        try {
            if ($this->PermissionActionMenu('polocalhistory-whs')->r == 1) {

                $header_title                   = "PO LOCAL - PO HISTORY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local History Po',
                        'description' => 'Display History PO',
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

                $link_newPO                     = 'polocalnewpo-whs';
                $link_ongoing                   = 'polocalongoing-whs';
                $link_planDelivery              = 'polocalplandelivery-whs';
                $link_readyToDelivery           = 'polocalreadydelivery-whs';
                $link_historyPO                 = 'polocalhistory-whs';
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('polocalhistory-whs');
                $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
                $data = VwHistoryLocal::select(
                    'ID',
                    'Number',
                    'NRP',
                    'PurchaseOrderCreator',
                    'Vendor',
                    'VendorCode_new',
                    'VendorCode',
                    'POID',
                    'Date',
                    'ReleaseDate',
                    'DeliveryDate',
                    'ParentID',
                    'ItemNumber',
                    'Material',
                    'MaterialVendor',
                    'Description',
                    'Quantity',
                    'totalgr',
                    'ConfirmedDate',
                    'ConfirmedQuantity',
                    'ActiveStage'
                )->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $countNewpoPolocal       = $NewpoPolocal->count();
                $countOngoingPolocal     = $OngoingPolocal->count();
                $countHistoryPolocal     = $data->count();
                $countplanDelivery      = $planDelivery->count();
                $countreadyToDelivery    = $readyToDelivery->count();

                return view(
                    'po-tracking/polocal/pohistory-nonmanagement',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
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

    // PO All- View gr History
    public function view_detailgrhistory(Request $request)
    {
        $data    = PdiHistory::where('POID', $request->number)->where('ItemNumber', $request->item)->get();
        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }
    //Poreturn
    public function Poreturn(Request $request)
    {
        $data = Pdi::where('ID', $request->ID)->where('POID', $request->POID)->first();
        if ($this->PermissionActionMenu('polocalhistory')) {
            $links =  'polocalhistory';
        } else if ($this->PermissionActionMenu('polocalhistory-vendor')) {
            $links =  'polocalhistory-vendor';
        } else {
            return redirect()->back()->with('err_message', 'Acces Denied!');
        }

        if (!empty($data)) {
            $poreturn = Pdi::where('POID', $request->POID)->where('IsClosed', 'C')
                ->update([
                    'ActiveStage' => NULL,
                    'IsClosed' => NULL,
                    'LastModifiedBy' => 'Reverse PO',
                ]);
            if ($poreturn) {
                return redirect($links)->with('suc_message', 'Reverse PO Success!');
            } else {
                return redirect()->back()->with('err_message', 'Reverse PO gagal !');
            }
        } else {
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }
}
