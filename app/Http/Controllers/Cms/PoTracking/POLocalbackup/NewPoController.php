<?php

namespace App\Http\Controllers\Cms\PoTracking\POLocal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\Po;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwOngoingAll;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use App\Models\View\PoTracking\VwQtytiket;
use Illuminate\Support\Carbon;

class NewPoController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('polocalnewpo') == 0 && $this->PermissionMenu('polocalnewpo-nonmanagement') == 0 && $this->PermissionMenu('polocalnewpo-proc') == 0 && $this->PermissionMenu('polocalnewpo-whs') == 0 && $this->PermissionMenu('polocalnewpo-vendor') == 0) {

		 return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //Po Local New PO View Management
    public function polocalnewpo()
    {

        if( $this->PermissionActionMenu('polocalnewpo')->r==1){
            $header_title                   = "PO LOCAL - New PO";
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local New Po',
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

                $link_newPO                     = 'polocalnewpo';
                $link_ongoing                   = 'polocalongoing';
                $link_planDelivery              = 'polocalplandelivery';
                $link_readyToDelivery           = 'polocalreadydelivery';
                $link_historyPO                 = 'polocalhistory';

            $kodex = ['A','D'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');

                $data = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();

            $countNewpoPolocal      = $data->count();
            $countOngoingPolocal    = $OngoingPolocal;
            $countHistoryPolocal    = $HistoryPolocal;
            $countplanDelivery      = $planDelivery;
            $countreadyToDelivery   = $readyToDelivery;
            return view('po-tracking/polocal/polocalnewpo',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }
    //Po Local New PO View NonManagement
    public function polocalnewpoNonManagement()
    {
        if( $this->PermissionActionMenu('polocalnewpo-nonmanagement')->r==1){
            $header_title                   = "PO LOCAL - New PO";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local New Po',
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

                $link_newPO                     = 'polocalnewpo-nonmanagement';
                $link_ongoing                   = 'polocalongoing-nonmanagement';
                $link_planDelivery              = 'polocalplandelivery-nonmanagement';
                $link_readyToDelivery           = 'polocalreadydelivery-nonmanagement';
                $link_historyPO                 = 'polocalhistory-nonmanagement';

            $kodex = ['A','D'] ;
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo-nonmanagement');

                $data = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();


            $countNewpoPolocal      = $data->count();
            $countOngoingPolocal    = $OngoingPolocal;
            $countHistoryPolocal    = $HistoryPolocal;
            $countplanDelivery      = $planDelivery;
            $countreadyToDelivery   = $readyToDelivery;
            return view('po-tracking/polocal/polocalnewpononmanagement',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }

    }
      //Po Local New PO View Proc
      public function polocalnewpoProc(Request $request)
      {
          if( $this->PermissionActionMenu('polocalnewpo-proc')->r==1){
              $header_title                   = "PO LOCAL - New PO";
              $date   = Carbon::now();
              LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local New Po',
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

                  $link_newPO                     = 'polocalnewpo-proc';
                  $link_ongoing                   = 'polocalongoing-proc';
                  $link_planDelivery              = 'polocalplandelivery-proc';
                  $link_readyToDelivery           = 'polocalreadydelivery-proc';
                  $link_historyPO                 = 'polocalhistory-proc';

              $kodex = ['A','D'] ;
              $actionmenu =  $this->PermissionActionMenu('polocalnewpo-proc');
                if($request->Number){
                    $data = VwLocalnewpo::where('Number',$request->Number)->where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                }else{
                    $data = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                }
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

                  $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->where('NRP',Auth::user()->email)->distinct('POID','ItemNumber')->count();
                  $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->where('NRP',Auth::user()->email)->distinct('Number','ItemNumber')->count();

              $countNewpoPolocal      = $data->count();
              $countOngoingPolocal    = $OngoingPolocal;
              $countHistoryPolocal    = $HistoryPolocal;
              $countplanDelivery      = $planDelivery;
              $countreadyToDelivery   = $readyToDelivery;
              return view('po-tracking/polocal/polocalnewpoproc',
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
              ));
          }else{
              return redirect('/')->with('err_message', 'Akses Ditolak!');
          }

      }
      //Po Local New PO View Vendor
      public function polocalnewpoVendor()
      {
          if( $this->PermissionActionMenu('polocalnewpo-vendor')->r==1){
              $header_title                   = "PO LOCAL - New PO";
              $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local New Po',
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

                  $link_newPO                     = 'polocalnewpo-vendor';
                  $link_ongoing                   = 'polocalongoing-vendor';
                  $link_planDelivery              = 'polocalplandelivery-vendor';
                  $link_readyToDelivery           = 'polocalreadydelivery-vendor';
                  $link_historyPO                 = 'polocalhistory-vendor';

              $kodex = ['A','D'] ;
              $actionmenu =  $this->PermissionActionMenu('polocalnewpo-vendor');
              $data = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
              $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->where('VendorCode',Auth::user()->email)->distinct('POID','ItemNumber')->count();
              $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->distinct('Number','ItemNumber')->count();
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

              $countNewpoPolocal      = $data->count();
              $countOngoingPolocal    = $OngoingPolocal;
              $countHistoryPolocal    = $HistoryPolocal;
              $countplanDelivery      = $planDelivery;
              $countreadyToDelivery   = $readyToDelivery;
              return view('po-tracking/polocal/polocalnewpovendor',
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
              ));
          }else{
              return redirect('/')->with('err_message', 'Akses Ditolak!');
          }

      }
        //Po Local New PO View NonManagement
        public function polocalnewpoWhs()
        {
            if( $this->PermissionActionMenu('polocalnewpo-whs')->r==1){
                $header_title                   = "PO LOCAL - New PO";
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local New Po',
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

                    $link_newPO                     = 'polocalnewpo-whs';
                    $link_ongoing                   = 'polocalongoing-whs';
                    $link_planDelivery              = 'polocalplandelivery-whs';
                    $link_readyToDelivery           = 'polocalreadydelivery-whs';
                    $link_historyPO                 = 'polocalhistory-whs';

                $kodex = ['A','D'] ;
                $actionmenu =  $this->PermissionActionMenu('polocalnewpo-whs');

                    $data = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
                    $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
                    $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
                    $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
                    $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();


                $countNewpoPolocal      = $data->count();
                $countOngoingPolocal    = $OngoingPolocal;
                $countHistoryPolocal    = $HistoryPolocal;
                $countplanDelivery      = $planDelivery;
                $countreadyToDelivery   = $readyToDelivery;
                return view('po-tracking/polocal/polocalnewpononmanagement',
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
                ));
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }

        }

     // New PO Insert
     public function poInsert(Request $request)
     {
        if($this->PermissionActionMenu('polocalnewpo-vendor')){
            $links = "polocalnewpo-vendor";
        }else if($this->PermissionActionMenu('polocalnewpo')){
            $links = "polocalnewpo";
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }
         if($request->ID == null){
             return redirect()->back()->with('err_message', 'Please Select Item!');
         }else{
             $varmain = Pdi::where('ID', $request->ID[0])->first();
             $varCountItemNumber = VwLocalnewpo::select('ItemNumber')->where('POID',$varmain->POID)->distinct()->count();

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
                 $checkvendortype = VwLocalnewpo::where('ID', $request->ID[0])->first();
                 $date   = Carbon::now();
                 if($request->action== "Save"){
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

                                 $checkpo    = VwOngoinglocal::select('ActiveStage')->where('POID', $appsmenu->POID)->whereNotNull('ActiveStage')->first();
                                 $date1      = Carbon::createFromFormat('Y-m-d', $appsmenu->DeliveryDate)->format('Y-m-d');
                                 $date2      = Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d');

                                 if($date1 >= $date2 && $eachitem['CountQty'] == 1 && $eachitem['TotalQuantity'] == $appsmenu->Quantity){
                                     if(isset($checkpo)){
                                         $confirmed = '1';
                                         $active = $checkpo->ActiveStage;
                                             $leadtimeitem   = NULL;
                                             $PB             = NULL;
                                             $Setting        = NULL;
                                             $Fullweld       = NULL;
                                             $Primer         = NULL;
                                     }
                                     else{
                                         $confirmed = '1';
                                         $active = "2";
                                             $leadtimeitem   = NULL;
                                             $PB             = NULL;
                                             $Setting        = NULL;
                                             $Fullweld       = NULL;
                                             $Primer         = NULL;
                                     }
                                    LogHistory::updateOrCreate([
                                        'user'  => Auth::user()->email,
                                        'menu'  => 'PO Local New Po',
                                        'description' => 'Vendor Confirm PO',
                                        'date'  => $date->toDateString(),
                                        'ponumber' => $checkvendortype->Number,
                                        'poitem' => $checkvendortype->ItemNumber,
                                        'userlogintype' => Auth::user()->title ,
                                        'vendortype' => 'Local',
                                        'CreatedBy'  => Auth::user()->name,
                                     ],
                                        [ 'time'     => $date->toTimeString()
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

                                     Notification::updateOrCreate([
                                        'Number'         => $checkvendortype->Number,
                                        'Subjek'         => "Vendor Negotiated PO",
                                        'user_by'=>Auth::user()->name,
                                        'user_to'=>$checkvendortype->NRP,
                                        'created_at'=>$date,
                                        'is_read'=>1,
                                        'menu'=>"polocalnewpo-proc",
                                        'comment'=>"New PO $checkvendortype->Number",
                                      ],
                                       ['is_read'=>1,
                                       'created_at'=>$date
                                       ]);

                                    LogHistory::updateOrCreate([
                                        'user'  => Auth::user()->email,
                                        'menu'  => 'PO Local New Po',
                                        'description' => 'Negotiated New PO',
                                        'ponumber' => $checkvendortype->Number,
                                        'poitem' => NULL,
                                        'userlogintype' => Auth::user()->title ,
                                        'vendortype' => 'Local',
                                        'CreatedBy'  => Auth::user()->name,
                                    ],
                                    [
                                        'date'  => $date->toDateString(),
                                        'time' => $date->toTimeString()
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

                    LogHistory::updateOrCreate([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local New Po',
                        'description' => 'Cancel PO',
                        'ponumber' => $checkvendortype->Number,
                        'poitem' => $checkvendortype->ItemNumber,
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'date'  => $date->toDateString(),
                        'time' => $date->toTimeString()
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
         $dataid = VwLocalnewpo::where('POID', $request->number)->where('ItemNumber', $request->item)->first();
         $data = VwLocalnewpo::where('POID', $request->number)->where('ActiveStage',1)->distinct()->orderBy('ItemNumber','Asc')->get();

         if($this->PermissionActionMenu('polocalnewpo-vendor')){
            $status =  1;
        }else if($this->PermissionActionMenu('polocalnewpo')){
            $status =  1;
        }else if($this->PermissionActionMenu('polocalnewpo-proc')){
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

        if($this->PermissionActionMenu('polocalnewpo-vendor')){
            $links = "polocalnewpo-vendor";
        }else if($this->PermissionActionMenu('polocalnewpo')){
            $links = "polocalnewpo";
        }else if($this->PermissionActionMenu('polocalnewpo-proc')){
            $links = "polocalnewpo-proc";
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }

         if(!empty($request->ID)){
             $appsmenu = Pdi::whereIn('ID', $request->ID)->get();

             if(!empty($appsmenu)){

                 $date   = Carbon::now();
                 $po= Pdi::where('ID', $request->ID)->first();
                 $cekvendortype = Po::select('Number')->where('ID', $po->POID)->first();

                 if($request->action == "Yes"){
                    Notification::where('Number',$cekvendortype->Number)->where('Subjek','Vendor Negotiated PO')
                    ->update([
                        'is_read'=>3,
                     ]);
                        LogHistory::updateOrCreate([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Local New Po',
                            'description' => 'Approve Negotiated PO',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $cekvendortype->Number,
                            'poitem' => NULL,
                            'userlogintype' => Auth::user()->title ,
                            'vendortype' => 'Local',
                            'CreatedBy'  => Auth::user()->name,
                        ]);
                     $cekongoing = VwOngoinglocal::select('ActiveStage')->where('POID', $po->POID)->whereNotNull('ActiveStage')->where('ActiveStage','!=','1')->first();

                     if(isset($cekongoing)) {
                        
                             $leadtimeitem   = NULL;
                             $PB             = NULL;
                             $Setting        = NULL;
                             $Fullweld       = NULL;
                             $Primer         = NULL;
                             $update = Pdi::whereIn('ID', $request->ID)
                             ->update([
                                 'ActiveStage'   => $cekongoing->ActiveStage,
                                 'ConfirmedItem' => '1',
                                 'IsClosed'      => NULL,
                                 'LeadTimeItem'  => $leadtimeitem,
                                 'PB'            => $PB,
                                 'Setting'       => $Setting,
                                 'Fullweld'      => $Fullweld,
                                 'Primer'        => $Primer
                             ]);
                     }
                     else{

                             $leadtimeitem   = NULL;
                             $PB             = NULL;
                             $Setting        = NULL;
                             $Fullweld       = NULL;
                             $Primer         = NULL;

                             $update =  Pdi::whereIn('ID', $request->ID)
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
                     LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local New Po',
                        'description' => 'Update ConfirmedDate PO',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $cekvendortype->Number,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
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

                     LogHistory::create([
                         'user'  => Auth::user()->email,
                         'menu'  => 'PO Local New Po',
                         'description' => 'PO Negotiated Cancel',
                         'date'  => $date->toDateString(),
                         'time'     => $date->toTimeString(),
                         'ponumber' => $cekvendortype->Number,
                         'poitem' => NULL,
                         'userlogintype' => Auth::user()->title ,
                         'vendortype' => 'Local',
                         'CreatedBy'  => Auth::user()->name,
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
