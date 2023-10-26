<?php

namespace App\Http\Controllers\Cms\PoTracking\POSubcontractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PP;
use App\Models\Table\PoTracking\ReasonSubCont;
use App\Models\Table\PoTracking\SubcontLeadtimeMaster;
use App\Models\Table\PoTracking\ParkingInvoiceDocument;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\Ticket;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\ParkingInvoice;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\Table\PoTracking\Users;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\VwUserRoleGroup;
use Exception;
use Response;
use Illuminate\Support\Carbon;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\Table\PoTracking\Qc;
use App\Models\Table\PoTracking\DetailQc;
use App\Models\Table\PoTracking\DisabledDays;
use App\Models\Table\PoTracking\Ngphoto;

class OngoingController extends Controller
{

    // subcontractor

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('subcontractorongoing') == 0 && 
                $this->PermissionMenu('subcontractorongoing-management') == 0 && 
                $this->PermissionMenu('subcontractorongoing-nonmanagement') == 0  && 
                $this->PermissionMenu('subcontractorongoing-whs') == 0 && 
                $this->PermissionMenu('subcontractorongoing-proc') == 0 && 
                $this->PermissionMenu('subcontractorongoing-vendor') == 0 && 
                $this->PermissionMenu('historyparking') == 0 && 
                $this->PermissionMenu('historyparkingsubcontractor') == 0 && 
                $this->PermissionMenu('historyparkingsubcontractor-proc') == 0 && 
                $this->PermissionMenu('historyparkingsubcontractor-vendor') == 0 && 
                $this->PermissionMenu('historyparkingsubcontractor-nonmanagement') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    // PO Subcont Ongiong View Manageement
    public function subcontractorongoing()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing')->r == 1) {
                $date   = Carbon::now();

                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Display Ongoing',
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
                $header_title                   = "PO SUBCONT - ONGOING";
                $link_newPO                     = 'subcontractornewpo';
                $link_ongoing                   = 'subcontractorongoing';
                $link_planDelivery              = 'subcontractorplandelivery';
                $link_readyToDelivery           = 'subcontractorreadydelivery';
                $link_historyPO                 = 'subcontractorhistory';
                $link_historyParking            = 'historyparkingsubcontractor';

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorongoing');

                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->count();
                $data  = VwSubcontOngoing::select(
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
                    'ConfirmedItem',
                    'totalticket',
                    'totalgr',
                    'totalir',
                    'totalparking',
                    'ActualQuantity',
                    'OpenQuantity',
                    'PrimerActualDate'
                )->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->count();

