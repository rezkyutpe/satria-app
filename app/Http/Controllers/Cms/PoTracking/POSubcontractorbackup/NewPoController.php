<?php

namespace App\Http\Controllers\Cms\PoTracking\POSubcontractor;
use App\Http\Controllers\Controller;
use App\Models\Table\PoTracking\DetailTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwOngoingAll;
use App\Models\Table\PoTracking\SubcontLeadtimeMaster;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use Illuminate\Support\Carbon;

class NewPoController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('subcontractornewpo') == 0 && $this->PermissionMenu('subcontractornewpo-nonmanagement') == 0 && $this->PermissionMenu('subcontractornewpo-whs') == 0 && $this->PermissionMenu('subcontractornewpo-proc') == 0 && $this->PermissionMenu('subcontractornewpo-vendor') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //Po Local New PO View Management
    public function subcontractornewpo()
    {

        if( $this->PermissionActionMenu('subcontractornewpo')->r==1){
            $header_title                   = "PO SUBCONT - NEW PO";
            $date   = Carbon::now();

            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont New Po',
                'description' => 'Display New PO',
                'date'  => $date->toDateString(),
                'ponumber' => NULL,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title ,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
             ],
            ['time'     => $date->toTimeString()
            ]);

                $link_newPO                     = 'subcontractornewpo';
                $link_ongoing                   = 'subcontractorongoing';
                $link_planDelivery              = 'subcontractorplandelivery';
                $link_readyToDelivery           = 'subcontractorreadydelivery';
                $link_historyPO                 = 'subcontractorhistory';

            $kodex = ['A','D'] ;
            $actionmenu =  $this->PermissionActionMenu('subcontractornewpo');
                $data = VwSubcontNewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number', 'ItemNumber','TicketID')->whereIn('status',$kodex)->count();

            $countNewpoSubcont      = $data->count();
            $countOngoingSubcont    = $OngoingSubcont;
            $countHistorySubcont    = $HistorySubcont;
            $countPlanDeliverySubcont      = $planDelivery;
            $countReadyToDeliverySubcont   = $readyToDelivery;
            return view('po-tracking/subcontractor/subcontractornewpo',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }
    //Po Local New PO View NonManagement
    public function subcontractornewpoNonManagement()
    {
        if( $this->PermissionActionMenu('subcontractornewpo-nonmanagement')->r==1){
            $header_title                   = "PO SUBCONT - NEW PO";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont New Po',
                'description' => 'Display New PO',
                'date'  => $date->toDateString(),
                'ponumber' => NULL,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title ,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
             ],
            ['time'     => $date->toTimeString()
            ]);

                $link_newPO                     = 'subcontractornewpo-nonmanagement';
                $link_ongoing                   = 'subcontractorongoing-nonmanagement';
                $link_planDelivery              = 'subcontractorplandelivery-nonmanagement';
                $link_readyToDelivery           = 'subcontractorreadydelivery-nonmanagement';
                $link_historyPO                 = 'subcontractorhistory-nonmanagement';

            $kodex = ['A','D'] ;
            $actionmenu =  $this->PermissionActionMenu('subcontractornewpo-nonmanagement');

                $data = VwSubcontNewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number', 'ItemNumber','TicketID')->whereIn('status',$kodex)->count();

            $countNewpoSubcont      = $data->count();
            $countOngoingSubcont    = $OngoingSubcont;
            $countHistorySubcont    = $HistorySubcont;
            $countPlanDeliverySubcont      = $planDelivery;
            $countReadyToDeliverySubcont   = $readyToDelivery;
            return view('po-tracking/subcontractor/subcontractornewpononmanagement',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }
      //Po Local New PO View Proc
      public function subcontractornewpoProc()
      {
          if( $this->PermissionActionMenu('subcontractornewpo-proc')->r==1){
              $header_title                   = "PO SUBCONT - NEW PO";
              $date   = Carbon::now();
              LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont New Po',
                'description' => 'Display New PO',
                'date'  => $date->toDateString(),
                'ponumber' => NULL,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title ,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
             ],
            ['time'     => $date->toTimeString()
            ]);

                  $link_newPO                     = 'subcontractornewpo-proc';
                  $link_ongoing                   = 'subcontractorongoing-proc';
                  $link_planDelivery              = 'subcontractorplandelivery-proc';
                  $link_readyToDelivery           = 'subcontractorreadydelivery-proc';
                  $link_historyPO                 = 'subcontractorhistory-proc';

              $kodex = ['A','D'] ;
              $actionmenu =  $this->PermissionActionMenu('subcontractornewpo-proc');
                  $data = VwSubcontNewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                  $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->where('NRP',Auth::user()->email)->distinct('POID','ItemNumber')->count();
                  $HistorySubcont = VwSubcontHistory::select('Number','ItemNumber')->where('NRP',Auth::user()->email)->distinct('Number','ItemNumber')->count();
                  $planDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->
                  leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                  leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
                  {
                      $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                      ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                  })
                  ->where('po.CreatedBy',Auth::user()->email)
                  ->where('detailticketingdelivery.status','P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();
                  $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
                  ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
                  {
                      $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                      ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                  })
                  ->where('po.CreatedBy',Auth::user()->email)
                  ->whereIn('detailticketingdelivery.status',$kodex)->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();

              $countNewpoSubcont      = $data->count();
              $countOngoingSubcont    = $OngoingSubcont;
              $countHistorySubcont    = $HistorySubcont;
              $countPlanDeliverySubcont      = $planDelivery;
              $countReadyToDeliverySubcont   = $readyToDelivery;
              return view('po-tracking/subcontractor/subcontractornewpoproc',
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
              ));
          }else{
              return redirect('/')->with('err_message', 'Akses Ditolak!');
          }

      }
      //Po Local New PO View Vendor
      public function subcontractornewpoVendor()
      {
          if( $this->PermissionActionMenu('subcontractornewpo-vendor')->r==1){
              $header_title                   = "PO SUBCONT - NEW PO";
              $date   = Carbon::now();
              LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont New Po',
                'description' => 'Display New PO',
                'date'  => $date->toDateString(),
                'ponumber' => NULL,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title ,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
             ],
            ['time'     => $date->toTimeString()
            ]);
                  $link_newPO                     = 'subcontractornewpo-vendor';
                  $link_ongoing                   = 'subcontractorongoing-vendor';
                  $link_planDelivery              = 'subcontractorplandelivery-vendor';
                  $link_readyToDelivery           = 'subcontractorreadydelivery-vendor';
                  $link_historyPO                 = 'subcontractorhistory-vendor';

              $kodex = ['A','D'] ;
              $actionmenu =  $this->PermissionActionMenu('subcontractornewpo-vendor');

              $data = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
              $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->where('VendorCode',Auth::user()->email)->distinct('POID','ItemNumber')->count();
              $HistorySubcont = VwSubcontHistory::select('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->distinct('Number','ItemNumber')->count();
              $planDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->
              leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
              leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
              {
                  $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                  ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
              })
              ->where('uservendors.VendorCode',Auth::user()->email)
              ->where('detailticketingdelivery.status','P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();
              $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
              ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
              {
                  $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                  ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
              })
              ->where('uservendors.VendorCode',Auth::user()->email)
              ->whereIn('detailticketingdelivery.status',$kodex)->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->count();

              $countNewpoSubcont      = $data->count();
              $countOngoingSubcont    = $OngoingSubcont;
              $countHistorySubcont    = $HistorySubcont;
              $countPlanDeliverySubcont      = $planDelivery;
              $countReadyToDeliverySubcont   = $readyToDelivery;
              return view('po-tracking/subcontractor/subcontractornewpovendor',
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
              ));
          }else{
              return redirect('/')->with('err_message', 'Akses Ditolak!');
          }

      }
        //Po Local New PO View NonManagement
    public function subcontractornewpoWhs()
    {
        if( $this->PermissionActionMenu('subcontractornewpo-whs')->r==1){
            $header_title                   = "PO SUBCONT - NEW PO";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont New Po',
                'description' => 'Display New PO',
                'date'  => $date->toDateString(),
                'ponumber' => NULL,
                'poitem' => NULL,
                'userlogintype' => Auth::user()->title ,
                'vendortype' => 'Local',
                'CreatedBy'  => Auth::user()->name,
             ],
            ['time'     => $date->toTimeString()
            ]);

                $link_newPO                     = 'subcontractornewpo-whs';
                $link_ongoing                   = 'subcontractorongoing-whs';
                $link_planDelivery              = 'subcontractorplandelivery-whs';
                $link_readyToDelivery           = 'subcontractorreadydelivery-whs';
                $link_historyPO                 = 'subcontractorhistory-whs';
            $kodex = ['A','D'] ;
            $actionmenu =  $this->PermissionActionMenu('subcontractornewpo-whs');
                $data = VwSubcontNewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistorySubcont = VwSubcontHistory::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number', 'ItemNumber','TicketID')->whereIn('status',$kodex)->count();

            $countNewpoSubcont      = $data->count();
            $countOngoingSubcont    = $OngoingSubcont;
            $countHistorySubcont    = $HistorySubcont;
            $countPlanDeliverySubcont      = $planDelivery;
            $countReadyToDeliverySubcont   = $readyToDelivery;
            return view('po-tracking/subcontractor/subcontractornewpononmanagement',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }

     // New PO Insert
     public function ConfirmPOSubcont(Request $request)
    {
    if($this->PermissionActionMenu('subcontractornewpo-vendor')){
            $links = "subcontractornewpo-vendor";
        }else if($this->PermissionActionMenu('subcontractornewpo')){
            $links = "subcontractornewpo";
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }
        $appsmenu = VwSubcontNewpo::where('POID', $request->POID)->get();
        $date   = Carbon::now();

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
           LogHistory::create([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Subcont New Po',
            'description' => 'Confirm PO',
            'date'  => $date->toDateString(),
            'time'     => $date->toTimeString(),
            'ponumber' => $p->Number,
            'poitem' => $p->ItemNumber,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Subcont',
            'CreatedBy'  => Auth::user()->name,
             ]);

       }

        if($update){
            return redirect($links)->with('suc_message', 'PO Di ACC!');
        }else{
            return redirect()->back()->with('err_message', 'Data gagal disimpan!');
        }
    }
     public function poInsert(Request $request)
     {
        if($this->PermissionActionMenu('subcontractornewpo-vendor')){
            $links = "subcontractornewpo-vendor";
        }else if($this->PermissionActionMenu('subcontractornewpo')){
            $links = "subcontractornewpo";
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }
         if($request->ID == null){
             return redirect()->back()->with('err_message', 'Please Select Item!');
         }else{
             $varmain = Pdi::where('ID', $request->ID[0])->first();
             $varCountItemNumber = VwnewpoAll::select('ItemNumber')->where('POID',$varmain->POID)->distinct()->count();

             for($n=0;$n<$varCountItemNumber;$n++){
                 $strIDpartial           = "IDpartial".$n;
                 $strConfirmedQuantity   = "ConfirmedQuantity".$n;
                 $strConfirmedDate       = "ConfirmedDate".$n;

                 if($request->$strIDpartial == null){
                     continue;
                 }
                 else{
                     $varIDpartial           = $request->$strIDpartial;
                     $varConfirmedQuantity   = $request->$strConfirmedQuantity;
                     $varConfirmedDate       = $request->$strConfirmedDate;

                     $totalQty               = array_sum($varConfirmedQuantity);
                     $countQty               = count($varConfirmedQuantity);

                     for($a=0;$a<count($varConfirmedDate);$a++){
                         $varPartial[] = [
                             'ID'                => $varIDpartial[$a],
                             'No'                => $a,
                             'ConfirmedQuantity' => $varConfirmedQuantity[$a],
                             'TotalQuantity'     => $totalQty,
                             'ConfirmedDate'     => $varConfirmedDate[$a],
                             'CountQty'          => $countQty
                         ];
                     }
                 }
             }
             if(!empty($varmain)){
                 $checkvendortype = VwnewpoAll::where('ID', $request->ID[0])->first();

                 if($request->action== "Save"){
                    $date   = Carbon::now();
                     foreach($request->ID as $itemID){
                         foreach($varPartial as $eachitem){
                             if($eachitem['ID'] != $itemID){
                                 continue;
                             }
                             else{
                                 $appsmenu = Pdi::where('ID', $eachitem['ID'])->first();

                                 //Cek Total Quantity partial
                                 if($eachitem['TotalQuantity'] > $appsmenu->Quantity or $eachitem['TotalQuantity'] <= 0){
                                     return redirect()->back()->with('err_message', 'Cek Total Qty!');
                                 }

                                 $checkpo    = VwongoingAll::where('POID', $appsmenu->POID)->whereNotNull('ActiveStage')->first();
                                 $date1      = Carbon::createFromFormat('Y-m-d', $appsmenu->DeliveryDate)->format('Y-m-d');
                                 $date2      = Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d');

                                 if($date1 >= $date2 && $eachitem['CountQty'] == 1 && $eachitem['TotalQuantity'] == $appsmenu->Quantity){
                                     if(isset($checkpo)){
                                         $confirmed = '1';
                                         $active = $checkpo->ActiveStage;
                                         if(($appsmenu->ReleaseDate < Carbon::now()->format('Y-m-d')) || $appsmenu->ReleaseDate = null){
                                            $leadtimeitem        = $appsmenu->WorkTime;
                                        }
                                        else{
                                            $releaseDate        = Carbon::parse($appsmenu->ReleaseDate);
                                            $confirmedDate      = Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d');
                                            $confirmedDateFix   = Carbon::parse($confirmedDate);
                                            $leadtimeitem       = $releaseDate->diffInDays($confirmedDateFix);
                                        }

                                        $dataLeadTime       = SubcontLeadtimeMaster::all();
                                        $totalleadtime      = ($leadtimeitem - 1);

                                        $PB         = round($totalleadtime * $dataLeadTime[0]->Value);
                                        $Setting    = round($totalleadtime * $dataLeadTime[1]->Value);
                                        $Fullweld   = round($totalleadtime * $dataLeadTime[2]->Value);
                                        $Primer     = round($totalleadtime * $dataLeadTime[3]->Value);
                                     }
                                     else{
                                         $confirmed = '1';
                                         $active = "2";
                                         if(($appsmenu->ReleaseDate < Carbon::now()->format('Y-m-d')) || $appsmenu->ReleaseDate = null){
                                            $leadtimeitem        = $appsmenu->WorkTime;
                                        }
                                        else{
                                            $releaseDate        = Carbon::parse($appsmenu->ReleaseDate);
                                            $confirmedDate      = Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d');
                                            $confirmedDateFix   = Carbon::parse($confirmedDate);
                                            $leadtimeitem       = $releaseDate->diffInDays($confirmedDateFix);
                                        }

                                        $dataLeadTime       = SubcontLeadtimeMaster::all();
                                        $totalleadtime      = ($leadtimeitem - 1);

                                        $PB         = round($totalleadtime * $dataLeadTime[0]->Value);
                                        $Setting    = round($totalleadtime * $dataLeadTime[1]->Value);
                                        $Fullweld   = round($totalleadtime * $dataLeadTime[2]->Value);
                                        $Primer     = round($totalleadtime * $dataLeadTime[3]->Value);
                                     }

                                    LogHistory::updateOrCreate([
                                        'user'  => Auth::user()->email,
                                        'menu'  => 'PO Subcont New PO',
                                        'description' => 'Confirm New PO',
                                        'date'  => $date->toDateString(),
                                        'time'     => $date->toTimeString(),
                                        'ponumber' => $checkvendortype->Number,
                                        'poitem' => NULL,
                                        'userlogintype' => Auth::user()->title ,
                                        'vendortype' => 'Subcont',
                                        'CreatedBy'  => Auth::user()->name,
                                    ]);
                                 }
                                 else{
                                     $confirmed = NULL;
                                     $active = "1";
                                     $leadtimeitem   = NULL;
                                     $PB             = NULL;
                                     $Setting        = NULL;
                                     $Fullweld       = NULL;
                                     $Primer         = NULL;
                                     Notification::create([
                                        'Number'         => $checkvendortype->Number,
                                        'Subjek'         => "Vendor Negotiated PO",
                                        'user_by'=>Auth::user()->name,
                                        'user_to'=>$checkvendortype->NRP,
                                        'is_read'=>1,
                                        'menu'=>"subcontractornewpo-proc",
                                        'comment'=>"New PO $checkvendortype->Number",
                                        'created_at'=>$date
                                    ]);
                                    LogHistory::updateOrCreate([
                                        'user'  => Auth::user()->email,
                                        'menu'  => 'PO Subcont New PO',
                                        'description' => 'Negotiated New PO',
                                        'date'  => $date->toDateString(),
                                        'time'     => $date->toTimeString(),
                                        'ponumber' => $checkvendortype->Number,
                                        'poitem' => NULL,
                                        'userlogintype' => Auth::user()->title ,
                                        'vendortype' => 'Subcont',
                                        'CreatedBy'  => Auth::user()->name,
                                    ]);
                                 }

                                 if($eachitem['No'] == 0){
                                     $update = Pdi::where('ID', $appsmenu->ID)
                                     ->update([
                                         'ConfirmedDate'     => Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d'),
                                         'ConfirmedQuantity' => $eachitem['ConfirmedQuantity'],
                                         'ConfirmedItem'     => $confirmed,
                                         'ParentID'          => $appsmenu->ID,
                                         'ActiveStage'       => $active,
                                         'LeadTimeItem'      => $leadtimeitem,
                                         'PB'                => $PB,
                                         'Setting'           => $Setting,
                                         'Fullweld'          => $Fullweld,
                                         'Primer'            => $Primer
                                     ]);

                                     if($update){
                                         $varStatus[] = 'Success';
                                     }
                                     else{
                                         $varStatus[] = 'Failed';
                                     }
                                 }
                                 else{
                                     $newinsert = [
                                         'POID'              => $appsmenu->POID,
                                         'PRNumber'          => $appsmenu->PRNumber,
                                         'PRCreateDate'      => $appsmenu->PRCreateDate,
                                         'PRReleaseDate'     => $appsmenu->PRReleaseDate,
                                         'DeliveryDate'      => $appsmenu->DeliveryDate,
                                         'ParentID'          => $appsmenu->ID,
                                         'ItemNumber'        => $appsmenu->ItemNumber,
                                         'Material'          => $appsmenu->Material,
                                         'MaterialVendor'    => $appsmenu->MaterialVendor,
                                         'Description'       => $appsmenu->Description,
                                         'NetPrice'          => $appsmenu->NetPrice,
                                         'Currency'          => $appsmenu->Currency,
                                         'Quantity'          => $appsmenu->Quantity,
                                         'OpenQuantity'      => $appsmenu->OpenQuantity,
                                         'ActiveStage'       => 1,
                                         'ConfirmedQuantity' => $eachitem['ConfirmedQuantity'],
                                         'ConfirmedDate'     => Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d'),
                                         'LeadTimeItem'      => $leadtimeitem,
                                         'PB'                => $PB,
                                         'Setting'           => $Setting,
                                         'Fullweld'          => $Fullweld,
                                         'Primer'            => $Primer,
                                         'PayTerms'          => $appsmenu->PayTerms,
                                         'DocumentNumberItem'=> $appsmenu->DocumentNumberItem,
                                         'WorkTime'          => $appsmenu->WorkTime,
                                         'OpenQuantity'      => $appsmenu->OpenQuantity,
                                         'OpenQuantityIR'    => $appsmenu->OpenQuantityIR,
                                         'CreatedBy'         => Auth::user()->name
                                     ];
                                     $update = Pdi::insert($newinsert);

                                     if($update){
                                         $varStatus[] = 'Success';

                                     }
                                     else{
                                         $varStatus[] = 'Failed';
                                     }
                                 }

                             }
                         }
                     }
                     if(in_array('Failed',$varStatus)){
                         return redirect()->back()->with('err_message', 'Data gagal diproses!');
                     }else{
                         return redirect($links)->with('suc_message', 'Data berhasil diproses!');
                     }

                 }
                 else{
                     $date   = Carbon::now();

                     Notification::create([
                         'Number'         => $checkvendortype->Number,
                         'Subjek'         => "Cancel PO",
                         'user_by'=>Auth::user()->name,
                         'user_to'=>$checkvendortype->NRP,
                         'is_read'=>1,
                         'menu'=>"subcontractorhistory-proc",
                         'comment'=>"Cancel PO $checkvendortype->Number",
                         'created_at'=>$date
                     ]);
                     LogHistory::updateOrCreate([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Subcont New PO',
                        'description' => 'Cancel PO',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $checkvendortype->Number,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Subcont',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                     foreach($request->ID as $itemID){
                         $update = Pdi::where('ID', $itemID)
                             ->update([
                                 'IsClosed'=>"C",
                                 'ActiveStage'=>1
                             ]);
                         if($update){
                             $varStatus[] = 'Success';
                         }
                         else{
                             $varStatus[] = 'Failed';
                         }
                     }
                     if(in_array('Failed',$varStatus)){
                         return redirect()->back()->with('err_message', 'Data gagal diproses!');
                     }else{
                         return redirect($links)->with('suc_message', 'PO Item berhasil di Cancel!');
                     }
                 }
             }
             else{
                 return redirect()->back()->with('err_message', 'Please Select Item!');
             }
         }
     }
     //viewnegosiasi
     public function view_negosiasipo(Request $request)
     {
         $dataid = VwnewpoAll::where('POID', $request->number)->where('ItemNumber', $request->item)->first();
         $data = VwnewpoAll::where('POID', $request->number)->where('ActiveStage',1)->distinct()->orderBy('ItemNumber','Asc')->get();

         if($this->PermissionActionMenu('subcontractornewpo-vendor')){
            $status =  1;
        }else if($this->PermissionActionMenu('subcontractornewpo')){
            $status =  1;
        }else if($this->PermissionActionMenu('subcontractornewpo-proc')){
            $status =  2;
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }

         $material_potracking    = VwnewpoAll::where('POID', $request->number)->select('Material')->distinct()->get()->toArray();
         $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material',$material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
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

         $data = array(
             'dataid'      => $dataid,
             'data'        => $data,
             'status'  => $status,
         );

         echo json_encode($data);
     }
      // New PO Update
     public function poUpdate(Request $request)
     {

        if($this->PermissionActionMenu('subcontractornewpo-vendor')){
            $links = "subcontractornewpo-vendor";
        }else if($this->PermissionActionMenu('subcontractornewpo')){
            $links = "subcontractornewpo";
        }else if($this->PermissionActionMenu('subcontractornewpo-proc')){
            $links = "subcontractornewpo-proc";
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }

         if(!empty($request->ID)){
             $appsmenu = Pdi::whereIn('ID', $request->ID)->get();

             if(!empty($appsmenu)){
                 $cekvendortype = VwnewpoAll::where('ID', $request->ID)->first();
                 $date   = Carbon::now();
                 $po= Pdi::where('ID', $request->ID)->first();
                 if($request->action == "Yes"){
                    Notification::where('Number',$cekvendortype->Number)->where('Subjek','Vendor Negotiated PO')
                    ->update([
                        'is_read'=>3,
                     ]);
                     LogHistory::updateOrCreate([
                         'user'  => Auth::user()->email,
                         'menu'  => 'PO Subcont New Po',
                         'description' => 'Approve Negotiated PO',
                         'date'  => $date->toDateString(),
                         'time'     => $date->toTimeString(),
                         'ponumber' => $cekvendortype->Number,
                         'poitem' => NULL,
                         'userlogintype' => Auth::user()->title ,
                         'vendortype' => 'Local',
                         'CreatedBy'  => Auth::user()->name,
                     ]);
                     $cekongoing = VwOngoingAll::where('POID', $po->POID)->whereNotNull('ActiveStage')->first();
                     if(isset($cekongoing)) {

                        for($i = 0; $i < $appsmenu->count(); $i++){
                            if(($appsmenu[$i]->ConfirmedDate < Carbon::now()->format('Y-m-d')) || $appsmenu[$i]->ConfirmedDate = null){
                                $leadtimeitem        = $appsmenu[$i]->WorkTime;
                            }
                            else{
                                $releaseDate        = Carbon::parse($appsmenu[$i]->ReleaseDate);
                                $confirmedDate      = Carbon::createFromFormat('d/m/Y', $request->ConfirmedDate[$i])->format('Y-m-d');
                                $confirmedDateFix   = Carbon::parse($confirmedDate);
                                $leadtimeitem       = $releaseDate->diffInDays($confirmedDateFix);
                            }

                            $dataLeadTime       = SubcontLeadtimeMaster::all();
                            $totalleadtime      = ($leadtimeitem - 1);

                            $PB         = round($totalleadtime * $dataLeadTime[0]->Value);
                            $Setting    = round($totalleadtime * $dataLeadTime[1]->Value);
                            $Fullweld   = round($totalleadtime * $dataLeadTime[2]->Value);
                            $Primer     = round($totalleadtime * $dataLeadTime[3]->Value);

                            $update =  Pdi::where('ID', $appsmenu[$i]->ID)
                            ->update([
                                'ActiveStage'   => $cekongoing->ActiveStage,
                                'ConfirmedItem' => 1,
                                'IsClosed'      => NULL,
                                'LeadTimeItem'  => $leadtimeitem,
                                'PB'            => $PB,
                                'Setting'       => $Setting,
                                'Fullweld'      => $Fullweld,
                                'Primer'        => $Primer
                            ]);
                        }
                     }
                     else{
                        for($i = 0; $i < $appsmenu->count(); $i++){
                            if(($appsmenu[$i]->ConfirmedDate < Carbon::now()->format('Y-m-d')) || $appsmenu[$i]->ConfirmedDate = null){
                                $leadtimeitem        = $appsmenu[$i]->WorkTime;
                            }
                            else{
                                $releaseDate        = Carbon::parse($appsmenu[$i]->ReleaseDate);
                                $confirmedDate      = Carbon::createFromFormat('d/m/Y', $request->ConfirmedDate[$i])->format('Y-m-d');
                                $confirmedDateFix   = Carbon::parse($confirmedDate);
                                $leadtimeitem       = $releaseDate->diffInDays($confirmedDateFix);
                            }

                            $dataLeadTime       = SubcontLeadtimeMaster::all();
                            $totalleadtime      = ($leadtimeitem - 1);

                            $PB         = round($totalleadtime * $dataLeadTime[0]->Value);
                            $Setting    = round($totalleadtime * $dataLeadTime[1]->Value);
                            $Fullweld   = round($totalleadtime * $dataLeadTime[2]->Value);
                            $Primer     = round($totalleadtime * $dataLeadTime[3]->Value);

                            $update =  Pdi::where('ID', $appsmenu[$i]->ID)
                            ->update([
                                'ActiveStage'   => 2,
                                'ConfirmedItem' => 1,
                                'IsClosed'      => NULL,
                                'LeadTimeItem'  => $leadtimeitem,
                                'PB'            => $PB,
                                'Setting'       => $Setting,
                                'Fullweld'      => $Fullweld,
                                'Primer'        => $Primer
                            ]);
                        }
                     }
                     if($update){
                         return redirect($links)->with('suc_message', 'PO Di ACC!');
                     }else{
                         return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                     }
                 }
                 elseif($request->action == "Update"){
                     $data = count($request->IDS);
                     for ($i = 0; $i < $data; $i++){
                             $update =  Pdi::where('ID', $request->IDS[$i])
                                 ->update(['ConfirmedDate' => Carbon::createFromFormat('d/m/Y', $request->ConfirmedDate[$i])->format('Y-m-d')]);
                     }
                     if($update || !$update){
                         return redirect($links)->with('suc_message', 'PO Di Update!');
                     }else{
                         return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                     }
                 }
                 else{
                     Notification::where('Number',$cekvendortype->Number)->where('Subjek','Vendor Negotiated PO')
                     ->update([
                         'is_read'=>3,
                      ]);

                     Notification::create([
                         'Number'         => $cekvendortype->Number,
                         'Subjek'         => "Cancel PO",
                         'user_by'=>Auth::user()->name,
                         'user_to'=> $cekvendortype->Vendor,
                         'is_read'=>1,
                         'menu'=>$links,
                         'comment'=>"Cancel PO $cekvendortype->Number",
                         'created_at'=>$date
                     ]);
                     $update =  Pdi::whereIn('ID', $request->ID)
                     ->update([
                         'ConfirmedItem' =>  0,
                         'IsClosed'      => 'C',
                     ]);
                 }
                 if($update){
                     return redirect($links)->with('suc_message', 'PO Di Cancel!');
                 }else{
                     return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                 }
             }
             else{
                 return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
             }
         }
         else{
             return redirect()->back()->with('error', 'Please Select Item !!');
         }
     }
}
