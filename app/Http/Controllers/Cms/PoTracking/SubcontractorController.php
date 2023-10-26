<?php

namespace App\Http\Controllers\Cms\PoTracking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PP;
use App\Models\Table\PoTracking\ReasonSubCont;
use App\Models\Table\PoTracking\SubcontLeadtimeMaster;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\Ticket;
use App\Models\Table\PoTracking\Vendors;
use App\Models\Table\PoTracking\Notification;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\VwUserRoleGroup;

use Response;
use DateTime;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use App\Models\View\CompletenessComponent\VwTotalStock;

use Carbon\Carbon;

class SubcontractorController extends Controller
{
    public $newpo           = 'subcontractornewpo';
    public $ongoing         = 'subcontractorongoing';
    public $planDelivery    = 'subcontractorplandelivery';
    public $readyToDelivery = 'subcontractorreadytodelivery';
    public $reportPO        = '#';
    public $historyparking  = '#';
    public $historyPO       = 'subcontractorhistory';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('subcontractornewpo') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function subcontractor()
    {
        $data =VwPo::Join('vw_posubcont','vw_po.id','=','vw_posubcont.id')->whereIn('Type', ['ZO05','ZO09','ZO10'])->distinct()->get();
        // $data =VwPo::Join('vw_posubcont','vw_po.id','=','vw_subcont.id')->whereIn('Type', ['ZO05','ZO09','ZO10'])->distinct()->get();
        return view('po-tracking/subcontraktor/index', compact('data'));
    }