                $number_potracking = VwSubcontOngoing::select('Number')->distinct()->get()->toArray();
                $new_number = MigrationPO::whereIn('submi', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($new_number as $b) {
                        if ($a->Number == $b->submi) {
                            $a->setAttribute('Number', $b->ebeln);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $old_number = MigrationPO::whereIn('ebeln', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($old_number as $b) {
                        if ($a->Number == $b->ebeln) {
                            $a->setAttribute('Number_old', $b->submi);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $data->count();
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;
                return view(
                    'po-tracking/subcontractor/subcontractorongoing',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'link_historyParking',
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
    // PO Subcont Ongiong View Manageement
    public function subcontractorongoingManagement()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing-management')->r == 1) {
                $date   = Carbon::now();

                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Display Ongoing',
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
                $header_title                   = "PO SUBCONT - ONGOING";
                $link_newPO                     = 'subcontractornewpo-management';
                $link_ongoing                   = 'subcontractorongoing-management';
                $link_planDelivery              = 'subcontractorplandelivery-management';
                $link_readyToDelivery           = 'subcontractorreadydelivery-management';
                $link_historyPO                 = 'subcontractorhistory-management';

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorongoing-management');
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber')->count();
                $data  = VwSubcontOngoing::select(
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
                    'ConfirmedItem',
                    'totalticket',
                    'totalgr',
                    'totalir',
                    'totalparking',
                    'ActualQuantity',
                    'OpenQuantity',
                    'PrimerActualDate'
                )->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->count();

                $number_potracking = VwSubcontOngoing::select('Number')->distinct()->get()->toArray();
                $new_number = MigrationPO::whereIn('submi', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($new_number as $b) {
                        if ($a->Number == $b->submi) {
                            $a->setAttribute('Number', $b->ebeln);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $old_number = MigrationPO::whereIn('ebeln', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($old_number as $b) {
                        if ($a->Number == $b->ebeln) {
                            $a->setAttribute('Number_old', $b->submi);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $data->count();
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;
                return view(
                    'po-tracking/subcontractor/subcontractorongoing',
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
    // PO Subcont Ongiong View Non Management
    public function subcontractorongoingnonManagement()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing-nonmanagement')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Display Ongoing',
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
                $header_title                   = "PO SUBCONT - ONGOING";
                $link_newPO                     = 'subcontractornewpo-nonmanagement';
                $link_ongoing                   = 'subcontractorongoing-nonmanagement';
                $link_planDelivery              = 'subcontractorplandelivery-nonmanagement';
                $link_readyToDelivery           = 'subcontractorreadydelivery-nonmanagement';
                $link_historyPO                 = 'subcontractorhistory-nonmanagement';
                $link_historyParking            = 'historyparkingsubcontractor-nonmanagement';
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorongoing-nonmanagement');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber')->count();
                $data  = VwSubcontOngoing::select(
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
                    'ConfirmedDate',
                    'ConfirmedQuantity',
                    'ActiveStage',
                    'ConfirmedItem',
                    'totalticket',
                    'totalgr',
                    'totalir',
                    'totalparking',
                    'ActualQuantity',
                    'OpenQuantity',
                    'PrimerActualDate'
                )->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->count();

                $number_potracking = VwSubcontOngoing::select('Number')->distinct()->get()->toArray();
                $new_number = MigrationPO::whereIn('submi', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($new_number as $b) {
                        if ($a->Number == $b->submi) {
                            $a->setAttribute('Number', $b->ebeln);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $old_number = MigrationPO::whereIn('ebeln', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($old_number as $b) {
                        if ($a->Number == $b->ebeln) {
                            $a->setAttribute('Number_old', $b->submi);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $data->count();
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;
                return view(
                    'po-tracking/subcontractor/subcontractorongoingnonmanagement',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'link_historyParking',
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
    // PO Subcont Ongiong View Proc
    public function subcontractorongoingProc(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing-proc')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Display Ongoing',
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
                $header_title                   = "PO SUBCONT - ONGOING";
                $link_newPO                     = 'subcontractornewpo-proc';
                $link_ongoing                   = 'subcontractorongoing-proc';
                $link_planDelivery              = 'subcontractorplandelivery-proc';
                $link_readyToDelivery           = 'subcontractorreadydelivery-proc';
                $link_historyPO                 = 'subcontractorhistory-proc';
                $link_historyParking            = 'historyparkingsubcontractor-proc';
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorongoing-proc');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->count();
                if ($request->Number) {
                    $data  = VwSubcontOngoing::select(
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
                        'ConfirmedItem',
                        'totalticket',
                        'totalgr',
                        'totalir',
                        'totalparking',
                        'ActualQuantity',
                        'OpenQuantity',
                        'PrimerActualDate'
                    )->where('Number', $request->Number)->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                } else {
                    $data  = VwSubcontOngoing::select(
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
                        'ConfirmedItem',
                        'totalticket',
                        'totalgr',
                        'totalir',
                        'totalparking',
                        'ActualQuantity',
                        'OpenQuantity'
                    )->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                }

                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->count();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kodex)->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();

                $number_potracking = VwSubcontOngoing::select('Number')->distinct()->get()->toArray();
                $new_number = MigrationPO::whereIn('submi', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($new_number as $b) {
                        if ($a->Number == $b->submi) {
                            $a->setAttribute('Number', $b->ebeln);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $old_number = MigrationPO::whereIn('ebeln', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($old_number as $b) {
                        if ($a->Number == $b->ebeln) {
                            $a->setAttribute('Number_old', $b->submi);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $data->count();
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;
                return view(
                    'po-tracking/subcontractor/subcontractorongoingproc',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'link_historyParking',
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
    // PO Subcont Ongiong View Venodor
    public function subcontractorongoingVendor(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing-vendor')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Display Ongoing',
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
                $header_title                   = "PO SUBCONT - ONGOING";
                $link_newPO                     = 'subcontractornewpo-vendor';
                $link_ongoing                   = 'subcontractorongoing-vendor';
                $link_planDelivery              = 'subcontractorplandelivery-vendor';
                $link_readyToDelivery           = 'subcontractorreadydelivery-vendor';
                $link_historyPO                 = 'subcontractorhistory-vendor';
                $link_historyParking            = 'historyparkingsubcontractor-vendor';

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorongoing-vendor');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->count();
                if ($request->Number) {
                    $data  = VwSubcontOngoing::select(
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
                        'PrimerActualDate',
                        'ConfirmedItem',
                        'totalticket',
                        'totalgr',
                        'totalir',
                        'totalparking',
                        'ActualQuantity',
                        'OpenQuantity',
                        'PrimerActualDate'
                    )->where('Number', $request->Number)->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                } else {
                    $data  = VwSubcontOngoing::select(
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
                        'PrimerActualDate',
                        'ConfirmedItem',
                        'totalticket',
                        'totalgr',
                        'totalir',
                        'totalparking',
                        'ActualQuantity',
                        'OpenQuantity',
                        'PrimerActualDate'
                    )->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                }

                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->count();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kodex)->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();
                $number_potracking = VwSubcontOngoing::select('Number')->distinct()->get()->toArray();
                $new_number = MigrationPO::whereIn('submi', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($new_number as $b) {
                        if ($a->Number == $b->submi) {
                            $a->setAttribute('Number', $b->ebeln);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $old_number = MigrationPO::whereIn('ebeln', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($old_number as $b) {
                        if ($a->Number == $b->ebeln) {
                            $a->setAttribute('Number_old', $b->submi);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $data->count();
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;
                return view(
                    'po-tracking/subcontractor/subcontractorongoingvendor',
                    compact(
                        'data',
                        'header_title',
                        'link_newPO',
                        'link_ongoing',
                        'link_planDelivery',
                        'link_readyToDelivery',
                        'link_historyPO',
                        'link_historyParking',
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
    // PO Subcont Ongiong View Non Management
    public function subcontractorongoingWhs()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing-whs')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Display Ongoing',
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
                $header_title                   = "PO SUBCONT - ONGOING";
                $link_newPO                     = 'subcontractornewpo-whs';
                $link_ongoing                   = 'subcontractorongoing-whs';
                $link_planDelivery              = 'subcontractorplandelivery-whs';
                $link_readyToDelivery           = 'subcontractorreadydelivery-whs';
                $link_historyPO                 = 'subcontractorhistory-whs';
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorongoing-whs');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->whereIn('Plant', $plant)->count();
                $data  = VwSubcontOngoing::select(
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
                    'ConfirmedDate',
                    'ConfirmedQuantity',
                    'ActiveStage',
                    'ConfirmedItem',
                    'totalticket',
                    'totalgr',
                    'totalir',
                    'totalparking',
                    'ActualQuantity',
                    'OpenQuantity',
                    'PrimerActualDate'
                )->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->count();

                $number_potracking = VwSubcontOngoing::select('Number')->distinct()->get()->toArray();
                $new_number = MigrationPO::whereIn('submi', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($new_number as $b) {
                        if ($a->Number == $b->submi) {
                            $a->setAttribute('Number', $b->ebeln);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $old_number = MigrationPO::whereIn('ebeln', $number_potracking)->select('ebeln', 'submi')->distinct()->get();
                foreach ($data as $a) {
                    foreach ($old_number as $b) {
                        if ($a->Number == $b->ebeln) {
                            $a->setAttribute('Number_old', $b->submi);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $data->count();
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;
                return view(
                    'po-tracking/subcontractor/subcontractorongoingnonmanagement',
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

    public function Historyparking()
    {
        try {
            if ($this->PermissionActionMenu('historyparkingsubcontractor')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY PARKING";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO History Parking',
                        'description' => 'Display PO History Parking Subcont',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
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
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('historyparkingsubcontractor');
                $data = ParkingInvoice::groupBy('created_at')->orderBy('Number')
                    ->get();
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->count();
                $OngoingSubcont  =  VwSubcontOngoing::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('ItemNumber', 'Number', 'TicketID')->whereIn('status', $kodex)->count();


                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $OngoingSubcont;
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;

                return view(
                    'po-tracking/subcontractor/historyparking',
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

    public function HistoryparkingVendor()
    {
        try {
            if ($this->PermissionActionMenu('historyparkingsubcontractor-vendor')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY PARKING";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO History Parking',
                        'description' => 'Display PO History Parking Subcont',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
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

                $actionmenu =  $this->PermissionActionMenu('historyparkingsubcontractor-vendor');
                $NewpoSubcont = VwSubcontNewpo::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('Number', 'asc')->get();
                $OngoingSubcont = VwSubcontOngoing::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
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
                $historyPO = VwSubcontHistory::select('ID', 'Number', 'NRP', 'PurchaseOrderCreator', 'Vendor', 'VendorCode_new', 'VendorCode', 'POID', 'Date', 'ReleaseDate', 'DeliveryDate', 'ParentID', 'ItemNumber', 'Material', 'MaterialVendor', 'Description', 'NetPrice', 'Currency', 'Quantity', 'ConfirmedDate', 'totalgr', 'ConfirmedQuantity', 'ActiveStage', 'IsClosed')
                    ->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $data = ParkingInvoice::where('created_by', Auth::user()->email)->groupBy('created_at')->orderBy('Number')
                    ->get();
                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $historyPO->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/historyparking',
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

    public function HistoryparkingProc()
    {
        try {
            if ($this->PermissionActionMenu('historyparkingsubcontractor-proc')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY PARKING";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO History Parking',
                        'description' => 'Display PO History Parking Subcont',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
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

                $kodex = ['A', 'D'];

                $actionmenu =  $this->PermissionActionMenu('historyparkingsubcontractor-proc');
                $NewpoSubcont = VwSubcontNewpo::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('Number', 'asc')->get();
                $OngoingSubcont = VwSubcontOngoing::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $historyPO = VwSubcontHistory::select('ID', 'Number', 'NRP', 'PurchaseOrderCreator', 'Vendor', 'VendorCode_new', 'VendorCode', 'POID', 'Date', 'ReleaseDate', 'DeliveryDate', 'ParentID', 'ItemNumber', 'Material', 'MaterialVendor', 'Description', 'NetPrice', 'Currency', 'Quantity', 'ConfirmedDate', 'totalgr', 'ConfirmedQuantity', 'ActiveStage', 'IsClosed')
                    ->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                if (Auth::user()->email == 'PROC-S-11') {
                    $data =   ParkingInvoice::groupBy('created_at')->orderBy('Number')->get();
                } else {
                    $data =   ParkingInvoice::select('parkinginvoice.*')->leftjoin('po', 'po.Number', '=', 'parkinginvoice.Number')
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->groupBy('created_at')->orderBy('Number')->get();
                }
                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $historyPO->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/historyparking',
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

    public function HistoryparkingnonManagement()
    {
        try {
            if ($this->PermissionActionMenu('historyparkingsubcontractor-nonmanagement')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY PARKING";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO History Parking',
                        'description' => 'Display PO History Parking Subcont',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
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
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('historyparkingsubcontractor-nonmanagement');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->count();
                $OngoingSubcont  =  VwSubcontOngoing::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->distinct('Number', 'ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('ItemNumber', 'Number', 'TicketID')->whereIn('status', $kodex)->count();
                if (Auth::user()->email == 'adm.proc2@patria.co.id') {
                    $data =   ParkingInvoice::groupBy('created_at')->orderBy('Number')->get();
                }else{
                    $data =   ParkingInvoice::select('parkinginvoice.*')->leftjoin('po', 'po.Number', '=', 'parkinginvoice.Number')
                    ->where('po.CreatedBy', Auth::user()->email)
                    ->groupBy('created_at')->orderBy('Number')->get();
                }


                $countNewpoSubcont       = $NewpoSubcont;
                $countOngoingSubcont     = $OngoingSubcont;
                $countHistorySubcont     = $HistorySubcont;
                $countPlanDeliverySubcont      = $planDelivery;
                $countReadyToDeliverySubcont    = $readyToDelivery;

                return view(
                    'po-tracking/subcontractor/historyparking',
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

    //PO SubContractor - OnGoingPO (Insert Subcont Proforma Invoice)
    public function proformasubcontractor(Request $request)
    {
        try {
            // Need or Skip Proforma
            if (!empty($request->ID) || !empty($request->Number)) {
                $datapo = VwSubcontOngoing::where('Number', $request->Number)->first();
                if ($this->PermissionActionMenu('subcontractorongoing')) {
                    $link = "subcontractorongoing";
                } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                    $link = "subcontractorongoing-vendor";
                } else if ($this->PermissionActionMenu('subcontractorongoing-proc')) {
                    $link = "subcontractorongoing-proc";
                } else {
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                $date   = Carbon::now();
                if ($request->ActiveStage == '2') {
                    $appsmenu = VwSubcontOngoing::where('Number', $request->Number)->get();
                    if (!empty($appsmenu)) {
                        foreach ($appsmenu as $p) {
                            $id[] = $p->ID;
                        }
                        if ($request->action == "Need") {
                            $create = Pdi::whereIn('ID', $id)
                                ->update([
                                    'ActiveStage' => '2a',
                                ]);
                            Notification::create([
                                'Number'         => $datapo->Number,
                                'Subjek'         => "Proforma",
                                'user_by' => Auth::user()->name,
                                'user_to' => $datapo->Vendor,
                                'is_read' => 1,
                                'menu' => "subcontractorongoing-vendor",
                                'comment' => "RProforma PO $datapo->Number",
                                'created_at' => $date
                            ]);
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Subcont Ongoing',
                                'description' => 'Proforma',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $datapo->Number,
                                'poitem' => $datapo->ItemNumber,
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Subcont',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                        } else {
                            $create = Pdi::whereIn('ID', $id)
                                ->update([
                                    'ActiveStage' => 3,
                                ]);
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Subcont Ongoing',
                                'description' => 'Skip Proforma',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $datapo->Number,
                                'poitem' => $datapo->ItemNumber,
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Subcont',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                        }
                        if ($create) {
                            return redirect($link)->with('suc_message', 'PO Proforma Approve!');
                        } else {
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    } else {
                        return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                    }
                }
                // Upload Document Proforma
                elseif ($request->ActiveStage == '2a') {
                    $this->validate($request, [
                        'filename' => 'required|mimes:PDF,pdf|max:5120'
                    ]);
                    $appsmenu = VwSubcontOngoing::where('Number', $request->Number)->get();
                    if (!empty($appsmenu)) {
                        $file           =  $request->file('filename');
                        $nama_file      = time() . "_" . $file->getClientOriginalName();
                        // isi dengan nama folder tempat kemana file diupload
                        $tujuan_upload  = 'public/potracking/uploudproforma';
                        $file->move($tujuan_upload, $nama_file);

                        foreach ($appsmenu as $p) {
                            $id[] = $p->ID;
                        }

                        $create = Pdi::whereIn('ID', $id)
                            ->update([
                                'ActiveStage'                   => '2b',
                                'InvoiceMethod'                 => $request->invoice_no,
                                'ConfirmReceivedPaymentDate'    => $request->invoice_date,
                                'InvoiceMethod'                 => $request->invoice_no,
                                'TaxInvoice'                    => $request->tax_invoice_no,
                                'ProformaInvoiceDocument'       => $nama_file,
                            ]);
                        Notification::where('Number', $datapo->Number)->where('Subjek', 'Proforma')->orWhere('Subjek', 'Revisi Proforma')
                            ->update([
                                'is_read' => 3,
                            ]);
                        Notification::create([
                            'Number'         => $datapo->Number,
                            'Subjek'         => "Request Proforma",
                            'user_by' => Auth::user()->name,
                            'user_to' => $datapo->NRP,
                            'is_read' => 1,
                            'menu' => "subcontractorongoing-proc",
                            'comment' => "Proforma PO NO.$datapo->Number",
                            'created_at' => $date
                        ]);
                        LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Subcont Ongoing',
                            'description' => 'Request Proforma',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $datapo->Number,
                            'poitem' => $datapo->ItemNumber,
                            'userlogintype' => Auth::user()->title,
                            'vendortype' => 'Subcont',
                            'CreatedBy'  => Auth::user()->name,
                        ]);
                        if ($create) {
                            return redirect($link)->with('suc_message', 'Proforma Berhasi Disimpan!');
                        } else {
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    } else {
                        return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                    }
                }
                // Accept/Revision Document Proforma
                else if ($request->ActiveStage == '2b') {
                    $appsmenu = VwSubcontOngoing::where('Number', $request->Number)->get();
                    $getpdf = VwSubcontOngoing::where('Number', $request->Number)->first();

                    if (!empty($appsmenu)) {
                        if ($request->action == "Save") {
                            $file =  $request->file('filename');
                            $nama_file = time() . "_" . $file->getClientOriginalName();

                            // isi dengan nama folder tempat kemana file diupload
                            $tujuan_upload = 'public/potracking/uploudproforma';
                            $file->move($tujuan_upload, $nama_file);
                            $this->validate($request, [
                                'confirm_date' => 'required'
                            ]);
                            foreach ($appsmenu as $p) {
                                $id[] = $p->ID;
                            }
                            $create = Pdi::whereIn('ID', $id)->update(['ActiveStage' => 3, 'ApproveProformaInvoiceDocument' => $nama_file,]);
                            Notification::where('Number', $datapo->Number)->where('Subjek', 'Request Proforma')
                                ->update([
                                    'is_read' => 3,
                                ]);
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Subcont Ongoing',
                                'description' => 'Approve Proforma',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $datapo->Number,
                                'poitem' => $datapo->ItemNumber,
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Subcont',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                            if ($create) {
                                return redirect($link)->with('suc_message', 'Proforma disetujui!');
                            } else {
                                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                            }
                        } elseif ($request->action == "Download") {
                            $file = "public/potracking/uploudproforma/$getpdf->ProformaInvoiceDocument";
                            $headers = array(
                                'Content-Type: application/pdf',
                            );
                            return Response::download($file, $getpdf->ProformaInvoiceDocument, $headers);
                        } elseif ($request->action == "Revisi") {
                            foreach ($appsmenu as $p) {
                                $id[] = $p->ID;
                            }
                            $create = Pdi::whereIn('ID', $id)
                                ->update([
                                    'ActiveStage' => '2a',

                                ]);
                            Notification::where('Number', $datapo->Number)->where('Subjek', 'Request Proforma')
                                ->update([
                                    'is_read' => 3,
                                ]);
                            Notification::create([
                                'Number'         => $datapo->Number,
                                'Subjek'         => "Revisi Proforma",
                                'user_by' => Auth::user()->name,
                                'user_to' => $datapo->Vendor,
                                'is_read' => 1,
                                'menu' => "subcontractorongoing-vendor",
                                'comment' => "Revisi Proforma PO NO.$datapo->Number",
                                'created_at' => $date
                            ]);
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Subcont Ongoing',
                                'description' => 'Revisi Proforma',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $datapo->Number,
                                'poitem' => $datapo->ItemNumber,
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Subcont',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                            if ($create) {
                                return redirect($link)->with('suc_message', 'Proforma harus direvisi!');
                            } else {
                                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                            }
                        }
                    } else {
                        return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                    }
                } else {
                    return redirect()->back()->with('err_message', 'Terjadi Kesalahan!');
                }
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //parking or approve parking
    public function Parkinginvoice(Request $request)
    {
        try {
            $appsmenu = VwongoingAll::where('Number', $request->Number)->first();
            if ($appsmenu != null) {
                if ($this->PermissionActionMenu('subcontractorongoing')) {
                    $link = "subcontractorongoing";
                } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                    $link = "subcontractorongoing-vendor";
                } else {
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                $date   = Carbon::now();
                if ($request->Update == "Approve Parking?") {

                    $header = ParkingInvoice::where('Number', $request->Number)->first();
                    $body = ParkingInvoice::where('Number', $request->Number)->get();
                    $no = 0;
                    foreach($body as $item){
                        $textbody[] = [
                            'INVOICE_DOC_ITEM'=> str_pad($no+1, 6, "0", STR_PAD_LEFT),
                            'PO_NUMBER'=> $header->Number,
                            'PO_ITEM'=> str_pad($item->ItemNumber, 5, "0", STR_PAD_LEFT),
                            'REF_DOC'=> $header->Reference,
                            'REF_DOC_YEAR'=> '2023',
                            'REF_DOC_IT'=> str_pad($no+1, 4, "0", STR_PAD_LEFT),
                            'TAX_CODE'=> 'M4',
                            'ITEM_AMOUNT'=> $item->Amount,
                            'QUANTITY'=> $item->Qty,
                            'PO_UNIT'=> 'PCS',
                            'ITEM_TEXT'=> 'TES PO'.$header->Number.'ITEM'.$item->ItemNumber,
                            'FINAL_INV'=> 'X'
                        ];
                        $no++;
                    }

                    $textfull = [
                        'INVOICE_IND' => 'X',
                        'DOC_DATE' => Carbon::parse($header->InvoiceDate)->format('d.m.Y'),
                        'PSTNG_DATE' => Carbon::now()->format('d.m.Y'),
                        'REF_DOC_NO' => $header->Reference,
                        'COMP_CODE' => '1000',
                        'CURRENCY' => 'IDR',
                        'GROSS_AMOUNT' => $header->Amount,
                        'CALC_TAX_IND' => 'X',
                        'HEADER_TXT' => $header->Headertext,
                        'ALLOC_NMBR' => $header->Number,
                        'ITEM_TEXT'  => 'PO'.$header->Number,
                        'WI_TAX_TYPE'=> '',
                        'WI_TAX_CODE'=> '',
                        'ITEMDATA' => $textbody
                    ];
                    echo json_encode($textfull);
                    // dd('test_subcont',$textfull);

                    // $create1 = ParkingInvoice::where('Number', $request->Number)->where('Status', "Request Parking")
                    //     ->update([
                    //         'Status' => "Approve Parking",
                    //         'updated_at' => $date
                    //     ]);
                    // Notification::where('Number', $request->Number)->where('menu', 'Request Parking')
                    //     ->update([
                    //         'is_read' => 3,
                    //     ]);
                    // Notification::create([
                    //     'Number'         => $appsmenu->Number,
                    //     'Subjek'         => "Approve Parking",
                    //     'user_by' => Auth::user()->name,
                    //     'user_to' => $appsmenu->Vendor,
                    //     'is_read' => 1,
                    //     'menu' => "historyparking",
                    //     'comment' => "Approve Parking PO.No $appsmenu->Number",
                    //     'created_at' => $date
                    // ]);
                    // if ($create1) {
                    //     return redirect('historyparkingsubcontractor')->with('suc_message', 'Parking Invoice Berhasil di Approve!');
                    // } else {
                    //     return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    // }
                } else {
                    $request->validate([
                        'filename.*' => 'required|mimes:PDF,pdf|max:5120',
                    ]);

                    $data = count($request->Qty);

                    for ($i = 0; $i < $data; $i++) {

                        $insert = [
                            'Number'            => $request->Number,
                            'ItemNumber'        => $request->ItemNumber[$i],
                            'Material'          => $request->Material[$i],
                            'Description'       => $request->Description[$i],
                            'Qty'               => $request->Qty[$i],
                            'InvoiceDate' =>    Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d'),
                            'InvoiceNumber' =>   $request->invoice_no,
                            'Amount' =>          $request->amount,
                            'Assignment' =>      $request->Assignment,
                            'HeadertText' =>     $request->headertext,
                            'Status' =>          "Request Parking",
                            'created_by' =>          Auth::user()->name,
                            'Reference' =>       $request->reference
                        ];
                        $create1 = ParkingInvoice::insert($insert);
                    }
                    foreach ($request->file('filename') as $parkingdocument) {
                        if ($parkingdocument->isValid()) {
                            $parkingdocumentName =  Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d') . '_' . str_replace(' ', '_', $parkingdocument->getClientOriginalName());
                            if (!file_exists(public_path('potracking/parking_invoice/' . $request->Number))) {
                                mkdir(public_path('potracking/parking_invoice/' . $request->Number), 0777, true);
                            }
                            $parkingdocument->move(public_path('potracking/parking_invoice/' . $request->Number), $parkingdocumentName);
                        }
                        $insert = [
                            'Number'            => $request->Number,
                            'FileName' =>        $parkingdocumentName,
                            'CreatedBy' =>      Auth::user()->name
                        ];
                        $create1 = ParkingInvoiceDocument::insert($insert);
                    }
                    Notification::create([
                        'Number'         => $appsmenu->Number,
                        'Subjek'         => "Request Parking",
                        'user_by' => Auth::user()->name,
                        'user_to' => $appsmenu->NRP,
                        'is_read' => 1,
                        'menu' => "historyparking",
                        'comment' => "Request Parking PO.No $appsmenu->Number",
                        'created_at' => $date
                    ]);

                    if ($create1) {
                        return redirect($link)->with('suc_message', 'Parking Invoice Berhasil!');
                    } else {
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    //PO SubContractor - OnGoingPO (View Subcont Proforma Invoice)
    public function view_proformasubcontractor(Request $request)
    {

        $dataid     = VwSubcontOngoing::where('ID', $request->id)->first();
        $data       = VwSubcontOngoing::where('Number', $dataid->Number)->where('ParentID', '!=', 'null')->groupBy('Number', 'ItemNumber', 'Quantity')->get();
        $data = array(
            'data'          => $data,
            'dataid'        => $dataid
        );

        echo json_encode($data);
    }
    //PO SubContractor - OngoingPO (View Subcont Progress Sequence)
    public function viewsubcontractorsequence(Request $request)
    {
        $dataid         = VwSubcontOngoing::where('ID', $request->id)->orWhere('ParentID', $request->id)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
        $dataall        = VwSubcontOngoing::where('ID', $request->id)->orWhere('ParentID', $request->id)->get();
        $reasonid       = ReasonSubCont::all();
        $dataleadtime   = SubcontLeadtimeMaster::all();


        $a = array();
        for ($i = 0; $i < count($dataall); $i++) {
            $photoprogress  = PP::where('PurchasingDocumentItemID', $dataall[$i]->ID)->get();
            if (!empty($photoprogress)) {
                array_push($a, $photoprogress);
            }
        }
        $data = array(
            'dataid'        => $dataid,
            'dataall'       => $dataall,
            'reasonsubcont' => $reasonid,
            'dataleadtime'  => $dataleadtime,
            'photoprogress' => $a

        );

        echo json_encode($data);
    }
    //PO SubContractor - OngoingPO (Insert Subcont Progress Sequence)
    public function subcontractorleadtime(Request $request)
    {
        try {
            $appsmenu = Pdi::where('ID', $request->ID)->first();
            if ($this->PermissionActionMenu('subcontractorongoing')) {
                $link = "subcontractorongoing";
            } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                $link = "subcontractorongoing-vendor";
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            if (!empty($appsmenu)) {
                $date   = Carbon::now();
                $datapo =  VwSubcontOngoing::select('Number', 'ItemNumber')->where('ID', $request->ID)->first();

                if ($request->action == "PB") { //Untuk submit PB Progress
                    $request->validate([
                        'PBfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach ($request->PBfoto as $pbfoto) {
                        $imageName = uniqid() . '_' . time() . '_PB_' . $pbfoto->getClientOriginalName();
                        $pbfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
                            'PONumber'                  => $datapo->Number,
                            'ItemNumber'                => $datapo->ItemNumber,
                            'FileName'                  => trim($imageName),
                            'CreatedBy'                 => "System",
                            'ProcessName'               => "PB",
                        ];
                        $update = PP::insert($newinsert);
                    }
                    if ($appsmenu->ActiveStage == null) { //JIKA PROSES DARI SAP
                        $leadtimeitem       = $appsmenu->WorkTime;
                        $dataLeadTime       = SubcontLeadtimeMaster::all();
                        $totalleadtime      = ($leadtimeitem - 1);

                        $PB         = round($totalleadtime * $dataLeadTime[0]->Value);
                        $Setting    = round($totalleadtime * $dataLeadTime[1]->Value);
                        $Fullweld   = round($totalleadtime * $dataLeadTime[2]->Value);
                        $Primer     = round($totalleadtime * $dataLeadTime[3]->Value);

                        $update =  Pdi::where('ID', $request->ID)
                            ->update([
                                'ActiveStage'       => "3a",
                                'PB'                => $PB,
                                'Setting'           => $Setting,
                                'Fullweld'          => $Fullweld,
                                'Primer'            => $Primer,
                                'PBActualDate'      => Carbon::createFromFormat('d/m/Y', trim($request->PBActualDate))->format('Y-m-d'),
                                'PBLateReasonID'    => trim($request->PBreasonID),
                            ]);
                    } else {
                        $update =  Pdi::where('ID', $request->ID)
                            ->update([
                                'ActiveStage'       => "3a",
                                'PBActualDate'      => Carbon::createFromFormat('d/m/Y', trim($request->PBActualDate))->format('Y-m-d'),
                                'PBLateReasonID'    => trim($request->PBreasonID),
                            ]);
                    }

                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Progress PB',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);

                    if ($update) {
                        return redirect($link)->with('suc_message', 'PB Progress saved successfully!');
                    } else {
                        return redirect()->back()->with('err_message', 'PB Progress failed to save!');
                    }
                }

                if ($request->action == "Setting") { //Untuk submit Setting Progress
                    $request->validate([
                        'Settingfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach ($request->Settingfoto as $settingfoto) {
                        $imageName = uniqid() . '_' . time() . '_Setting_' . $settingfoto->getClientOriginalName();
                        $settingfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
                            'PONumber'                  => $datapo->Number,
                            'ItemNumber'                => $datapo->ItemNumber,
                            'FileName'                  => trim($imageName),
                            'CreatedBy'                 => "System",
                            'ProcessName'               => "Setting",
                        ];
                        $update = PP::insert($newinsert);
                    }
                    $update =  Pdi::where('ID', $request->ID)->where('ActiveStage', '3a')
                        ->update([
                            'ActiveStage'           => "3b",
                            'SettingActualDate'     => Carbon::createFromFormat('d/m/Y', trim($request->SettingActualDate))->format('Y-m-d'),
                            'SettingLateReasonID'   => trim($request->SettingreasonID),
                        ]);
                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Progress Setting',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                    if ($update) {
                        return redirect($link)->with('suc_message', 'Setting Progress saved successfully!');
                    } else {
                        return redirect()->back()->with('err_message', 'Setting Progress failed to save!');
                    }
                }

                if ($request->action == "Fullweld") { //Untuk submit Fullweld Progress
                    $request->validate([
                        'Fullweldfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach ($request->Fullweldfoto as $fullweldfoto) {
                        $imageName = uniqid() . '_' . time() . '_Fullweld_' . $fullweldfoto->getClientOriginalName();
                        $fullweldfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
                            'PONumber'                  => $datapo->Number,
                            'ItemNumber'                => $datapo->ItemNumber,
                            'FileName'                  => trim($imageName),
                            'CreatedBy'                 => "System",
                            'ProcessName'               => "Fullweld",
                        ];
                        $update = PP::insert($newinsert);
                    }

                    $update =  Pdi::where('ID', $request->ID)->where('ActiveStage', '3b')
                        ->update([
                            'ActiveStage'       => "3c",
                            'FullweldActualDate'      => Carbon::createFromFormat('d/m/Y', trim($request->FullweldActualDate))->format('Y-m-d'),
                            'FullweldLateReasonID'    => trim($request->FullweldreasonID),
                        ]);
                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Progress Fullweld',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                    if ($update) {
                        return redirect($link)->with('suc_message', 'Fullweld Progress saved successfully!');
                    } else {
                        return redirect()->back()->with('err_message', 'Fullweld Progress failed to save!');
                    }
                }

                if ($request->action == "Primer") { //Untuk submit Primer Progress
                    $request->validate([
                        'Primerfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach ($request->Primerfoto as $primerfoto) {
                        $imageName = uniqid() . '_' . time() . '_Primer_' . $primerfoto->getClientOriginalName();
                        $primerfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
                            'PONumber'                  => $datapo->Number,
                            'ItemNumber'                => $datapo->ItemNumber,
                            'FileName'                  => trim($imageName),
                            'CreatedBy'                 => "System",
                            'ProcessName'               => "Primer",
                        ];
                        $update = PP::insert($newinsert);
                    }

                    $update =  Pdi::where('ID', $request->ID)->where('ActiveStage', '3c')
                        ->update([
                            'ActiveStage'           => "4",
                            'PrimerActualDate'      => Carbon::createFromFormat('d/m/Y', trim($request->PrimerActualDate))->format('Y-m-d'),
                            'PrimerLateReasonID'    => trim($request->PrimerreasonID),
                        ]);
                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Progress Primer',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                    if ($update) {
                        return redirect($link)->with('suc_message', 'Primer Progress saved successfully!');
                    } else {
                        return redirect()->back()->with('err_message', 'Primer Progress failed to save!');
                    }
                }
            } else {
                return redirect()->back()->with('err_message', 'Data not found!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function subcontractorcreateticket(Request $request)
    {
        //ProsesConfirmPo
        try {
            if (!empty($request->ID) || !empty($request->Number)) {
                if ($this->PermissionActionMenu('subcontractorongoing')) {
                    $link = "subcontractorongoing";
                } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                    $link = "subcontractorongoing-vendor";
                } else {
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                if ($request->ActiveStage == '4') {
                    $appsmenu       = VwSubcontOngoing::whereIn('ParentID', $request->ID)->orWhereIn('ID', $request->ID)->first();
                    $ticket         = DetailTicket::all();
                    $jumlahticket   = count($ticket);

                    if ($jumlahticket == 0) {
                        $c = 1;
                    } else {
                        $c = $jumlahticket;
                    }
                    if (!empty($appsmenu)) {
                        $data       = count($request->ID);
                        $idticket   = 'PTR/PR/' . date('Ymd') . '/' . date('His') . '/' . $appsmenu->Number . '/' . $c . '/' . $data;
                        $spbdate    = Carbon::createFromFormat('d/m/Y', $request->SPBdate)->format('Y-m-d');

                        $create1    = Ticket::create([
                            'POID' => $appsmenu->POID,
                            'ticketid' => $idticket
                        ]);
                        for ($i = 0; $i < $data; $i++) {

                            $newinsert = [
                                'PDIID'         => $request->ID[$i],
                                'Number'         => $request->number[$i],
                                'ItemNumber'         => $request->itemnumber[$i],
                                'Plant'         => $request->plant[$i],
                                'SLoc'         => $request->sloc[$i],
                                'Material'         => (!is_null($request->material[$i]) ? $request->material[$i] : "NULL"),
                                'Description'         => $request->description[$i],
                                'ConfirmDeliveryDate'  => Carbon::createFromFormat('d/m/Y', $request->deliverydatesap)->format('Y-m-d'),
                                'DeliveryDate'  => Carbon::createFromFormat('d/m/Y', $request->deliverydate)->format('Y-m-d') . ' ' . $request->deliverytime,
                                'TicketID'      => $idticket,
                                'headertext'    => $request->headertext,
                                'DeliveryNote'  => $request->DeliveryNote,
                                'CreatedBy'        => Auth::user()->name,
                                'status'        => 'P',
                                'QtySAP'         => $request->qtysap[$i],
                                'Quantity'      => $request->QtyDelivery[$request->ID[$i]][0],
                                'SPBDate'       => $spbdate
                            ];
                            $create2 = DetailTicket::insert($newinsert);
                        }
                        Notification::where('Number', $request->numbers)->where('Subjek', 'Cancel Ticket Subcont')
                            ->update([
                                'is_read' => 3,
                            ]);

                        $dataS    = VwUserRoleGroup::select('username')->where('username', '!=', $request->Name)->where('group', 30)->get();
                        foreach ($dataS as $q) {
                            $datarole[] = $q->username;
                        }

                        $datawarehouse = count($datarole);

                        $date   = Carbon::now();
                        for ($i = 0; $i < $datawarehouse; $i++) {
                            Notification::create([
                                'Number'         => $request->numbers,
                                'Subjek'         => "Ticket Subcont",
                                'user_by' => $appsmenu->Vendor,
                                'user_to' => $datarole[$i],
                                'menu' => 'subcontractorplandelivery-whs',
                                'is_read' => 1,
                                'comment' => "$idticket",
                                'created_at' => $date
                            ]);
                        }
                        LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Subcont Ongoing',
                            'description' => 'Create Ticket',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $appsmenu->Number,
                            'poitem' => $appsmenu->ItemNumber,
                            'userlogintype' => Auth::user()->title,
                            'vendortype' => 'Subcont',
                            'CreatedBy'  => Auth::user()->name,
                        ]);

                        if ($create1 && $create2) {
                            return redirect($link)->with('suc_message', 'Ticket Berhasil Dibuat !');
                        } else {
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    } else {
                        return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                    }
                } else {
                    return redirect()->back()->with('err_message', 'Terjadi Kesalahan!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data Tidak Boleh Kosong!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
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

    public function view_cekparking(Request $request)
    {
        $data = VwSubcontOngoing::where('ID', $request->id)->groupBy('Number', 'ItemNumber', 'ID')->WhereNotIn('status', ['C'])->orderBy('Status', 'asc')->get();
        $dataviewticket = VwSubcontOngoing::where('ID', $request->id)->first();
        $dataparking = ParkingInvoice::where('ID_ticket', $dataviewticket->TicketID)->get();

        $data = array(
            'dataviewticket' => $dataviewticket,
            'data' => $data,
            'dataparking' => $dataparking,
        );
        echo json_encode($data);
    }
    //cekticket
    public function view_cekticket(Request $request)
    {

        $dataid = VwSubcontOngoing::select('ID', 'Number', 'ItemNumber', 'Material', 'Description', 'DeliveryDate', 'ConfirmedDate', 'Plant', 'SLoc', 'ActiveStage', 'Quantity', 'ConfirmTicketDate')->where('vw_ongoingposubcont.ID', $request->id)->first();
        $dataticket = VwSubcontOngoing::where('Number', $dataid->Number)->where('ActiveStage', 4)->groupBy('Number', 'ItemNumber')->get();
        if ($dataid->ConfirmedItem == 1) {
            $dataall = VwSubcontOngoing::where('Number', $dataid->Number)->get();
        } else {
            $dataall = VwSubcontOngoing::where('Number', $dataid->Number)->groupBy('Number', 'ItemNumber')->get();
        }
        $days_disabled = DisabledDays::select('event_date')->where('is_active', 1)->where('is_disabled', 1)->get();
        $data = array(
            'dataid' => $dataid,
            'dataticket' => $dataticket,
            'dataall' => $dataall,
            'days_disabled' => $days_disabled
        );
        echo json_encode($data);
    }

    public function SkipProforma(Request $request)
    {
        try {
            if (!empty($request->Number)) {

                if ($this->PermissionActionMenu('subcontractorongoing')) {
                    $links = "subcontractorongoing";
                } else if ($this->PermissionActionMenu('subcontractorongoing-proc')) {
                    $links = "subcontractorongoing-proc";
                } else {
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                $date   = Carbon::now();
                $appsmenu = VwSubcontOngoing::whereIn('Number', $request->Number)->groupBy('Number')->get();
                foreach ($appsmenu as $q) {
                    $number[] = $q->Number;
                    $itemnumber[] = $q->ItemNumber;
                    $poid[] = $q->POID;
                }

                $totalpo = count($appsmenu);
                for ($i = 0; $i < $totalpo; $i++) {
                    LogHistory::updateOrCreate([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Skip Proforma',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' =>  $number[$i],
                        'poitem' => $itemnumber[$i],
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                }
                $create = Pdi::whereIn('POID', $poid)->where('ActiveStage', '2')
                    ->update([
                        'ActiveStage' => 3,
                    ]);
                if ($create) {
                    return redirect($links)->with('suc_message', 'Success Skip Proforma!');
                } else {
                    return redirect()->back()->with('err_message', 'Proforma gagal diskip!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Error Request, Exception Error');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //Downloadfileparking
    public function Downloadfile($id)
    {

        $file = "public/potracking/parking_invoice/$id";
        $headers = array('Content-Type: application/pdf',);
        return Response::download($file, $id, $headers);
    }
    //Downloadfileproforma
    public function Downloadproforma($id)
    {

        $file = "public/potracking/uploudproforma/$id";
        $headers = array('Content-Type: application/pdf',);
        return Response::download($file, $id, $headers);
    }
    //ApproveParking
    public function Approveparking(Request $request)
    {
        try {
            $data = VwViewTicket::where('TicketID', $request->TicketID)->groupBy('ID', 'ItemNumber')->WhereNotIn('status', ['C'])->orderBy('ID', 'asc')->get();
            foreach ($data as $a) {
                $id_tiket[] = $a->IDTicket;
            }

            if (!empty($data)) {
                $parking = DetailTicket::whereIn('ID', $id_tiket)
                    ->update([
                        'status' => 'X',
                    ]);
                if ($parking) {
                    return redirect('historyparking')->with('suc_message', 'Parking Accept!');
                } else {
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //UpdateData
    public function UpdateData(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('subcontractorongoing')->u == 1) {

                $update = Pdi::where('ID', $request->ID)
                    ->update([
                        'ConfirmedQuantity' => $request->qty,
                        'LastModifiedBy' => 'Update Data Ongoing',
                    ]);
                if ($update) {
                    return redirect('subcontractorongoing')->with('suc_message', 'Update Data Success!');
                } else {
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //PO SubContractor - OngoingPO (View Subcont Invitation Ticket Modal)
    public function view_invitationticket(Request $request)
    {
        $dataid = VwSubcontOngoing::select('ID', 'Number', 'ItemNumber', 'Material', 'Description', 'DeliveryDate', 'ConfirmedDate', 'ActiveStage', 'Quantity', 'Vendor', 'VendorCode')
            ->where('ID', $request->id)->first();
        $dataticket = VwSubcontOngoing::where('Number', $dataid->Number)->where('NetPrice', '>=', 10000000)->whereIn('ActiveStage', ['3d', 4])->groupBy('Number', 'ItemNumber')->get();
        foreach ($dataticket as $a) {
            $Material[] = $a->Material;
        }
        $Material = array_unique($Material);
        $gp = MaterialTemporary::whereIn('material_number', $Material)->select('group_product', 'material_number')->groupby('material_number', 'group_product')->get();
        foreach ($dataticket as $a) {
            foreach ($gp as $b) {
                if ($a->Material == $b->material_number) {
                    $a->setAttribute('group_product', $b->group_product);
                    break;
                } else {
                    $a->setAttribute('group_product', NULL);
                }
            }
        }

        if ($dataid->ConfirmedItem == 1) {
            $dataall = VwSubcontOngoing::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->get();
        } else {
            $dataall = VwSubcontOngoing::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->groupBy('Number', 'ItemNumber')->get();
        }

        $data = array(
            'dataid' => $dataid,
            'dataticket' => $dataticket,
            'dataall' => $dataall,
        );
        echo json_encode($data);
    }


    public function subcontractorcreateticketqc(Request $request)
    {
        try {
            //ProsesConfirmPo
            if (!empty($request->ID) || !empty($request->Number)) {
                if ($this->PermissionActionMenu('subcontractorongoing')) {
                    $link = "subcontractorongoing";
                } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                    $link = "subcontractorongoing-vendor";
                } else {
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }
                $appsmenu       = VwSubcontOngoing::where('ID', $request->ID)->first();
                $jumlahticket   = Qc::select('ID')->get()->count();
                $data       = count($request->ID);
                if ($jumlahticket == 0) {
                    $c = 1;
                } else {
                    $c = $jumlahticket;
                }

                if (!empty($appsmenu)) {

                    $idticket   = 'PTR/QC/' . date('Ymd') . '/' . date('His') . '/' . $appsmenu->Number . '/' . $c . '/' . $data;
                    for ($i = 0; $i < $data; $i++) {
                        $newinsert = [
                            'vendor'            => $request->vendor_name[$i],
                            'vendor_code'       => $request->vendor_code[$i],
                            'number'            => $request->Number,
                            'item'              => $request->itemnumber[$i],
                            'pn'                => (!is_null($request->material[$i]) ? $request->material[$i] : "NULL"),
                            'pn_desc'           => $request->description[$i],
                            'DeliveryDateSAP'    => $request->deliverydatesap[$i],
                            'group_product'     => $request->group_product[$i],
                            'no_ticket'         => $idticket,
                            'req_qtyinsp'       => $request->qty_insp[$i],
                            'vendor_reqdate'    => Carbon::createFromFormat('d/m/Y', $request->vendor_reqdate)->format('Y-m-d'),
                            'status'    => 'Req',
                        ];
                        $create2 = Qc::insert($newinsert);
                    }
                    if ($appsmenu->ActiveStage == '3d') {
                        Pdi::where('ID', $request->ID)->update(['ActiveStage' => '4']);
                    }

                    $date   = Carbon::now();

                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Ongoing',
                        'description' => 'Create Ticket QC',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $appsmenu->Number,
                        'poitem' => $appsmenu->ItemNumber,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);

                    if ($create2) {
                        return redirect($link)->with('suc_message', 'Ticket QC Berhasil Dibuat !');
                    } else {
                        return redirect()->back()->with('err_message', 'Ticket QC gagal dibuat!');
                    }
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Form Tidak Boleh Kosong!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    //PO SubContractor - OngoingPO (View historyinsticket Ticket Modal)
    public function historyinsticket(Request $request)
    {
        if ($this->PermissionActionMenu('subcontractorongoing')) {
            $data = Qc::select('id', 'vendor', 'number', 'item', 'pn', 'pn_desc', 'DeliveryDateSAP', 'group_product', 'no_ticket', 'req_qtyinsp', 'vendor_reqdate', 'qc_planingdate', 'qc_insdate', 'req_assignment', 'qc_visitdate', 'qtyinsp_ng', 'qtyinsp_good')->groupBy('number', 'item', 'no_ticket')->orderBy('id', 'asc')->get();
        } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
            $data = Qc::select('id', 'vendor', 'number', 'item', 'pn', 'pn_desc', 'DeliveryDateSAP', 'group_product', 'no_ticket', 'req_qtyinsp', 'vendor_reqdate', 'qc_planingdate', 'qc_insdate', 'req_assignment', 'qc_visitdate', 'qtyinsp_ng', 'qtyinsp_good')->where('vendor', Auth::user()->name)->groupBy('number', 'item', 'no_ticket')->orderBy('id', 'asc')->get();
        } else {
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }

        return view(
            'po-tracking/subcontractor/historyinsqc',
            compact(
                'data',
            )
        );
    }
    public function RepairNG(Request $request)
    {
        try {
            $request->validate([
                'foto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
            ]);

            $dataid = DetailQc::whereIn('id', $request->id)->get();
            foreach ($dataid as $items) {
                $foto = 'foto' . $items->id;
                $text_no = $items->id;
                if ($request->has($foto)) {
                    $foto = $request->$foto;
                } else {
                    $foto = NULL;
                }
                if ($foto) {
                    $data[] = [
                        'id'   => $text_no,
                        'id_ticket'   =>  $items->id_ticket,
                        'foto'        => $foto,
                        'item'        => $items->item,
                        'no'        => $items->no,

                    ];
                }
            }

            foreach ($data as $ngphoto) {
                $id[] = $ngphoto['id'];
                $id_ticket = $ngphoto['id_ticket'];
                $item = $ngphoto['item'];
                $no = $ngphoto['no'];
                $foto = $ngphoto['foto'];
                foreach ($ngphoto['foto'] as $photo) {

                    if ($photo->isValid()) {

                        $ngphotoName =  $photo->getClientOriginalName();
                        if (!file_exists(public_path('potracking/repairphoto/' . $id_ticket . '/' . $item . '/' . $no))) {
                            mkdir(public_path('potracking/repairphoto/' . $id_ticket . '/' . $item . '/' . $no), 0777, true);
                        }
                        $photo->move(public_path('potracking/repairphoto/' . $id_ticket . '/' . $item . '/' . $no), $ngphotoName);
                    }
                    $create =  Ngphoto::create([
                        'id_ticket'            => $id_ticket,
                        'item' =>        $item,
                        'no' =>        $no,
                        'name' => $ngphotoName,
                        'status' => "Repair",
                        'CreatedBy' =>    Auth::user()->name
                    ]);
                }
            }
            $update = DetailQc::whereIn('id', $id)
                ->update([
                    'status' => "Repair NG",

                ]);


            if ($create && $update) {
                return redirect('historyinvqc')->with('suc_message', 'Repair Berhasil !');
            } else {
                return redirect()->back()->with('err_message', 'Repair Gagal!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function view_cekNG(Request $request)
    {

        $dataid = Qc::select('no_ticket', 'remarks', 'qtyinsp_good', 'qtyinsp_ng')->where('no_ticket', $request->id)->first();
        $data = DetailQc::select('id', 'id_ticket', 'item', 'no', 'no_ticket', 'dimensi', 'welding', 'fungsi', 'kelengkapan', 'penampilan', 'painting', 'asembling', 'others', 'salahfisik', 'status', 'kategori', 'remarks')->where('no_ticket', $request->id)->where('item', $request->item)->get();

        foreach ($data as $a) {
            $var_photo = Ngphoto::where('id_ticket', $a->id_ticket)->where('item', $a->item)->where('status', Null)->where('no', $a->no)->get();
            if (count($var_photo) > 0) {
                foreach ($var_photo as $b) {
                    $temp_photo[] = [
                        'id' => $b->id,
                        'id_ticket' => $b->id_ticket,
                        'item' => $b->item,
                        'no' => $b->no,
                        'name' => $b->name
                    ];
                }
            }
        }

        foreach ($data as $ar) {
            $var_repairphoto = Ngphoto::where('id_ticket', $ar->id_ticket)->where('item', $ar->item)->where('no', $ar->no)->where('status', 'Repair')->get();
            if (count($var_repairphoto) > 0) {
                foreach ($var_repairphoto as $c) {
                    $temp_photorepair[] = [
                        'id' => $c->id,
                        'id_ticket' => $c->id_ticket,
                        'item' => $c->item,
                        'no' => $c->no,
                        'name' => $c->name
                    ];
                }
            }
        }

        if (isset($temp_photo) && isset($temp_photorepair)) {
            $data = array(
                'dataid' => $dataid,
                'data' => $data,
                'photo' => $temp_photo,
                'photorepair' => $temp_photorepair
            );
        } elseif (isset($temp_photo) && !isset($temp_photorepair)) {
            $data = array(
                'dataid' => $dataid,
                'data' => $data,
                'photo' => $temp_photo,
                'photorepair' => null
            );
        } else {
            $data = array(
                'dataid' => $dataid,
                'data' => $data,
                'photo' => null,
                'photorepair' => null
            );
        }
        //   dd($data);
        echo json_encode($data);
    }
}
