<?php

namespace App\Http\Controllers\Cms\PoTracking\POLocal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\Ticket;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\Table\PoTracking\Users;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use App\Models\View\CompletenessComponent\VwTotalStock;
use DateTime;
use Illuminate\Support\Carbon;

class PlanDeliveryController extends Controller
{

    // polocal

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('polocalplandelivery') == 0 && $this->PermissionMenu('polocalplandelivery-management') == 0 && $this->PermissionMenu('polocalplandelivery-nonmanagement') == 0 && $this->PermissionMenu('polocalplandelivery-proc') == 0 && $this->PermissionMenu('polocalplandelivery-vendor') == 0  && $this->PermissionMenu('polocalplandelivery-whs') == 0 && $this->PermissionMenu('historyticket') == 0 && $this->PermissionMenu('historyticket-nonmanagement') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //PlanDelivery management
    public function polocalPlandelivery()
    {
        if ($this->PermissionActionMenu('polocalplandelivery')->r == 1) {

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display Plan Delivery',
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
            $actionmenu =  $this->PermissionActionMenu('polocalplandelivery');
            $menu_ticket = 'historyticketlocal';
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber')->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
            $HistoryPolocal = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

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
            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();
            return view(
                'po-tracking/polocal/plandelivery',
                compact(
                    'data',
                    'header_title',
                    'link_newPO',
                    'link_ongoing',
                    'link_planDelivery',
                    'link_readyToDelivery',
                    'link_historyPO',
                    'menu_ticket',
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
    }
    //PlanDelivery management
    public function polocalPlandeliveryManagement()
    {
        if ($this->PermissionActionMenu('polocalplandelivery-management')->r == 1) {

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display Plan Delivery',
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
            // $datauser = Users::where('email', Auth::user()->email)->first();
            // $plant = explode(', ', $datauser->assign_plant);
            $kodex = ['A', 'D'];
            $actionmenu =  $this->PermissionActionMenu('polocalplandelivery-management');
            $menu_ticket = 'historyticketlocal-management';
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber')->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
            $HistoryPolocal = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
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
            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/plandelivery',
                compact(
                    'data',
                    'header_title',
                    'link_newPO',
                    'link_ongoing',
                    'link_planDelivery',
                    'link_readyToDelivery',
                    'link_historyPO',
                    'menu_ticket',
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
    }
    //PlanDelivery management
    public function polocalPlandeliveryNonManagement()
    {
        if ($this->PermissionActionMenu('polocalplandelivery-nonmanagement')->r == 1) {

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display Plan Delivery',
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
            // $datauser = Users::where('email', Auth::user()->email)->first();
            // $plant = explode(', ', $datauser->assign_plant);
            $kodex = ['A', 'D'];
            $actionmenu =  $this->PermissionActionMenu('polocalplandelivery-nonmanagement');
            $menu_ticket = 'historyticketlocal-nonmanagement';
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber')->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
            $HistoryPolocal = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

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

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/plandelivery',
                compact(
                    'data',
                    'header_title',
                    'link_newPO',
                    'link_ongoing',
                    'link_planDelivery',
                    'link_readyToDelivery',
                    'link_historyPO',
                    'menu_ticket',
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
    }
    //PlanDelivery proc
    public function polocalPlandeliveryProc()
    {

        if ($this->PermissionActionMenu('polocalplandelivery-proc')->r == 1) {

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $link_search                    = "caripolocalplandelivery";
            $link_reset                     = "plandelivery";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display Plan Delivery',
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
            $menu_ticket = 'historyticketlocal-proc';
            $kodex = ['A', 'D'];
            $actionmenu =  $this->PermissionActionMenu('polocalplandelivery-proc');
            $NewpoPolocal = VwLocalnewpo::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal = VwOngoinglocal::select('POID', 'ItemNumber')->where('NRP', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
            $HistoryPolocal = VwHistoryLocal::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
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

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();


            return view(
                'po-tracking/polocal/plandelivery',
                compact(
                    'data',
                    'header_title',
                    'link_search',
                    'link_reset',
                    'link_newPO',
                    'link_ongoing',
                    'link_planDelivery',
                    'link_readyToDelivery',
                    'link_historyPO',
                    'menu_ticket',
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
    }
    //PlanDelivery management
    public function polocalPlandeliveryVendor()
    {
        if ($this->PermissionActionMenu('polocalplandelivery-vendor')->r == 1) {

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display Plan Delivery',
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
            $actionmenu =  $this->PermissionActionMenu('polocalplandelivery-vendor');
            $menu_ticket = 'historyticketlocal-vendor';
            $NewpoPolocal = VwLocalnewpo::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('*')->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $HistoryPolocal = VwHistoryLocal::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
            $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                ->where('uservendors.VendorCode', Auth::user()->email)
                ->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
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

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/plandeliveryVendor',
                compact(
                    'data',
                    'header_title',
                    'link_newPO',
                    'link_ongoing',
                    'link_planDelivery',
                    'link_readyToDelivery',
                    'link_historyPO',
                    'menu_ticket',
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
    }
    //PlanDelivery management
    public function polocalPlandeliveryWhs(Request $request)
    {
        if ($this->PermissionActionMenu('polocalplandelivery-whs')->r == 1) {

            $header_title                   = "PO LOCAL - PLAN DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display Plan Delivery',
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
            // $datauser = Users::where('email', Auth::user()->email)->first();
            // $plant = explode(', ', $datauser->assign_plant);
            $kodex = ['A', 'D'];
            $actionmenu =  $this->PermissionActionMenu('polocalplandelivery-whs');
            $menu_ticket = 'historyticketlocal-whs';
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            if ($request->Number) {
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('detailticketingdelivery.Number', $request->Number)
                    ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            } else {
                $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('detailticketingdelivery.status', 'P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            }

            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber')->whereIn('status', $kodex)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ID', 'asc')->get();
            $HistoryPolocal = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

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

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $data->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/plandelivery',
                compact(
                    'data',
                    'header_title',
                    'link_newPO',
                    'link_ongoing',
                    'link_planDelivery',
                    'link_readyToDelivery',
                    'link_historyPO',
                    'menu_ticket',
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
    }
    //HistoryTicket
    public function Historyticket()
    {
        if ($this->PermissionActionMenu('historyticketlocal')->r == 1) {

            $header_title                   = "PO LOCAL - HISTORY TICKET";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display History Ticket',
                    'date'  => $date->toDateString(),
                    'time'     => $date->toTimeString(),
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
            $actionmenu =  $this->PermissionActionMenu('historyticketlocal');

            $kode = ['S', 'C', 'E'];
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/historyticket',
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
    }
    //HistoryTicketmanagement
    public function HistoryticketManagement()
    {
        if ($this->PermissionActionMenu('historyticketlocal-management')->r == 1) {

            $header_title                   = "PO LOCAL - HISTORY TICKET";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display History Ticket',
                    'date'  => $date->toDateString(),
                    'time'     => $date->toTimeString(),
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
            $actionmenu =  $this->PermissionActionMenu('historyticketlocal-management');
            // $datauser = Users::where('email', Auth::user()->email)->first();
            // $plant = explode(', ', $datauser->assign_plant);
            $kode = ['S', 'C', 'E'];
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/historyticket',
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
    }
    //HistoryTicketNonManagement

    public function HistoryticketNonManagement()
    {
        if ($this->PermissionActionMenu('historyticketlocal-nonmanagement')->r == 1) {

            $header_title                   = "PO LOCAL - HISTORY TICKET";

            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display History Ticket',
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
            $actionmenu =  $this->PermissionActionMenu('historyticketlocal-nonmanagement');
            // $datauser = Users::where('email', Auth::user()->email)->first();
            // $plant = explode(', ', $datauser->assign_plant);
            $kode = ['S', 'C', 'E'];
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/historyticket',
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
    } //HistoryTicket
    public function HistoryticketProc()
    {
        if ($this->PermissionActionMenu('historyticketlocal-proc')->r == 1) {

            $header_title                   = "PO LOCAL - HISTORY TICKET";

            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display History Ticket',
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
            $actionmenu =  $this->PermissionActionMenu('historyticketlocal-proc');
            $kode = ['S', 'C', 'E'];
            $NewpoPolocal = VwLocalnewpo::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
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
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->where('po.CreatedBy', Auth::user()->email)->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::where('NRP', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/historyticket',
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
    } //HistoryTicket
    public function HistoryticketVendor()
    {
        if ($this->PermissionActionMenu('historyticketlocal-vendor')->r == 1) {

            $header_title                   = "PO LOCAL - HISTORY TICKET";

            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display History Ticket',
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
            $actionmenu =  $this->PermissionActionMenu('historyticketlocal-vendor');
            $kode = ['S', 'C', 'E'];
            $NewpoPolocal = VwLocalnewpo::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->where('VendorCode', Auth::user()->email)->distinct('POID', 'ItemNumber')->get();
            $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                ->where('uservendors.VendorCode', Auth::user()->email)
                ->whereIn('detailticketingdelivery.status', $kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

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
                ->where('uservendors.VendorCode', Auth::user()->email)->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/historyticket',
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
    } //HistoryTicket
    public function HistoryticketWhs()
    {
        if ($this->PermissionActionMenu('historyticketlocal-whs')->r == 1) {

            $header_title                   = "PO LOCAL - HISTORY TICKET";
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Plan Delivery',
                    'description' => 'Display History Ticket',
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
            // $datauser = Users::where('email', Auth::user()->email)->first();
            // $plant = explode(', ', $datauser->assign_plant);
            $kodex = ['A', 'D'];
            $actionmenu =  $this->PermissionActionMenu('historyticketlocal-whs');
            $kode = ['S', 'C', 'E'];
            $NewpoPolocal = VwLocalnewpo::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID', 'ItemNumber')->distinct('POID', 'ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'P')->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->distinct('Number', 'ItemNumber', 'TicketID')->whereIn('status', $kodex)->get();
            $data = DetailTicket::select('detailticketingdelivery.*', 'po.CreatedBy as NRP', 'po.PurchaseOrderCreator', 'po.ReleaseDate', 'uservendors.VendorCode', 'uservendors.VendorCode_new', 'po.Date', 'purchasingdocumentitem.POID', 'purchasingdocumentitem.DeliveryDate as DeliveryDateS', 'purchasingdocumentitem.POID', 'uservendors.Name as Vendor', 'uservendors.VendorType', 'uservendors.VendorCode')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->whereNotIn('detailticketingdelivery.Number', ['', 'NULL'])->whereIn('detailticketingdelivery.status', $kode)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::select('Number', 'ItemNumber')->groupBy('Number', 'ItemNumber', 'Quantity')->get();
            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $readyToDelivery->count();

            return view(
                'po-tracking/polocal/historyticket',
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
    }

    public function view_cekticket(Request $request)
    {
        $dataid = VwOngoinglocal::where('ID', $request->id)->first();
        $dataticket = VwOngoinglocal::where('Number', $dataid->Number)->groupBy('Number', 'ItemNumber')->get();
        if ($dataid->ConfirmedItem == 1) {
            $dataall = VwOngoinglocal::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->get();
        } else {
            $dataall = VwOngoinglocal::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->groupBy('Number', 'ItemNumber')->get();
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
        if ($this->PermissionActionMenu('polocalplandelivery')) {
            $links = "polocalplandelivery";
        } else if ($this->PermissionActionMenu('polocalplandelivery-vendor')) {
            $links = "polocalplandelivery-vendor";
        } else if ($this->PermissionActionMenu('polocalplandelivery-whs')) {
            $links = "polocalplandelivery-whs";
        } else {
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }
        if (!empty($request->ID)) {

            $dates = new DateTime();

            $po = DetailTicket::select('detailticketingdelivery.TicketID', 'detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'po.CreatedBy as NRP', 'uservendors.Name')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function ($join) {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
                ->whereIn('detailticketingdelivery.ID', $request->ID)
                ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber', 'detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

            $datavendor = count($po);
            $datetime = $dates->format('Y-m-d h:i:s');
            $date   = Carbon::now();

            if (!empty($po) || !empty($request->ID)) {
                foreach ($po as $q) {
                    $ticketid[] = $q->TicketID;
                    $number[] = $q->Number;
                    $itemnumber[] = $q->ItemNumber;
                    $name[] = $q->Name;
                }

                if ($request->action == "Confirm Delivery") {

                    for ($i = 0; $i < $datavendor; $i++) {
                        Notification::create([
                            'Number'         => $number[$i],
                            'Subjek'         => "Approve Ticket Local",
                            'user_by' => Auth::user()->name,
                            'user_to' => $name[$i],
                            'is_read' => 1,
                            'menu' => 'polocalreadydelivery-vendor',
                            'comment' => $request->remarks,
                            'created_at' => $date
                        ]);
                        LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Local Plan Delivery',
                            'description' => 'Approve Ticket Local',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $number[$i],
                            'poitem' => $itemnumber[$i],
                            'userlogintype' => Auth::user()->title,
                            'vendortype' => 'Local',
                            'CreatedBy'  => Auth::user()->name,
                        ]);
                    }
                    Notification::whereIn('Number', $number)->where('Subjek', 'Ticket Local')
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
                    if ($this->PermissionActionMenu('polocalplandelivery-whs')) {

                        Notification::whereIn('Number', $number)->where('Subjek', 'Ticket Local')
                            ->update([
                                'is_read' => 3,
                            ]);

                        for ($i = 0; $i < $datavendor; $i++) {
                            Notification::updateOrCreate([
                                'Number'         => $number[$i],
                                'Subjek'         => "Approve Ticket Local",
                                'user_by' => Auth::user()->name,
                                'user_to' => $name[$i],
                                'is_read' => 1,
                                'menu' => 'polocalreadydelivery-vendor',
                                'comment' => $request->remarks,
                                'created_at' => $date
                            ]);

                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Local Plan Delivery',
                                'description' => 'Update & Approve Ticket Local',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $number[$i],
                                'poitem' => $itemnumber[$i],
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Local',
                                'CreatedBy'  => Auth::user()->name,
                            ]);
                        }
                        for ($i = 0; $i < $data; $i++) {

                            $update = DetailTicket::where('ID', $request->IDS[$i])
                                ->update([
                                    'DeliveryDate' => Carbon::createFromFormat('d/m/Y', $request->deliverydate[$i])->format('Y-m-d') . ' ' . $request->time[$i],
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
                                ]);
                        }
                        for ($i = 0; $i < $datavendor; $i++) {
                            LogHistory::create([
                                'user'  => Auth::user()->email,
                                'menu'  => 'PO Local Plan Delivery',
                                'description' => 'Update Ticket Local',
                                'date'  => $date->toDateString(),
                                'time'     => $date->toTimeString(),
                                'ponumber' => $number[$i],
                                'poitem' => $itemnumber[$i],
                                'userlogintype' => Auth::user()->title,
                                'vendortype' => 'Local',
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


                    Notification::whereIn('Number', $number)->where('Subjek', 'Ticket Local')
                        ->update([
                            'is_read' => 3,
                        ]);
                    for ($i = 0; $i < $datavendor; $i++) {
                        Notification::create([
                            'Number'         => $number[$i],
                            'Subjek'         => "Cancel Ticket Local",
                            'user_by' => Auth::user()->name,
                            'user_to' => $name[$i],
                            'is_read' => 1,
                            'menu' => 'polocalongoing-vendor',
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
                            'Subjek'         =>  "Cancel Ticket Local",
                            'user_by' => Auth::user()->name,
                            'user_to' => $names[$i],
                            'is_read' => 1,
                            'menu' => 'polocalongoing-proc',
                            'comment' => $request->remarks,
                            'created_at' => $date
                        ]);
                    }
                    for ($i = 0; $i < $datavendor; $i++) {
                        LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Local Plan Delivery',
                            'description' => 'Cancel Ticket Local',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $number[$i],
                            'poitem' => $itemnumber[$i],
                            'userlogintype' => Auth::user()->title,
                            'vendortype' => 'Local',
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
    }

    //ProsesUpdate
    public function ProsesUpdate(Request $request)
    {
        if ($this->PermissionActionMenu('historyticketlocal')) {
            $link = "historyticketlocal";
        } else if ($this->PermissionActionMenu('historyticketlocal-whs')) {
            $link = "historyticketlocal-whs";
        } else if ($this->PermissionActionMenu('historyticketlocal-vendor')) {
            $link = "historyticketlocal-vendor";
        } else {
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }

        $date   = Carbon::now();
        $dataviewticket = DetailTicket::where('TicketID', $request->ID)->first();

        LogHistory::create([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Plan Delivery',
            'description' => 'Cancel Ticket',
            'date'  => $date->toDateString(),
            'time'     => $date->toTimeString(),
            'ponumber' => $dataviewticket->Number,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title,
            'vendortype' => 'Local',
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
    }
}
