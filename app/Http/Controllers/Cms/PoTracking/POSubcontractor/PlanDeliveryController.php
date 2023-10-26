<?php

namespace App\Http\Controllers\Cms\PoTracking\POSubcontractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\Ticket;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\Table\PoTracking\Users;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use App\Models\View\CompletenessComponent\VwTotalStock;
use DateTime;
use Illuminate\Support\Carbon;
use Exception;

class PlanDeliveryController extends Controller
{

    // posubcontractor

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('subcontractorplandelivery') == 0 && $this->PermissionMenu('subcontractorplandelivery-proc') == 0 && $this->PermissionMenu('subcontractorplandelivery-management') == 0 && $this->PermissionMenu('subcontractorplandelivery-nonmanagement') == 0 && $this->PermissionMenu('subcontractorplandelivery-whs') == 0 && $this->PermissionMenu('subcontractorplandelivery-vendor') == 0 && $this->PermissionMenu('historyticket') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //PlanDelivery management
    public function subcontractorPlandelivery()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - PLAN DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Plan Delivery',
                        'description' => 'Display Plan Delivery',
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
                $menu_history =                 "historyticketsubcont";


                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorplandelivery');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

                $material_potracking    = DetailTicket::select('Material')->distinct()->get()->toArray();
                $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
                $ccr_stock              = VwTotalStock::whereIn('material_number', $material_potracking)->select('material_number', 'stock')->get();
                foreach ($data as $a) {
                    foreach ($ccr_reqdate as $b) {
                        if ($a->Material == $b->material) {
                            $a->setAttribute('req_date', $b->req_date);
                            break;
                        } else {
                            continue;
                        }
                    }
                    foreach ($ccr_stock as $c) {
                        if ($a->Material == $c->material) {
                            $a->setAttribute('stock', $c->stock);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $number_potracking = DetailTicket::select('Number')->distinct()->get()->toArray();
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

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $data->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/plandelivery',
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
                        'menu_history',
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
    //PlanDelivery management
    public function subcontractorPlandeliveryManagement()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery-management')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - PLAN DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Plan Delivery',
                        'description' => 'Display Plan Delivery',
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

                $link_newPO                     = 'subcontractornewpo-management';
                $link_ongoing                   = 'subcontractorongoing-management';
                $link_planDelivery              = 'subcontractorplandelivery-management';
                $link_readyToDelivery           = 'subcontractorreadydelivery-management';
                $link_historyPO                 = 'subcontractorhistory-management';
                $menu_history =                 "historyticketsubcont-management";

                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorplandelivery-management');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('detailticketingdelivery.status', 'P')->whereIn('detailticketingdelivery.Plant', $plant)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

                $material_potracking    = DetailTicket::select('Material')->distinct()->get()->toArray();
                $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
                $ccr_stock              = VwTotalStock::whereIn('material_number', $material_potracking)->select('material_number', 'stock')->get();
                foreach ($data as $a) {
                    foreach ($ccr_reqdate as $b) {
                        if ($a->Material == $b->material) {
                            $a->setAttribute('req_date', $b->req_date);
                            break;
                        } else {
                            continue;
                        }
                    }
                    foreach ($ccr_stock as $c) {
                        if ($a->Material == $c->material) {
                            $a->setAttribute('stock', $c->stock);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $number_potracking = DetailTicket::select('Number')->distinct()->get()->toArray();
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

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $data->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/plandelivery',
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
                        'menu_history',
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
    //PlanDelivery management
    public function subcontractorPlandeliveryWhs(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery-whs')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - PLAN DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Plan Delivery',
                        'description' => 'Display Plan Delivery',
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

                $link_newPO                     = 'subcontractornewpo-whs';
                $link_ongoing                   = 'subcontractorongoing-whs';
                $link_planDelivery              = 'subcontractorplandelivery-whs';
                $link_readyToDelivery           = 'subcontractorreadydelivery-whs';
                $link_historyPO                 = 'subcontractorhistory-whs';
                $menu_history                   = "historyticketsubcont-whs";


                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorplandelivery-whs');
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                if ($request->Number) {
                    $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('detailticketingdelivery.Number', $request->Number)
                        ->where('detailticketingdelivery.status', 'P')->whereIn('detailticketingdelivery.Plant', $plant)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                } else {
                    $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('detailticketingdelivery.status', 'P')->whereIn('detailticketingdelivery.Plant', $plant)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                }

                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $material_potracking    = DetailTicket::select('Material')->distinct()->get()->toArray();
                $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
                $ccr_stock              = VwTotalStock::whereIn('material_number', $material_potracking)->select('material_number', 'stock')->get();
                foreach ($data as $a) {
                    foreach ($ccr_reqdate as $b) {
                        if ($a->Material == $b->material) {
                            $a->setAttribute('req_date', $b->req_date);
                            break;
                        } else {
                            continue;
                        }
                    }
                    foreach ($ccr_stock as $c) {
                        if ($a->Material == $c->material) {
                            $a->setAttribute('stock', $c->stock);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $number_potracking = DetailTicket::select('Number')->distinct()->get()->toArray();
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
                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $data->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/plandelivery',
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
                        'menu_history',
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
    //PlanDelivery management
    public function subcontractorPlandeliveryNonManagement()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery-nonmanagement')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - PLAN DELIVERY";
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Plan Delivery',
                        'description' => 'Display Plan Delivery',
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
                $menu_history                   = "historyticketsubcont-nonmanagement";

                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorplandelivery-nonmanagement');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->whereIn('Plant', $plant)->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('detailticketingdelivery.status', 'P')->whereIn('detailticketingdelivery.Plant', $plant)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

                $material_potracking    = DetailTicket::select('Material')->distinct()->get()->toArray();
                $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
                $ccr_stock              = VwTotalStock::whereIn('material_number', $material_potracking)->select('material_number', 'stock')->get();
                foreach ($data as $a) {
                    foreach ($ccr_reqdate as $b) {
                        if ($a->Material == $b->material) {
                            $a->setAttribute('req_date', $b->req_date);
                            break;
                        } else {
                            continue;
                        }
                    }
                    foreach ($ccr_stock as $c) {
                        if ($a->Material == $c->material) {
                            $a->setAttribute('stock', $c->stock);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $number_potracking = DetailTicket::select('Number')->distinct()->get()->toArray();
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

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $data->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/plandelivery',
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
                        'menu_history',
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
    //PlanDelivery proc
    public function subcontractorPlandeliveryProc()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery-proc')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - PLAN DELIVERY";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Plan Delivery',
                        'description' => 'Display Plan Delivery',
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
                $menu_history = "historyticketsubcont-proc";

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorplandelivery-proc');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont = VwSubcontOngoing::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
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

                $material_potracking    = DetailTicket::select('Material')->distinct()->get()->toArray();
                $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
                $ccr_stock              = VwTotalStock::whereIn('material_number', $material_potracking)->select('material_number', 'stock')->get();
                foreach ($data as $a) {
                    foreach ($ccr_reqdate as $b) {
                        if ($a->Material == $b->material) {
                            $a->setAttribute('req_date', $b->req_date);
                            break;
                        } else {
                            continue;
                        }
                    }
                    foreach ($ccr_stock as $c) {
                        if ($a->Material == $c->material) {
                            $a->setAttribute('stock', $c->stock);
                            break;
                        } else {
                            continue;
                        }
                    }
                }

                $number_potracking = DetailTicket::select('Number')->distinct()->get()->toArray();
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

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $data->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();


                return view(
                    'po-tracking/subcontractor/plandelivery',
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
                        'menu_history',
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
    //PlanDelivery management
    public function subcontractorPlandeliveryVendor()
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery-vendor')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - PLAN DELIVERY";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont Plan Delivery',
                        'description' => 'Display Plan Delivery',
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
                $menu_history                   = "historyticketsubcont-vendor";

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('subcontractorplandelivery-vendor');

                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $HistorySubcont = VwSubcontHistory::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                $material_potracking    = DetailTicket::select('Material')->distinct()->get()->toArray();
                $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
                $ccr_stock              = VwTotalStock::whereIn('material_number', $material_potracking)->select('material_number', 'stock')->get();
                foreach ($data as $a) {
                    foreach ($ccr_reqdate as $b) {
                        if ($a->Material == $b->material) {
                            $a->setAttribute('req_date', $b->req_date);
                            break;
                        } else {
                            continue;
                        }
                    }
                    foreach ($ccr_stock as $c) {
                        if ($a->Material == $c->material) {
                            $a->setAttribute('stock', $c->stock);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                $number_potracking = DetailTicket::select('Number')->distinct()->get()->toArray();
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

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $data->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/plandeliveryVendor',
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
                        'menu_history',
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
    //HistoryTicket
    public function Historyticket()
    {
        try {
            if ($this->PermissionActionMenu('historyticketsubcont')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY TICKET";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont History Ticket',
                        'description' => 'Display History Ticket',
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
                $actionmenu =  $this->PermissionActionMenu('historyticketsubcont');
                $kode = ['S', 'C', 'E', 'Q'];
                //30/08/2023
                $dateS = Carbon::now()->subMonth(4);
                $dateE = Carbon::now();
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();

                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereNotIn('detailticketingdelivery.Number', [''])->whereIn('detailticketingdelivery.status', $kode)->whereBetween('detailticketingdelivery.DeliveryDate', [$dateS, $dateE])->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/subcontractorhistoryticket',
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
    //HistoryTicketManagement
    public function HistoryticketManagement()
    {
        try {
            if ($this->PermissionActionMenu('historyticketsubcont-management')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY TICKET";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont History Ticket',
                        'description' => 'Display History Ticket',
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
                $link_newPO                     = 'subcontractornewpo-management';
                $link_ongoing                   = 'subcontractorongoing-management';
                $link_planDelivery              = 'subcontractorplandelivery-management';
                $link_readyToDelivery           = 'subcontractorreadydelivery-management';
                $link_historyPO                 = 'subcontractorhistory-management';

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('historyticketsubcont-management');
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kode = ['S', 'C', 'E', 'Q'];
                //30/08/2023
                $dateS = Carbon::now()->subMonth(4);
                $dateE = Carbon::now();
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereNotIn('detailticketingdelivery.Number', [''])->whereIn('detailticketingdelivery.Plant', $plant)->whereIn('detailticketingdelivery.status', $kode)->whereBetween('detailticketingdelivery.DeliveryDate', [$dateS, $dateE])->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/subcontractorhistoryticket',
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
    //HistoryTicketNonManagement
    public function HistoryticketNonManagement()
    {
        try {
            if ($this->PermissionActionMenu('historyticketsubcont-nonmanagement')->r == 1) {
                $header_title                   = "PO SUBCONTRACTOR - HISTORY TICKET";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont History Ticket',
                        'description' => 'Display History Ticket',
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
                $actionmenu =  $this->PermissionActionMenu('historyticketsubcont-nonmanagement');
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kode = ['S', 'C', 'E', 'Q'];
                //30/08/2023
                $dateS = Carbon::now()->subMonth(4);
                $dateE = Carbon::now();
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->whereIn('Plant', $plant)->distinct('POID', 'ItemNumber')->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.Plant', $plant)->whereIn('detailticketingdelivery.status', $kode)->whereBetween('detailticketingdelivery.DeliveryDate', [$dateS, $dateE])->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/subcontractorhistoryticket',
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
    //HistoryTicket
    public function HistoryticketProc()
    {
        try {
            if ($this->PermissionActionMenu('historyticketsubcont-proc')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY TICKET";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont History Ticket',
                        'description' => 'Display History Ticket',
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
                $kode = ['A', 'D'];
                $kodex = ['S', 'C', 'E', 'Q'];
                $actionmenu =  $this->PermissionActionMenu('historyticketsubcont-proc');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
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
                    ->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('po.CreatedBy', Auth::user()->email)->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                $HistorySubcont          = VwSubcontHistory::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/subcontractorhistoryticket',
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
    //HistoryTicket
    public function HistoryticketVendor()
    {
        try {
            if ($this->PermissionActionMenu('historyticketsubcont-vendor')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY TICKET";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont History Ticket',
                        'description' => 'Display History Ticket',
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

                $kode = ['A', 'D'];
                $kodex = ['S', 'C', 'E', 'Q'];
                $actionmenu =  $this->PermissionActionMenu('historyticketsubcont-vendor');
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
                $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                    ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                            ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                $planDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)
                    ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode', Auth::user()->email)->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/subcontractorhistoryticket',
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
    //HistoryTicket
    public function HistoryticketWhs()
    {
        try {
            if ($this->PermissionActionMenu('historyticketsubcont-whs')->r == 1) {

                $header_title                   = "PO SUBCONTRACTOR - HISTORY TICKET";

                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont History Ticket',
                        'description' => 'Display History Ticket',
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

                $link_newPO                     = 'subcontractornewpo-whs';
                $link_ongoing                   = 'subcontractorongoing-whs';
                $link_planDelivery              = 'subcontractorplandelivery-whs';
                $link_readyToDelivery           = 'subcontractorreadydelivery-whs';
                $link_historyPO                 = 'subcontractorhistory-whs';

                $kodex = ['A', 'D'];
                $actionmenu =  $this->PermissionActionMenu('historyticketsubcont-whs');
                $datauser = Users::where('email', Auth::user()->email)->first();
                $plant = explode(', ', $datauser->assign_plant);
                $kode = ['S', 'C', 'E', 'Q'];
                //30/08/2023
                $dateS = Carbon::now()->subMonth(4);
                $dateE = Carbon::now();
                $NewpoSubcont = VwSubcontNewpo::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->whereIn('Plant', $plant)->get();
                $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
                $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('Plant', $plant)->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();

                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'po.VendorCode', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode', 'uservendors.VendorCode_new')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.Plant', $plant)->whereIn('detailticketingdelivery.status', $kode)->whereBetween('detailticketingdelivery.DeliveryDate', [$dateS, $dateE])->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $HistorySubcont          = VwSubcontHistory::select('Number', 'ItemNumber')->whereIn('Plant', $plant)->groupBy('Number', 'ItemNumber', 'Quantity')->get();

                $countNewpoSubcont       = $NewpoSubcont->count();
                $countOngoingSubcont     = $OngoingSubcont->count();
                $countHistorySubcont     = $HistorySubcont->count();
                $countPlanDeliverySubcont      = $planDelivery->count();
                $countReadyToDeliverySubcont    = $readyToDelivery->count();

                return view(
                    'po-tracking/subcontractor/subcontractorhistoryticket',
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
    public function view_cekticket(Request $request)
    {
        $dataid = VwSubcontOngoing::where('ID', $request->id)->first();
        $dataticket = VwSubcontOngoing::where('Number', $dataid->Number)->groupBy('Number', 'ItemNumber')->get();
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
    //viewticket
    public function cek_ticket(Request $request)
    {
        $data = DetailTicket::where('ID', $request->id)->groupBy('Number', 'ItemNumber', 'ID')->WhereNotIn('status', ['C'])->orderBy('Status', 'asc')->get();

        $data = array(
            'data' => $data
        );
        echo json_encode($data);
    }
    //Acceptticket
    public function Confirmticket(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('subcontractorplandelivery')) {
                $links = "subcontractorplandelivery";
            } else if ($this->PermissionActionMenu('subcontractorplandelivery-vendor')) {
                $links = "subcontractorplandelivery-vendor";
            } else if ($this->PermissionActionMenu('subcontractorplandelivery-whs')) {
                $links = "subcontractorplandelivery-whs";
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            if (!empty($request->ID)) {

                $date = new DateTime();

                $po = DetailTicket::select('detailticketingdelivery.TicketID', 'detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'po.CreatedBy as NRP', 'uservendors.Name')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->whereIn('detailticketingdelivery.ID', $request->ID)
                    ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
                $datavendor = count($po);

                $datetime = $date->format('Y-m-d h:i:s');
                $appsmenu = DetailTicket::whereIn('ID', $request->ID)->first();
                if (!empty($appsmenu) || !empty($request->ID)) {
                    foreach ($po as $q) {
                        $ticketid[] = $q->TicketID;
                        $number[] = $q->Number;
                        $itemnumber[] = $q->ItemNumber;
                        $name[] = $q->Name;
                    }

                    $date   = Carbon::now();
                    if ($request->action == "Confirm Delivery") {
                        for ($i = 0; $i < $datavendor; $i++) {
                            Notification::create([
                                'Number'         => $number[$i],
                                'Subjek'         => 'Approve Ticket Subcont',
                                'user_by' => Auth::user()->name,
                                'user_to' => $name[$i],
                                'is_read' => 1,
                                'menu' => 'subcontractorreadydelivery-vendor',
                                'comment' => $request->remarks,
                                'created_at' => $date

                            ]);
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Subcont Plan Delivery',
                                'description' => 'Confirm Ticket',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $number[$i],
                                'poitem' => $itemnumber[$i],
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Subcont',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                        }

                        Notification::whereIn('Number', $number)->where('Subjek', 'Ticket Subcont')
                            ->update([
                                'is_read' => 3,
                            ]);
                        $update = DetailTicket::whereIn('ID', $request->ID)
                            ->update([
                                'status' => 'A',
                                'AcceptedDate' => $datetime,
                            ]);

                        if ($update) {
                            return redirect($links)->with('suc_message', 'Ticket Accepted By Warehouse !');
                        } else {
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    } elseif ($request->action == "Update Delivery") {
                        // dd($request);
                        $data = count($request->IDS);

                        if ($this->PermissionActionMenu('subcontractorplandelivery-whs')) {

                            Notification::whereIn('Number', $number)->where('Subjek', 'Ticket Subcont')
                                ->update([
                                    'is_read' => 3,
                                ]);

                            for ($i = 0; $i < $datavendor; $i++) {
                                LogHistory::create([
                                    'user'  => Auth::user()->email,
                                    'menu'  => 'PO Subcont Plan Delivery',
                                    'description' => 'Update & Approve Ticket Subcont',
                                    'date'  => $date->toDateString(),
                                    'time'     => $date->toTimeString(),
                                    'ponumber' => $number[$i],
                                    'poitem' => $itemnumber[$i],
                                    'userlogintype' => Auth::user()->title,
                                    'vendortype' => 'Subcont',
                                    'CreatedBy'  => Auth::user()->name,
                                ]);
                                Notification::create([
                                    'Number'         => $number[$i],
                                    'Subjek'         => 'Approve Ticket Subcont',
                                    'user_by' => Auth::user()->name,
                                    'user_to' => $name[$i],
                                    'is_read' => 1,
                                    'menu' => 'subcontractorreadydelivery-vendor',
                                    'comment' => $request->remarks,
                                    'created_at' => $date
                                ]);
                            }

                            for ($i = 0; $i < $data; $i++) {

                                $update = DetailTicket::where('ID', $request->IDS[$i])
                                    ->update([
                                        'DeliveryDate' => Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d') . ' ' . $request->time[$i],
                                        'SPBDate' => Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d'),
                                    ]);
                            }
                            $update = DetailTicket::whereIn('ID', $request->ID)
                                ->update([
                                    'status' => 'A',
                                    'AcceptedDate' => $datetime,
                                ]);
                        } else {
                            for ($i = 0; $i < $data; $i++) {
                                $update = DetailTicket::where('ID', $request->IDS[$i])
                                    ->update([
                                        'DeliveryDate' => Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d') . ' ' . $request->time[$i],
                                        'SPBDate' => Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d'),
                                    ]);
                            }
                            for ($i = 0; $i < $datavendor; $i++) {
                                LogHistory::create([
                                    'user'  => Auth::user()->email,
                                    'menu'  => 'PO Subcont Plan Delivery',
                                    'description' => 'Update Ticket Subcont',
                                    'date'  => $date->toDateString(),
                                    'time'     => $date->toTimeString(),
                                    'ponumber' => $number[$i],
                                    'poitem' => $itemnumber[$i],
                                    'userlogintype' => Auth::user()->title,
                                    'vendortype' => 'Subcont',
                                    'CreatedBy'  => Auth::user()->name,
                                ]);
                            }
                        }

                        if ($update || !$update) {
                            return redirect($links)->with('suc_message', 'Update Delivery Success !!');
                        } else {
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    } else {

                        Notification::whereIn('Number', $number)->where('Subjek', 'Ticket Subcont')
                            ->update([
                                'is_read' => 3,
                            ]);

                        for ($i = 0; $i < $datavendor; $i++) {
                            Notification::create([
                                'Number'         => $number[$i],
                                'Subjek'         => 'Cancel Ticket Subcont',
                                'user_by' => Auth::user()->name,
                                'user_to' => $name[$i],
                                'is_read' => 1,
                                'menu' => 'subcontractorongoing-vendor',
                                'comment' => $request->remarks,
                                'created_at' => $date
                            ]);
                        }
                        foreach ($po as $q) {
                            $ticketids[] = $q->TicketID;
                            $numbers[] = $q->Number;
                            $names[] = $q->NRP;
                        }
                        for ($i = 0; $i < $datavendor; $i++) {
                            Notification::create([
                                'Number'         => $numbers[$i],
                                'Subjek'         => 'Cancel Ticket Subcont',
                                'user_by' => Auth::user()->name,
                                'user_to' => $names[$i],
                                'is_read' => 1,
                                'menu' => 'subcontractorongoing-proc',
                                'comment' => $request->remarks,
                                'created_at' => $date
                            ]);
                        }
                        for ($i = 0; $i < $datavendor; $i++) {
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Subcont Plan Delivery',
                                'description' => 'Cancel Ticket Subcont',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $number[$i],
                                'poitem' => $itemnumber[$i],
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Subcont',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                        }

                        $update = DetailTicket::whereIn('ID', $request->ID)
                            ->update([
                                'remarks' => $request->remarks,
                                'status' => 'C',
                                'ConfirmTicketDate' => Carbon::createFromFormat('d/m/Y', $request->ConfirmTicketDate)->format('Y-m-d'),
                                'AcceptedDate' => $date,
                            ]);
                        if ($update) {
                            return redirect($links)->with('suc_message', 'Ticket Di Cancel !');
                        } else {
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    }
                } else {
                    return redirect()->back()->with('error', 'Please Select Item !!');
                }
            } else {
                return redirect()->back()->with('error', 'Please Select Item !!');
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
            if ($this->PermissionActionMenu('historyticketsubcont')) {
                $link = "historyticketsubcont";
            } else if ($this->PermissionActionMenu('historyticketsubcont-whs')) {
                $link = "historyticketsubcont-whs";
            } else if ($this->PermissionActionMenu('historyticketsubcont-vendor')) {
                $link = "historyticketsubcont-vendor";
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }

            $date   = Carbon::now();
            $dataviewticket = DetailTicket::where('TicketID', $request->ID)->first();

            LogHistory::create([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont Plan Delivery',
                'description' => 'Cancel Ticket',
                'date'  => $date->toDateString(),
                'time'     => $date->toTimeString(),
                'ponumber' => $dataviewticket->Number,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title,
                'vendortype' => 'Subcont',
                'CreatedBy'  => Auth::user()->name,
            ]);
            if ($request->status == "Delete") {
                $update = DetailTicket::where('TicketID', $request->ID)->whereIn('status', ['S'])
                    ->update([
                        'remarks' => $request->remarks,
                        'status' => 'C',
                        'LastModifiedBy' => Auth::user()->name,
                    ]);
                $info = 'Cancel Ticket Success !';
            } else {
                $update = DetailTicket::where('TicketID', $request->ID)->whereIn('status', ['E'])
                    ->update([
                        'SecurityDate' => Carbon::createFromFormat('d/m/Y', $request->securitydate)->format('Y-m-d'),
                        'remarks' => $request->remarks,
                        'status' => 'S',
                        'LastModifiedBy' => Auth::user()->name,
                    ]);
                $info = 'Update Ticket Success !';
            }


            if ($update) {
                return redirect($link)->with('suc_message', $info);
            } else {
                return redirect()->back()->with('err_message', 'Data gagal di Proses!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