    //PO SubContractor - NewPO
    public function subcontractornewpo()
    {
        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date           = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont New Po',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title           = "Sub Contractor - New PO";
            $link_search            = "carisubcontractornewpo";
            $link_reset             = "subcontractornewpo";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyParking    = $this->historyparking;
            $link_historyPO         = $this->historyPO;
            $kodex                  = ['A','D','S','W','Q','R','X'] ;
            $actionmenu             =  $this->PermissionActionMenu('subcontractornewpo');

            if($actionmenu->c==1){
                $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }
            else if($actionmenu->u==1){
                $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontNewpo::where('NRP',Auth::user()->email)
                    ->leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_newposubcont.Material')
                    ->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }
            else{
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontNewpo::
                    leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_newposubcont.Material')
                    ->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('status','P')->whereIn('POID',$ongoingPOID)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('status',$kodex)->whereIn('POID',$ongoingPOID)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }

            $material_potracking = VwSubcontNewpo::select('Material')->distinct()->get()->toArray();
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
            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractornewpo',
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
                'link_historyParking',
                'link_historyPO',
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
	  //ConfirmedPOSubcont - NewPO
	public function ConfirmedPOSubcont(Request $request)
  	  {
       	  $appsmenu = VwSubcontNewpo::where('POID', $request->POID)->get();
        foreach($appsmenu as $p){
             $update = Pdi::where('ID', $p->ID)
            ->update([
                'ConfirmedDate'     => $p->DeliveryDate,
                'ConfirmedQuantity' => $p->Quantity,
                'ConfirmedItem'     => 1,
                'ParentID'          => $p->ID,
                'ActiveStage'       => 2,
                'LeadTimeItem'      => NULL,
                'PB'                => NULL,
                'Setting'           => NULL,
                'Fullweld'          => NULL,
                'Primer'            => NULL
            ]);
        }        
	if($update){
            return redirect('subcontractornewpo')->with('suc_message', 'Confirm PO Success!');
        }else{
            return redirect()->back()->with('err_message', 'Data gagal disimpan!');
        }
	}
      //Cari PO SubContractor - NewPO
      public function carisubcontractornewpo($number)
      {
          if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
              $date           = Carbon::now();
              LogHistory::updateOrCreate([
                  'user'      => Auth::user()->id,
                  'menu'      => 'PO Subcont New Po',
                  'date'      => $date->toDateString(),
                  'CreatedBy' => Auth::user()->name
              ],
                  ['time'     => $date->toTimeString()
              ]);

              $header_title           = "Sub Contractor - New PO";
              $link_search            = "carisubcontractornewpo";
              $link_reset             = "subcontractornewpo";

              $link_newPO             = $this->newpo;
              $link_ongoing           = $this->ongoing;
              $link_planDelivery      = $this->planDelivery;
              $link_readyToDelivery   = $this->readyToDelivery;
              $link_reportPO          = $this->reportPO;
              $link_historyParking    = $this->historyparking;
              $link_historyPO         = $this->historyPO;
              $kodex                  = ['A','D','S','W','Q','R','X'] ;
              $actionmenu             =  $this->PermissionActionMenu('subcontractornewpo');

              if($actionmenu->c==1){
                  $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                  $data                       = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->where('Number',$number)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                  $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                  $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                  $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                  $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                  $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
              }
              else if($actionmenu->u==1){
                  $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                  $data                       = VwSubcontNewpo::where('NRP',Auth::user()->email)->where('Number',$number)
                      ->leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_newposubcont.Material')
                      ->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                  $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                  $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                  $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                  $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                  $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
              }
              else{
                  $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                  $data                       = VwSubcontNewpo::where('Number',$number)->
                      leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_newposubcont.Material')
                      ->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                  $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                  $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                  $PlanDeliveryPoSubcont      = VwViewTicket::where('status','P')->whereIn('POID',$ongoingPOID)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                  $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('status',$kodex)->whereIn('POID',$ongoingPOID)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                  $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
              }

              $material_potracking = VwSubcontNewpo::select('Material')->distinct()->get()->toArray();
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
              $countNewpoSubcontractor            = $NewPoSubcont;
              $countOngoingSubcontractor          = $OngoingPoSubcont;
              $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
              $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
              $countHistorySubcontractor          = $HistoryPoSubcont;

              return view('po-tracking/subcontraktor/subcontractornewpo',
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
                  'link_historyParking',
                  'link_historyPO',
                  'countNewpoSubcontractor',
                  'countOngoingSubcontractor',
                  'countPlanDeliverySubcontractor',
                  'countReadyToDeliverySubcontractor',
                  'countHistorySubcontractor',
                  'actionmenu'
              ));
          }else{
              return redirect('/')->with('err_message', 'Akses Ditolak!');
          }
      }

    //PO SubContractor - NewPO (View Subcont ConfirmPO)
    public function viewsubcontractor(Request $request)
    {
        $Subcont    = VwPo::where('ID', $request->id)->first();
        $viewtable  = VwSubcontNewpo::where('ID', $request->id)->orWhere('ParentID', $request->id)->get();
        $Vendors    = UserVendor::where('VendorCode', $Subcont->VendorCode)->first();
        $actionmenu = $this->PermissionActionMenu('subcontractornewpo');
        $data       = array(
                    'subcont' =>$Subcont,
                    'viewtable' =>$viewtable,
                    'vendors' => $Vendors,
                    'actionmenu' => $actionmenu,
                    );

        echo json_encode($data);
    }

    //PO SubContractor - OngoingPO
    public function subcontractorongoing()
    {
        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont Ongoing Po',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title   = "Sub Contractor - On Going";
            $link_search    = "carisubcontractorongoing";
            $link_reset     = "subcontractorngoing";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'] ;
            $actionmenu             = $this->PermissionActionMenu('subcontractornewpo');

            if($actionmenu->c==1){
                $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }else if($actionmenu->u==1){
                $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontOngoing::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();

            }else{
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontOngoing::groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorongoing',
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
                'link_historyParking',
                'link_historyPO',
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }
        else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    //PO SubContractor - OngoingPO (by Date)
    public function carisubcontractorongoing($number)
    {

        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont Ongoing Po',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title   = "Sub Contractor - On Going";
            $link_search    = "carisubcontractorongoing";
            $link_reset     = "subcontractorngoing";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'];
            $actionmenu             =  $this->PermissionActionMenu('subcontractornewpo');


            if($actionmenu->c==1){
                $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontOngoing::where('Number',$number)->where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }
            else if($actionmenu->u==1){
                $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontOngoing::where('Number',$number)->where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }
            else{
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontOngoing::where('Number',$number)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();

            }

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorongoing',
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
                'link_historyParking',
                'link_historyPO',
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    //Downloadfileproforma
    public function subcontractordownloadproforma($id)
    {
        $file= "public/potracking/uploudproforma/$id";
        $headers = array('Content-Type: application/pdf',);

        return Response::download($file, $id, $headers);
    }

    //PO SubContractor - OnGoingPO (View Subcont Proforma Invoice)
    public function view_proformasubcontractor(Request $request)
    {
        $actionmenu = $this->PermissionActionMenu('subcontractornewpo');
        $dataid     = VwSubcontOngoing::where('ID', $request->id)->first();
        $data       = VwSubcontOngoing::where('Number', $dataid->Number)->where('ParentID','!=','null')->groupBy('Number','ItemNumber','Quantity')->get();

        $data = array(
            'data'          => $data,
            'dataid'        => $dataid,
            'actionmenu'    => $actionmenu,
        );

        echo json_encode($data);
    }

    //PO SubContractor - OnGoingPO (Insert Subcont Proforma Invoice)
    public function proformasubcontractor(Request $request)
    {
         // Need or Skip Proforma
        if(!empty($request->ID) || !empty($request->Number)){
            $datapo = VwSubcontOngoing::where('Number', $request->Number)->first();
            $date   = Carbon::now();
            if($request->ActiveStage== '2'){
                $appsmenu = VwSubcontOngoing::where('Number', $request->Number)->get();
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
                            'menu'=>"subcontractorongoing",
                            'comment'=>"Request And Uploud Proforma PO $datapo->Number",
                            'created_at'=>$date
                        ]);
                    }
                    else{
                        $create = Pdi::whereIn('ID',$id)
                        ->update([
                            'ActiveStage'=>3,
                        ]);

                    }
                    if($create){
                        return redirect('subcontractorongoing')->with('suc_message', 'PO Proforma Approve!');
                    }
                    else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }
            // Upload Document Proforma
            elseif($request->ActiveStage == '2a'){
                $this->validate($request,[
                    'filename' => 'required|mimes:PDF,pdf|max:5120'
                ]);
                $appsmenu = VwSubcontOngoing::where('Number', $request->Number)->get();
                if(!empty($appsmenu)){
                    $file           =  $request->file('filename');
                    $nama_file      = time()."_".$file->getClientOriginalName();
                    // isi dengan nama folder tempat kemana file diupload
                    $tujuan_upload  = 'public/potracking/uploudproforma';
                    $file->move($tujuan_upload,$nama_file);

                    foreach ($appsmenu as $p) {
                        $id[] = $p->ID;
                    }

                    $create = Pdi::whereIn('ID',$id)
                            ->update([
                                'ActiveStage'                   => '2b',
                                'InvoiceMethod'                 => $request->invoice_no,
                                'ConfirmReceivedPaymentDate'    => $request->invoice_date,
                                'InvoiceMethod'                 => $request->invoice_no,
                                'TaxInvoice'                    => $request->tax_invoice_no,
                                'ProformaInvoiceDocument'       => $nama_file,
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
                            'menu'=>"subcontractorongoing",
                            'comment'=>"Proforma PO NO.$datapo->Number",
                            'created_at'=>$date
                        ]);
                    if($create){
                        return redirect('subcontractorongoing')->with('suc_message', 'Proforma Berhasi Disimpan!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }
            // Accept/Revision Document Proforma
            else if($request->ActiveStage == '2b'){
                $appsmenu = VwSubcontOngoing::where('Number', $request->Number)->get();
                $getpdf = VwSubcontOngoing::where('Number', $request->Number)->first();

                if(!empty($appsmenu)){
                    if($request->action == "Save"){
                        $file =  $request->file('filename');
                        $nama_file = time()."_".$file->getClientOriginalName();

                                // isi dengan nama folder tempat kemana file diupload
                        $tujuan_upload = 'public/potracking/uploudproforma';
                        $file->move($tujuan_upload,$nama_file);
                        $this->validate($request,[
                            'confirm_date' => 'required'
                        ]);
                        foreach ($appsmenu as $p) {
                            $id[] = $p->ID;
                        }
                        $create = Pdi::whereIn('ID',$id)->update(['ActiveStage' => 3,'ApproveProformaInvoiceDocument' => $nama_file,]);
                        Notification::where('Number',$datapo->Number)->where('Subjek','Request Proforma')
                        ->update([
                            'is_read'=>3,
                         ]);

                        if($create){
                            return redirect('subcontractorongoing')->with('suc_message', 'Proforma disetujui!');
                        }else{
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    }
                    elseif($request->action== "Download"){
                        $file= "public/potracking/uploudproforma/$getpdf->ProformaInvoiceDocument";
                        $headers = array(
                            'Content-Type: application/pdf',
                        );
                        return Response::download($file, $getpdf->ProformaInvoiceDocument, $headers);
                    }
                    elseif($request->action== "Revisi"){
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
                                'menu'=>"subcontractorongoing",
                                'comment'=>"Revisi Proforma PO NO.$datapo->Number",
                                'created_at'=>$date
                        ]);
                        if($create){
                            return redirect('subcontractorongoing')->with('suc_message', 'Proforma harus direvisi!');
                        }else{
                            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                        }
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }
            else{
                return redirect()->back()->with('err_message', 'Terjadi Kesalahan!');
            }
        }
    }

    //PO SubContractor - OngoingPO (View Subcont Progress Sequence)
    public function viewsubcontractorsequence(Request $request)
    {
        $dataid         = VwSubcontOngoing::where('ID', $request->id)->orWhere('ParentID', $request->id)->groupBy('Number','ItemNumber','Quantity')->get();
        $dataall        = VwSubcontOngoing::where('ID', $request->id)->orWhere('ParentID', $request->id)->get();
        $reasonid       = ReasonSubCont::all();
        $dataleadtime   = SubcontLeadtimeMaster::all();
        $actionmenu     = $this->PermissionActionMenu('subcontractornewpo');

        $a = array();
        for($i = 0; $i < count($dataall) ; $i++){
            $photoprogress  = PP::where('PurchasingDocumentItemID', $dataall[$i]->ID)->get();
            if( !empty($photoprogress)){
                array_push($a , $photoprogress);
            }
        }
        $data = array(
            'dataid'        =>$dataid,
            'dataall'       =>$dataall,
            'reasonsubcont' =>$reasonid,
            'dataleadtime'  =>$dataleadtime,
            'photoprogress' =>$a,
            'actionmenu'    =>$actionmenu
            );

        echo json_encode($data);
    }

    //PO SubContractor - OngoingPO (Insert Subcont Progress Sequence)
    public function subcontractorleadtime(Request $request)
    {
        $appsmenu = Pdi::where('ID', $request->ID)->first();

        if(!empty($appsmenu)){
                if($request->action == "PB"){ //Untuk submit PB Progress
                    $request->validate([
                        'PBfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach($request->PBfoto as $pbfoto){
                        $imageName = uniqid().'_'.time().'_PB_'.$pbfoto->getClientOriginalName();
                        $pbfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
                            'FileName'                  => trim($imageName),
                            'CreatedBy'                 => "System",
                            'ProcessName'               => "PB",
                        ];
                        $update = PP::insert($newinsert);
                    }
                    if($appsmenu->ActiveStage == null){ //JIKA PROSES DARI SAP
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
                    }
                    else{
                        $update =  Pdi::where('ID', $request->ID)
                        ->update([
                            'ActiveStage'       => "3a",
                            'PBActualDate'      => Carbon::createFromFormat('d/m/Y', trim($request->PBActualDate))->format('Y-m-d'),
                            'PBLateReasonID'    => trim($request->PBreasonID),
                        ]);
                    }


                    if($update){
                        return redirect('subcontractorongoing')->with('suc_message', 'PB Progress saved successfully!');
                    }else{
                        return redirect()->back()->with('err_message', 'PB Progress failed to save!');
                    }
                }

                if($request->action == "Setting"){ //Untuk submit Setting Progress
                    $request->validate([
                        'Settingfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach($request->Settingfoto as $settingfoto){
                        $imageName = uniqid().'_'.time().'_Setting_'.$settingfoto->getClientOriginalName();
                        $settingfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
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
                    if($update){
                        return redirect('subcontractorongoing')->with('suc_message', 'Setting Progress saved successfully!');
                    }else{
                        return redirect()->back()->with('err_message', 'Setting Progress failed to save!');
                    }
                }

                if($request->action == "Fullweld"){ //Untuk submit Fullweld Progress
                    $request->validate([
                        'Fullweldfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach($request->Fullweldfoto as $fullweldfoto){
                        $imageName = uniqid().'_'.time().'_Fullweld_'.$fullweldfoto->getClientOriginalName();
                        $fullweldfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
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
                    if($update){
                        return redirect('subcontractorongoing')->with('suc_message', 'Fullweld Progress saved successfully!');
                    }else{
                        return redirect()->back()->with('err_message', 'Fullweld Progress failed to save!');
                    }
                }

                if($request->action == "Primer"){ //Untuk submit Primer Progress
                    $request->validate([
                        'Primerfoto.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
                    ]);
                    foreach($request->Primerfoto as $primerfoto){
                        $imageName = uniqid().'_'.time().'_Primer_'.$primerfoto->getClientOriginalName();
                        $primerfoto->move(public_path('potracking/progressphoto'), $imageName);

                        $newinsert = [
                            'PurchasingDocumentItemID'  => trim($request->ID),
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
                    if($update){
                        return redirect('subcontractorongoing')->with('suc_message', 'Primer Progress saved successfully!');
                    }else{
                        return redirect()->back()->with('err_message', 'Primer Progress failed to save!');
                    }
                }
        }else{
            return redirect()->back()->with('err_message', 'Data not found!');
        }
    }

    //PO SubContractor - OngoingPO (View Create Ticket Vendor)
    public function view_subcontcreateticket(Request $request)
    {
        $dataid = VwSubcontOngoing::where('ID', $request->id)->first();
        $dataticket = VwSubcontOngoing::where('Number', $dataid->Number)->where('ActiveStage', 4)->groupBy('Number','ItemNumber')->get();
        if($dataid->ConfirmedItem == 1){
            $dataall = VwSubcontOngoing::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->get();
        }else{
            $dataall = VwSubcontOngoing::where('Number', $dataid->Number)->where('ItemNumber', $dataid->ItemNumber)->groupBy('Number','ItemNumber')->get();
        }

        $data = array(
            'dataid' =>$dataid,
            'dataticket' =>$dataticket,
            'dataall' =>$dataall,
            );
        echo json_encode($data);
    }

    //PO SubContractor - OngoingPO (Insert Create Ticket Vendor)
    public function subcontractorcreateticket(Request $request)
    {
         //ProsesConfirmPo
        if(!empty($request->ID)||!empty($request->Number)){
            if($request->ActiveStage == '4' ){
                $appsmenu       = VwSubcontOngoing::whereIn('ParentID', $request->ID)->orWhereIn('ID', $request->ID)->first();
                $ticket         = DetailTicket::all();
                $jumlahticket   = count($ticket);

                if($jumlahticket == 0){
                    $c = 1;
                }else{
                    $c = $jumlahticket;
                }
                if(!empty($appsmenu)){
                    $data       = count($request->ID) ;
                    $idticket   = 'PTR/PR/'.date('Ymd').'/'.date('His').'/'.$appsmenu->Number.'/'.$c.'/'.$data;
                    $spbdate    = Carbon::createFromFormat('d/m/Y', $request->SPBdate)->format('Y-m-d');

                    $create1    = Ticket::create([
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
                    Notification::where('Number',$request->numbers)->where('menu','Cancel Ticket Subcont')
                    ->update([
                        'is_read'=>3,
                     ]);

                    $dataS    = VwUserRoleGroup::select('username')->where('username','!=',$request->Name)->where('group', 30)->get();
                    foreach ($dataS as $q) {
                        $datarole[] = $q->username;
                    }

                    $datawarehouse = count($datarole);

                            $date   = Carbon::now();
                            for ($i = 0; $i < $datawarehouse; $i++) {
                            Notification::create([
                                'Number'         => $request->numbers,
                                'Subjek'         => $idticket,
                                'user_by'=>$appsmenu->Vendor,
                                'user_to'=>$datarole[$i],
                                'menu'=>'Ticket Subcont',
                                'is_read'=>1,
                                'comment'=>"$idticket",
                                'created_at'=>$date
                            ]);

                            }

                    if($create1 && $create2){
                        return redirect('subcontractorongoing')->with('suc_message', 'Ticket Berhasil Dibuat !');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }
            else{
                return redirect()->back()->with('err_message', 'Terjadi Kesalahan!');
            }
        }else{
            return redirect()->back()->with('err_message', 'Data Tidak Boleh Kosong!');
        }
    }

    //PO SubContractor - Plan Delivery
    public function subcontractorplandelivery()
    {
        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date           = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont Plan Delivery',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title   = "Sub Contractor - Plan Delivery";
            $link_search    = "carisubcontractorplandelivery";
            $link_reset     = "subcontractorplandelivery";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'];
            $actionmenu             = $this->PermissionActionMenu('subcontractornewpo');

            if($actionmenu->c==1){
                $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::where('status','P')->where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('DeliveryDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }else if($actionmenu->u==1){
                $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::where('status','P')
                    ->leftJoin('satria_ccr.vw_total_stock AS totalstock', 'totalstock.material_number', '=' ,'vw_qtytiket.Material')
                    ->leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_qtytiket.Material')
                    ->where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('DeliveryDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }else{
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::where('status','P')
                    ->leftJoin('satria_ccr.vw_total_stock AS totalstock', 'totalstock.material_number', '=' ,'vw_qtytiket.Material')
                    ->leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_qtytiket.Material')
                    ->whereIn('POID',$ongoingPOID)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('DeliveryDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','ItemNumber','TicketID')->distinct('POID','ItemNumber','TicketID')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }

            $material_potracking = VwViewTicket::select('Material')->distinct()->get()->toArray();
            $ccr_reqdate = VwPoTrackingReqDateMaterial::whereIn('material',$material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
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

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorplandelivery',
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
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    //PO SubContractor - Plan Delivery (by Date)
    public function carisubcontractorplandelivery(Request $request)
    {

        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date           = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont Plan Delivery',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title   = "Sub Contractor - Plan Delivery (by Date)";
            $link_search    = "carisubcontractorplandelivery";
            $link_reset     = "subcontractorplandelivery";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'];
            $actionmenu             = $this->PermissionActionMenu('subcontractornewpo');

                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::where('Number',$request->number)->where('status','P')->whereIn('POID',$ongoingPOID)
                    ->leftJoin('satria_ccr.vw_total_stock AS totalstock', 'totalstock.material_number', '=' ,'vw_qtytiket.Material')
                    ->leftJoin('satria_ccr.vw_potracking_reqdate_material AS reqdateccr', 'reqdateccr.material', '=' ,'vw_qtytiket.Material')
                    ->groupBy('Number', 'ItemNumber','TicketID')->orderBy('DeliveryDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorplandelivery',
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
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    //PO SubContractor - Plan Delivery (modal view ticket)
    public function subcontviewticket(Request $request)
    {
        $data = VwViewTicket::where('ID', $request->id)->groupBy('Number','ItemNumber','ID')->WhereNotIn('status' ,['C'])->orderBy('Status', 'asc')->get();
        $dataviewticket = VwViewTicket::where('ID', $request->id)->first();

        $data = array(
            'data' =>$data,
            'dataviewticket' =>$dataviewticket,
        );
        echo json_encode($data);
    }

    //PO SubContractor - Plan Delivery (Confirm Ticket by Warehouse)
    public function subcontractorconfirmticket(Request $request)
    {
        if(!empty($request->ID)){
            $date       = new DateTime();
            $datetime   = $date->format('Y-m-d h:i:s');
            $appsmenu   = Ticket::whereIn('ID', $request->ID)->first();
            $po    = VwViewTicket::whereIn('IDTicket', $request->ID)->groupBy('number')->get();
            $datavendor = count($po);
            if(!empty($appsmenu)||!empty($request->ID)){
                if($request->action == "Confirm Delivery"){
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
                            'menu'=>'Approve Ticket Subcont',
                            'comment'=>$request->remarks,
                            'created_at'=>$date
                        ]);

                        }
                        Notification::whereIn('Number',$number)->where('menu','Ticket Subcont')
                        ->update([
                            'is_read'=>3,
                         ]);
                    $update = DetailTicket::whereIn('ID',$request->ID)
                    ->update([
                        'status'        => 'A',
                        'AcceptedDate'  => $datetime,
                    ]);
                    if($update){
                        return redirect('subcontractorplandelivery')->with('suc_message', 'Ticket Accepted By Warehouse !');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
                elseif($request->action== "Update Delivery"){
                    // dd($request);
                    $data = count($request->IDS) ;
                    if($this->PermissionActionMenu('subcontractornewpo')->v==1){
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
                        return redirect('subcontractorplandelivery')->with('suc_message', 'Update Delivery Success !!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
                else{

                    $datavendor = count($po);
                        foreach ($po as $q) {
                            $ticketid[] = $q->TicketID;
                            $number[] = $q->Number;
                            $name[] = $q->Name;

                        }

                            Notification::whereIn('Number',$number)->where('menu','Ticket Subcont')
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
                        return redirect('subcontractorplandelivery')->with('suc_message', 'Ticket Di Cancel !');
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

    //PO SubContractor - Plan Delivery (History Ticket)
    public function subcontractorhistoryticket()
    {
        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date           = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont History Ticket',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title   = "Sub Contractor - History Ticket";
            $link_search    = "carisubcontractorhistoryticket";
            $link_reset     = "subcontractorplandelivery";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'];
            $actionmenu             = $this->PermissionActionMenu('subcontractornewpo');

            if($actionmenu->c==1  || $actionmenu->u==1){
                $kode                       = ['C','E'] ;
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::whereNotNull('Number')->where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kode)->groupBy('ID','ItemNumber')->orderBy('ItemNumber', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $kode                       = ['D','A','S','W','C','Q','R','X','E'] ;
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::whereNotNull('Number')->whereIn('status',$kode)->whereIn('POID',$ongoingPOID)->groupBy('ID','ItemNumber')->orderBy('ItemNumber', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorhistoryticket',
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
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }



    //PO SubContractor - Ready to Delivery
    public function subcontractorreadytodelivery()
    {
        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){

            $header_title   = "Sub Contractor - Ready to Delivery";
            $link_search    = "carisubcontractorreadytodelivery";
            $link_reset     = "subcontractorreadydelivery";

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont Ready to Delivery',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'];
            $actionmenu             = $this->PermissionActionMenu('subcontractornewpo');

            if($actionmenu->c==1){
                $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('status',$kodex)->whereIn('POID',$ongoingPOID)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ID', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }else if($actionmenu->u==1){
                $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('status',$kodex)->whereIn('POID',$ongoingPOID)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ID', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();

            }else if($actionmenu->r==1 || $actionmenu->v==1 || $actionmenu->d==1){
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwViewTicket::whereIn('status',$kodex)->whereIn('POID',$ongoingPOID)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ID', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorreadytodelivery',
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
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }


    //PO SubContractor - Ready to Delivery (Cek Ticket)
    public function subcontractorcheckticket(Request $request)
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
            Notification::where('Number',$dataviewticket->Number)->where('menu','Approve Ticket Subcont')
            ->update([
                'is_read'=>3,
             ]);
            $deliverydate = date('d/m/Y', strtotime($dataviewticket->DeliveryDate));
            $deliverytime = date('H:i:s', strtotime($dataviewticket->DeliveryDate));

            $dataallviewticket = VwViewTicket::where('TicketID', $request->TicketID)->select('ItemNumber','Material','Description','DeliveryDates','Quantity')->groupBy('Number','ItemNumber','TicketID')->get()->toArray();
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

            return $pdf->stream();
        }
        else{
            return redirect()->back()->with('err_message', 'Data Tidak Ditemukan');
        }

    }

    //PO SubContractor - History
    public function subcontractorhistory()
    {
        if($this->PermissionActionMenu('subcontractornewpo')->c==1 || $this->PermissionActionMenu('subcontractornewpo')->r==1  || $this->PermissionActionMenu('subcontractornewpo')->u==1 || $this->PermissionActionMenu('subcontractornewpo')->d==1 || $this->PermissionActionMenu('subcontractornewpo')->v==1){
            $date           = Carbon::now();
            LogHistory::updateOrCreate([
                'user'      => Auth::user()->id,
                'menu'      => 'PO Subcont History Po',
                'date'      => $date->toDateString(),
                'CreatedBy' => Auth::user()->name
            ],
                ['time'     => $date->toTimeString()
            ]);

            $header_title   = "Sub Contractor - History";
            $link_search    = "carisubcontractorhistory";
            $link_reset     = "subcontractorhistory";

            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $link_historyParking    = $this->historyparking;
            $kodex                  = ['A','D','S','W','Q','R','X'];
            $actionmenu             = $this->PermissionActionMenu('subcontractornewpo');

            if($actionmenu->c==1){
                $ongoingPOID                = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontHistory::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('VendorCode',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('VendorCode',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }
            else if($actionmenu->u==1){
                $ongoingPOID                = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontHistory::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::where('NRP',Auth::user()->email)->whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::where('NRP',Auth::user()->email)->select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }
            else{
                $ongoingPOID                = VwSubcontOngoing::select('POID')->distinct()->get()->toArray();
                $data                       = VwSubcontHistory::groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->orderBy('ConfirmedDate', 'asc')->get();
                $NewPoSubcont               = VwSubcontNewpo::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $OngoingPoSubcont           = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $PlanDeliveryPoSubcont      = VwViewTicket::whereIn('POID',$ongoingPOID)->where('status','P')->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $ReadyToDeliveryPoSubcont   = VwViewTicket::whereIn('POID',$ongoingPOID)->whereIn('status',$kodex)->select('POID','DeliveryDate')->distinct('POID','DeliveryDate')->count();
                $HistoryPoSubcont           = VwSubcontHistory::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            }

            $countNewpoSubcontractor            = $NewPoSubcont;
            $countOngoingSubcontractor          = $OngoingPoSubcont;
            $countPlanDeliverySubcontractor     = $PlanDeliveryPoSubcont;
            $countReadyToDeliverySubcontractor  = $ReadyToDeliveryPoSubcont;
            $countHistorySubcontractor          = $HistoryPoSubcont;

            return view('po-tracking/subcontraktor/subcontractorhistory',
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
                'link_historyParking',
                'link_historyPO',
                'countNewpoSubcontractor',
                'countOngoingSubcontractor',
                'countPlanDeliverySubcontractor',
                'countReadyToDeliverySubcontractor',
                'countHistorySubcontractor',
                'actionmenu'
            ));
        }
        else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }



    // PO SubContractor - History (View Subcont History)
    public function viewcancelsubcontractor(Request $request)
    {
        $Subcont = VwPo::where('ID', $request->id)->first();
        $viewtable = VwSubcontHistory::where('ID', $request->id)->orwhere('ParentID', $request->id)->get();
        $Vendors = Vendors::where('Code', $Subcont->VendorCode)->get();

        $data = array(
            'subcont' =>$Subcont,
            'viewtable' =>$viewtable,
            'vendors' => $Vendors,
            );

        echo json_encode($data);
    }

    // PO SubContractor - NewPO (ConfirmPO)
    // public function subcontractorInsert(Request $request)
    // {
    //     // Get first data by id
    //     $appsmenu = VwSubcontNewpo::where('ID', $request->ID)->first();
    //     // Jumlah barang yang disetujui vendor
    //     $qty = $request->ConfirmedQuantity ;
    //     $jumlah = 0;
    //     // menjumlahkan semua barang menjadi 1
    //     foreach ($qty as $q) {
    //         $jumlah+= $q;
    //     }
    //     // Jika jumlah barang tidak sesuai
    //     if($jumlah > $appsmenu->Quantity or $jumlah < $appsmenu->Quantity  ){
    //         return redirect()->back()->with('err_message', 'Check Total Quantity!');
    //     }

    //     if(!empty($appsmenu)){
    //         // Jumlah Partial Data
    //         if($request->action == "Save PO") //Jika modal Confirm PO klik Save
    //         {
    //             $data = count($request->ConfirmedQuantity);
    //             // Jika Delivery Method Full atau Partial satu
    //             if((($request->DeliveryDate == $request->ConfirmedDate[0]) || ($request->DeliveryDate >= $request->ConfirmedDate[0])) && $data == 1)
    //             {
    //                 $confirmed  = 1;
    //                 $active     = 2;

    //                 $releaseDate        = Carbon::parse($appsmenu->ReleaseDate);
    //                 $confirmedDate      = Carbon::createFromFormat('d/m/Y', trim($request->ConfirmedDate[0]))->format('Y-m-d');
    //                 $confirmedDateFix   = Carbon::parse($confirmedDate);
    //                 $test               = $releaseDate->diffInDays($confirmedDateFix); //berdasarkan selisih dari release date dengan confirmed date

    //                 $dataLeadTime       = SubcontLeadtimeMaster::all();
    //                 $totalleadtime      = ($test - 1);

    //                 $PB = round($totalleadtime * $dataLeadTime[0]->Value);
    //                 $Setting = round($totalleadtime * $dataLeadTime[1]->Value);
    //                 $Fullweld = round($totalleadtime * $dataLeadTime[2]->Value);
    //                 $Primer = round($totalleadtime * $dataLeadTime[3]->Value);
    //                 // dd($active);
    //             }
    //             // Jika Delivery Method Partial lebih dari satu
    //             else{
    //                 $confirmed  = NULL;
    //                 $active     = 1;
    //                 $test       = NULL;
    //                 $PB         = NULL;
    //                 $Setting    = NULL;
    //                 $Fullweld   = NULL;
    //                 $Primer     = NULL;
    //                 // dd($active);
    //             }

    //             //jika data duplicate
    //             Pdi::where('ParentID', $request->ID)->whereNull('ActiveStage')->whereNull('IsClosed')->delete();

    //             for ($i = 0; $i < $data; $i++) {
    //                 if ($i == 0) { // data partial yang pertama
    //                         $update = Pdi::where('ID', $request->ID)
    //                         ->update([
    //                             'ParentID'          => $request->ID,
    //                             'ConfirmedDate'     => Carbon::createFromFormat('d/m/Y', trim($request->ConfirmedDate[0]))->format('Y-m-d'),
    //                             'ConfirmedQuantity' => trim($request->ConfirmedQuantity[0]),
    //                             'ConfirmedItem'     => $confirmed,
    //                             'ActiveStage'       => $active,
    //                             'LeadTimeItem'      => $test,
    //                             'PB'                => $PB ,
    //                             'Setting'           => $Setting ,
    //                             'Fullweld'          => $Fullweld,
    //                             'Primer'            => $Primer
    //                             ]);
    //                 }
    //                 else{
    //                     $newinsert = [
    //                         'POID'              => trim($appsmenu->POID),
    //                         'PRNumber'          => trim($appsmenu->PRNumber),
    //                         'PRCreateDate'      => trim($appsmenu->PRCreateDate),
    //                         'PRReleaseDate'     => trim($appsmenu->PRReleaseDate),
    //                         'DeliveryDate'      => trim($appsmenu->DeliveryDate),
    //                         'ParentID'          => trim($appsmenu->ID),
    //                         'ItemNumber'        => trim($appsmenu->ItemNumber),
    //                         'Material'          => trim($appsmenu->Material),
    //                         'MaterialVendor'    => trim($appsmenu->MaterialVendor),
    //                         'Description'       => trim($appsmenu->Description),
    //                         'NetPrice'          => trim($appsmenu->NetPrice),
    //                         'Currency'          => trim($appsmenu->Currency),
    //                         'Quantity'          => trim($appsmenu->Quantity),
    //                         'OpenQuantity'          => trim($appsmenu->OpenQuantity),
    //                         'ActiveStage'       => 1,
    //                         'ConfirmedQuantity' => trim($request->ConfirmedQuantity[$i]),
    //                         'ConfirmedDate'     => Carbon::createFromFormat('d/m/Y', trim($request->ConfirmedDate[$i]))->format('Y-m-d'),
    //                         // 'LeadTimeItem'      => $test2,
    //                     ];
    //                     $update = Pdi::insert($newinsert);
    //                 }
    //             }
    //             if($update){
    //                 return redirect('subcontractornewpo')->with('suc_message', 'Data processed successfully!');
    //             }else{
    //                 return redirect()->back()->with('err_message', 'Data failed to process!');
    //             }
    //         }
    //         if($request->action == "Cancel PO"){ //Jika modal Confirm PO klik Cancel

    //             Pdi::where('ParentID', $request->ID)->whereNull('ActiveStage')->whereNull('IsClosed')->delete(); //untuk delete data duplicate

    //             $cancel =  Pdi::where('ID', $request->ID)
    //             ->update([
    //                 'ActiveStage' =>  '1',
    //                 'ConfirmedItem' =>  '0',
    //                 'IsClosed'      => 'C',
    //             ]);
    //             if($cancel){
    //                 return redirect('subcontractorhistory')->with('err_message', 'PO cancelled!');
    //             }else{
    //                 return redirect()->back()->with('err_message', 'Data failed to process!');
    //             }
    //         }
    //     }else{
    //         return redirect()->back()->with('err_message', 'Data not found!');
    //     }
    // }

    // PO SubContractor - NewPO (NegotiatePO)
    // public function subcontractorUpdate(Request $request)
    // {
    //     $data = count($request->ID);

    //     if($request->action == "Yes"){
    //         for ($i = 0; $i < $data; $i++) {
    //             $appsmenu = Pdi::where('ID', $request->ID[$i])->first();
    //             $dataLeadTime = SubcontLeadtimeMaster::all();
    //             $confirmedDateFix = Carbon::parse($appsmenu->ConfirmedDate);
    //             $test3 = $confirmedDateFix->diffInDays(Carbon::now());
    //             $totalleadtime = ($test3 - 1);

    //             $PB = round($totalleadtime * $dataLeadTime[0]->Value);
    //             $Setting = round($totalleadtime * $dataLeadTime[1]->Value);
    //             $Fullweld = round($totalleadtime * $dataLeadTime[2]->Value);
    //             $Primer = round($totalleadtime * $dataLeadTime[3]->Value);

    //             $update =  Pdi::where('ID', $request->ID[$i])
    //             ->update([
    //                 'ActiveStage'   => '2',
    //                 'ConfirmedItem' => '1',
    //                 'IsClosed' => NULL,
    //                 'LeadTimeItem' => $test3,
    //                 'PB' => $PB,
    //                 'Setting' => $Setting ,
    //                 'Fullweld' => $Fullweld,
    //                 'Primer' => $Primer,
    //             ]);
    //         }
    //         if($update){
    //             return redirect('subcontractorongoing')->with('suc_message', 'Data saved successfully!');
    //         }else{
    //             return redirect()->back()->with('err_message', 'Data failed to save!');
    //         }
    //     }
    //     elseif($request->action == "Update"){
    //         $data = count($request->IDS) ;

    //         for ($i = 0; $i < $data; $i++) {
    //                 $update =  Pdi::where('ID', $request->IDS[$i])
    //                 ->update([
    //                 'ConfirmedDate'   =>  Carbon::createFromFormat('d/m/Y', $request->ConfirmedDate[$i])->format('Y-m-d'),
    //                 ]);
    //             }
    //         if($update){
    //             return redirect('subcontractornewpo')->with('suc_message', 'PO Di Update!');
    //         }else{
    //             return redirect()->back()->with('err_message', 'Data gagal disimpan!');
    //         }
    //     }
    //     if($request->action == "Cancel"){
    //         for ($i = 0; $i < $data; $i++) {
    //             $cancel =  Pdi::where('ID', $request->ID[$i])
    //             ->update([
    //                 'ConfirmedItem' =>  '0',
    //                 'IsClosed'      => 'C',
    //             ]);
    //         }
    //         if($cancel){
    //             return redirect('subcontractorhistory')->with('suc_message', 'Data cancelled successfully!');
    //         }else{
    //             return redirect()->back()->with('err_message', 'Data failed to cancel!');
    //         }
    //     }
    // }

}
