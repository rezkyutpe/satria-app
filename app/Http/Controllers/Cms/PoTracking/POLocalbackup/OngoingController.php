<?php

namespace App\Http\Controllers\Cms\PoTracking\POLocal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\ParkingInvoiceDocument;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\Ticket;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\ParkingInvoice;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\Table\PoTracking\DisabledDays;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwOngoingall;
use App\Models\View\PoTracking\VwQtytiket;
use App\Models\View\VwUserRoleGroup;
use Exception;
use Response;
use Illuminate\Support\Carbon;


class OngoingController extends Controller
{

    // polocal

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('polocalongoing') == 0 && $this->PermissionMenu('polocalongoing-nonmanagement') == 0 && $this->PermissionMenu('polocalongoing-proc') == 0 && $this->PermissionMenu('polocalongoing-vendor') == 0 && $this->PermissionMenu('polocalongoing-whs') == 0 && $this->PermissionMenu('historyparking') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    // PO Local Ongiong View Manageement
    public function polocalongoing()
    {
    if($this->PermissionActionMenu('polocalongoing')->r==1){
        $date   = Carbon::now();
        LogHistory::updateOrCreate([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Ongoing',
            'description' => 'Display Ongoing',
            'date'  => $date->toDateString(),
            'ponumber' => NULL,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Local',
            'CreatedBy'  => Auth::user()->name,
             ],
                ['time'     => $date->toTimeString()
            ]);
        $header_title                   = "PO LOCAL - ONGOING";
        $link_newPO                     = 'polocalnewpo';
        $link_ongoing                   = 'polocalongoing';
        $link_planDelivery              = 'polocalplandelivery';
        $link_readyToDelivery           = 'polocalreadydelivery';
        $link_historyPO                 = 'polocalhistory';

        $kodex = ['A','D'] ;
        $actionmenu =  $this->PermissionActionMenu('polocalongoing');
        $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
        $data  = VwOngoinglocal::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
        $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
        $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
        $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();

        $number_potracking = VwOngoinglocal::select('Number')->distinct()->get()->toArray();

    $old_number = MigrationPO::whereIn('ebeln',$number_potracking)->select('ebeln','submi')->distinct()->get();
    foreach($data as $a){
        foreach($old_number as $b){
            if($a->Number == $b->ebeln){
                $a->setAttribute('Number_old',$b->submi);
                break;
            }
            else{
                continue;
            }
        }
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
      // PO Local Ongiong View Non Management
      public function polocalongoingnonManagement()
      {
      if($this->PermissionActionMenu('polocalongoing-nonmanagement')->r==1){
          $date   = Carbon::now();
          LogHistory::updateOrCreate([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Ongoing',
            'description' => 'Display Ongoing',
            'date'  => $date->toDateString(),
            'ponumber' => NULL,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Local',
            'CreatedBy'  => Auth::user()->name,
             ],
                ['time'     => $date->toTimeString()
            ]);

          $header_title                   = "PO LOCAL - ONGOING";
          $link_newPO                     = 'polocalnewpo-nonmanagement';
          $link_ongoing                   = 'polocalongoing-nonmanagement';
          $link_planDelivery              = 'polocalplandelivery-nonmanagement';
          $link_readyToDelivery           = 'polocalreadydelivery-nonmanagement';
          $link_historyPO                 = 'polocalhistory-nonmanagement';

          $kodex = ['A','D'] ;
          $actionmenu =  $this->PermissionActionMenu('polocalongoing-nonmanagement');
          $NewpoPolocal = VwLocalnewpo::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
          $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
          $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
          $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();

          $number_potracking = VwOngoinglocal::select('Number')->distinct()->get()->toArray();
    $old_number = MigrationPO::whereIn('ebeln',$number_potracking)->select('ebeln','submi')->distinct()->get();
    foreach($data as $a){
        foreach($old_number as $b){
            if($a->Number == $b->ebeln){
                $a->setAttribute('Number_old',$b->submi);
                break;
            }
            else{
                continue;
            }
        }
    }

      $countNewpoPolocal       = $NewpoPolocal;
      $countOngoingPolocal     = $data->count();
      $countHistoryPolocal     = $HistoryPolocal;
      $countplanDelivery      = $planDelivery;
      $countreadyToDelivery    = $readyToDelivery;
          return view('po-tracking/polocal/polocalongoingnonmanagement',
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
        // PO Local Ongiong View Proc
    public function polocalongoingProc(Request $request)
    {
    if($this->PermissionActionMenu('polocalongoing-proc')->r==1){
        $date   = Carbon::now();
        LogHistory::updateOrCreate([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Ongoing',
            'description' => 'Display Ongoing',
            'date'  => $date->toDateString(),
            'ponumber' => NULL,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Local',
            'CreatedBy'  => Auth::user()->name,
             ],
                ['time'     => $date->toTimeString()
            ]);
        $header_title                   = "PO LOCAL - ONGOING";
        $link_newPO                     = 'polocalnewpo-proc';
        $link_ongoing                   = 'polocalongoing-proc';
        $link_planDelivery              = 'polocalplandelivery-proc';
        $link_readyToDelivery           = 'polocalreadydelivery-proc';
        $link_historyPO                 = 'polocalhistory-proc';
        $kodex = ['A','D'] ;
        $actionmenu =  $this->PermissionActionMenu('polocalongoing-proc');
        $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('NRP',Auth::user()->email)->count();
        if($request->Number){
            $data  = VwOngoinglocal::where('Number',$request->Number)->where('NRP',Auth::user()->email)->
             groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
        }else{
            $data  = VwOngoinglocal::where('NRP',Auth::user()->email)->
             groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
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

        $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('NRP',Auth::user()->email)->count();
        $number_potracking = VwOngoinglocal::select('Number')->distinct()->get()->toArray();

    $old_number = MigrationPO::whereIn('ebeln',$number_potracking)->select('ebeln','submi')->distinct()->get();
    foreach($data as $a){
        foreach($old_number as $b){
            if($a->Number == $b->ebeln){
                $a->setAttribute('Number_old',$b->submi);
                break;
            }
            else{
                continue;
            }
        }
    }

    $countNewpoPolocal       = $NewpoPolocal;
    $countOngoingPolocal     = $data->count();
    $countHistoryPolocal     = $HistoryPolocal;
    $countplanDelivery      = $planDelivery;
    $countreadyToDelivery    = $readyToDelivery;
        return view('po-tracking/polocal/polocalongoingproc',
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
      // PO Local Ongiong View Vendor
      public function polocalongoingVendor(Request $request)
      {
      if($this->PermissionActionMenu('polocalongoing-vendor')->r==1 ){
          $date   = Carbon::now();
          LogHistory::updateOrCreate([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Ongoing',
            'description' => 'Display Ongoing',
            'date'  => $date->toDateString(),
            'ponumber' => NULL,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Local',
            'CreatedBy'  => Auth::user()->name,
             ],
                ['time'     => $date->toTimeString()
            ]);

          $header_title                   = "PO LOCAL - ONGOING";
          $link_newPO                     = 'polocalnewpo-vendor';
          $link_ongoing                   = 'polocalongoing-vendor';
          $link_planDelivery              = 'polocalplandelivery-vendor';
          $link_readyToDelivery           = 'polocalreadydelivery-vendor';
          $link_historyPO                 = 'polocalhistory-vendor';

          $kodex = ['A','D'] ;
          $actionmenu =  $this->PermissionActionMenu('polocalongoing-vendor');

          $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->count();
          if($request->Number){
          $data  = VwOngoinglocal::where('Number',$request->Number)->where('VendorCode',Auth::user()->email)->
          groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
          }else{
          $data  = VwOngoinglocal::where('VendorCode',Auth::user()->email)->
          groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             }
 	      $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->count();
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
          $number_potracking = VwOngoinglocal::select('Number')->distinct()->get()->toArray();
          
    $old_number = MigrationPO::whereIn('ebeln',$number_potracking)->select('ebeln','submi')->distinct()->get();
    foreach($data as $a){
        foreach($old_number as $b){
            if($a->Number == $b->ebeln){
                $a->setAttribute('Number_old',$b->submi);
                break;
            }
            else{
                continue;
            }
        }
    }

      $countNewpoPolocal       = $NewpoPolocal;
      $countOngoingPolocal     = $data->count();
      $countHistoryPolocal     = $HistoryPolocal;
      $countplanDelivery      = $planDelivery;
      $countreadyToDelivery    = $readyToDelivery;
          return view('po-tracking/polocal/polocalongoingvendor',
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
      // PO Local Ongiong View Non Management
      public function polocalongoingWhs()
      {
      if($this->PermissionActionMenu('polocalongoing-whs')->r==1){
          $date   = Carbon::now();
          LogHistory::updateOrCreate([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Ongoing',
            'description' => 'Display Ongoing',
            'date'  => $date->toDateString(),
            'ponumber' => NULL,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Local',
            'CreatedBy'  => Auth::user()->name,
                ],
                ['time'     => $date->toTimeString()
            ]);
          $header_title                   = "PO LOCAL - ONGOING";
          $link_newPO                     = 'polocalnewpo-whs';
          $link_ongoing                   = 'polocalongoing-whs';
          $link_planDelivery              = 'polocalplandelivery-whs';
          $link_readyToDelivery           = 'polocalreadydelivery-whs';
          $link_historyPO                 = 'polocalhistory-whs';

          $kodex = ['A','D'] ;
          $actionmenu =  $this->PermissionActionMenu('polocalongoing-whs');
          $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
          $data  = VwOngoinglocal::groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
          $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
          $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
          $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();

          $number_potracking = VwOngoinglocal::select('Number')->distinct()->get()->toArray();

    $old_number = MigrationPO::whereIn('ebeln',$number_potracking)->select('ebeln','submi')->distinct()->get();
    foreach($data as $a){
        foreach($old_number as $b){
            if($a->Number == $b->ebeln){
                $a->setAttribute('Number_old',$b->submi);
                break;
            }
            else{
                continue;
            }
        }
    }

      $countNewpoPolocal       = $NewpoPolocal;
      $countOngoingPolocal     = $data->count();
      $countHistoryPolocal     = $HistoryPolocal;
      $countplanDelivery      = $planDelivery;
      $countreadyToDelivery    = $readyToDelivery;
          return view('po-tracking/polocal/polocalongoingnonmanagement',
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

    public function Historyparking()
    {
        if($this->PermissionActionMenu('historyparkinglocal')->r==1  ){

            $header_title                   = "PO LOCAL - HISTORY PARKING";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ongoing',
                'description' => 'Display History Parking',
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
            $actionmenu =  $this->PermissionActionMenu('historyparkinglocal');
            $data = ParkingInvoice::select('parkinginvoice.*','vw_ongoingall.Vendor','vw_ongoingall.VendorCode','vw_ongoingall.PurchaseOrderCreator','vw_ongoingall.ReleaseDate','vw_ongoingall.NRP')->
            leftjoin('vw_ongoingall', 'vw_ongoingall.Number', '=', 'parkinginvoice.Number')->groupBy('parkinginvoice.Number','parkinginvoice.InvoiceDate')
            ->get();
            $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
            $OngoingPolocal  =  VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->count();
            $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->where('status','P')->count();
            $HistoryPolocal = VwHistoryLocal::select('Number','ItemNumber')->distinct('Number','ItemNumber')->count();
            $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->distinct('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->count();
            $countNewpoPolocal       = $NewpoPolocal;
            $countOngoingPolocal     = $OngoingPolocal;
            $countHistoryPolocal     = $HistoryPolocal;
            $countplanDelivery      = $planDelivery;
            $countreadyToDelivery    = $readyToDelivery;

            return view('po-tracking/polocal/historyparking',
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
       //parking or approve parking
       public function Parkinginvoice(Request $request)
       {
        $appsmenu = VwOngoingall::where('Number', $request->Number)->first();
        if($appsmenu != null){
            if($this->PermissionActionMenu('polocalongoing')){
                $link = "polocalongoing";
            }else if($this->PermissionActionMenu('polocalongoing-vendor')){
                $link = "polocalongoing-vendor";
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
            $date   = Carbon::now();
            if($request->Update == "Approve Parking?"){

               $create1 = ParkingInvoice::where('Number', $request->Number)->where('ItemNumber', $request->ItemNumber)->where('Status', "Request Parking")
                ->update([
                    'Status'=>"Approve Parking",
                    'updated_at'=>$date
                ]);
                Notification::where('Number',$request->Number)->where('menu','Request Parking')
                        ->update([
                            'is_read'=>3,
                         ]);
                Notification::create([
                    'Number'         => $appsmenu->Number,
                    'Subjek'         => "Approve Parking",
                    'user_by'=>Auth::user()->name,
                    'user_to'=>$appsmenu->Vendor,
                    'is_read'=>1,
                    'menu'=>"historyparking",
                    'comment'=>"Approve Parking PO.No $appsmenu->Number",
                    'created_at'=>$date
                ]);
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Ongoing',
                    'description' => 'Approve Parking',
                    'date'  => $date->toDateString(),
                    'time'     => $date->toTimeString(),
                    'ponumber' =>  $appsmenu->Number,
                    'poitem' =>  $appsmenu->ItemNumber,
                    'userlogintype' => Auth::user()->title ,
                    'vendortype' => 'Local',
                    'CreatedBy'  => Auth::user()->name,
                ]);
                if($create1){
                    return redirect('historyparkinglocal')->with('suc_message', 'Parking Invoice Berhasil di Approve!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
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
                        'InvoiceDate'=>    Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d'),
                        'InvoiceNumber'=>   $request->invoice_no,
                        'Amount'=>          $request->amount,
                        'Assignment'=>      $request->Assignment,
                        'HeadertText'=>     $request->headertext,
                        'Status'=>          "Request Parking",
                        'created_by'=>          Auth::user()->name,
                        'Reference'=>       $request->reference
                    ];
                    $create1 = ParkingInvoice::insert($insert);

                }
                foreach ($request->file('filename') as $parkingdocument)
                {
                    if ($parkingdocument->isValid())

                    {
                        $parkingdocumentName =  Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d').'_'.str_replace(' ','_',$parkingdocument->getClientOriginalName());
                        if (!file_exists(public_path('potracking/parking_invoice/' . $request->Number))) {
                            mkdir(public_path('potracking/parking_invoice/' . $request->Number), 0777, true);
                        }
                        $parkingdocument->move(public_path('potracking/parking_invoice/'.$request->Number), $parkingdocumentName);
                    }
                    $insert = [
                        'Number'            => $request->Number,
                        'FileName'=>        $parkingdocumentName,
                        'CreatedBy'=>      Auth::user()->name
                    ];
                    $create1 = ParkingInvoiceDocument::insert($insert);
                }
                Notification::create([
                    'Number'         => $appsmenu->Number,
                    'Subjek'         => "Request Parking",
                    'user_by'=>Auth::user()->name,
                    'user_to'=>$appsmenu->NRP,
                    'is_read'=>1,
                    'menu'=>"historyparking",
                    'comment'=>"Request Parking PO.No $appsmenu->Number",
                    'created_at'=>$date
                ]);

                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->email,
                    'menu'  => 'PO Local Ongoing',
                    'description' => 'Request Parking',
                    'date'  => $date->toDateString(),
                    'time'     => $date->toTimeString(),
                    'ponumber' =>  $appsmenu->Number,
                    'poitem' =>  $appsmenu->ItemNumber,
                    'userlogintype' => Auth::user()->title ,
                    'vendortype' => 'Local',
                    'CreatedBy'  => Auth::user()->name,
                ]);

                  if($create1){
                      return redirect($link)->with('suc_message', 'Parking Invoice Berhasil!');
                  }else{
                      return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                  }
              }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }

       }

    // PO All- View cari proforma
    public function view_proforma(Request $request)
    {

        $dataid = VwOngoinglocal::where('ID', $request->id)->first();
        $data =  VwOngoinglocal::where('Number', $dataid->Number)->where('ParentID','!=','null')->groupBy('Number','ItemNumber','Quantity')->get();

        $data = array(
            'data'        => $data,
            'dataid'        => $dataid

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
        $dataid = VwOngoinglocal::select('ID','Number','ItemNumber','Material','Description','DeliveryDate','ConfirmedDate','ActiveStage','Quantity','ConfirmTicketDate')->where('vw_ongoinglocal.ID', $request->id)->first();

        $dataticket = VwOngoinglocal::where('Number', $dataid->Number)->groupBy('Number','ItemNumber')->get();
        // if($dataid->ConfirmedItem == 1){
        //     $dataall = VwOngoinglocal::where('Number', $dataid->Number)->get();
        // }else{
            $dataall = VwOngoinglocal::where('Number', $dataid->Number)->groupBy('Number','ItemNumber')->get();
        // }
            $days_disabled = DisabledDays::select('event_date')->where('is_active',1)->where('is_disabled',1)->get();
        $data = array(
            'dataid' =>$dataid,
            'dataticket' =>$dataticket,
            'dataall' =>$dataall,
            'days_disabled'=>$days_disabled
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
        if($this->PermissionActionMenu('polocalongoing')){
            $links = "polocalongoing";
        }else if($this->PermissionActionMenu('polocalongoing-proc')){
            $links = "polocalongoing-proc";
        }else if($this->PermissionActionMenu('polocalongoing-vendor')){
            $links = "polocalongoing-vendor";
        }else{
            return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }

         //ProsesConfirmPo
        if(!empty($request->ID)||!empty($request->Number)){
            $date   = Carbon::now();
            $datapo = VwOngoinglocal::where('Number', $request->Number)->first();
        if($request->ActiveStage== '2'){
            $appsmenu = VwOngoinglocal::where('Number', $request->Number)->get();
            if(!empty($appsmenu)){
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
                        'menu'=>"polocalongoing-vendor",
                        'comment'=>"Request And Uploud Proforma PO $datapo->Number",
                        'created_at'=>$date
                    ]);
                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ongoing',
                        'description' => 'Uploud Proforma',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
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
                        'menu'=>"polocalongoing-vendor",
                        'comment'=>"PO NO.$datapo->Number Approve next Create Ticket",
                        'created_at'=>$date
                    ]);
                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ongoing',
                        'description' => 'Skip Proforma',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                }
                if($create ){
                    return redirect($links)->with('suc_message', 'PO Proforma Approve!');
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
                        'menu'=>"polocalongoing-vendor",
                        'comment'=>"PO NO.$datapo->Number Approve next Create Ticket",
                        'created_at'=>$date
                    ]);
                    LogHistory::updateOrCreate([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ongoing',
                        'description' => 'Approve Proforma PO',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' => $datapo->Number,
                        'poitem' => $datapo->ItemNumber,
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                    foreach ($appsmenu as $p) {
                        $id[] = $p->ID;
                    }
                    $create = Pdi::whereIn('ID',$id)->update(['ActiveStage'=>4,'ApproveProformaInvoiceDocument' => $nama_file]);
                    if($create){
                        return redirect($links)->with('suc_message', 'Proforma disetujui!');
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
                            'menu'=>"polocalongoing-vendor",
                            'comment'=>"Revisi Proforma PO NO.$datapo->Number",
                            'created_at'=>$date
                        ]);
                        LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Local Ongoing',
                            'description' => 'Revisi Proforma PO',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $datapo->Number,
                            'poitem' => $datapo->ItemNumber,
                            'userlogintype' => Auth::user()->title ,
                            'vendortype' => 'Local',
                            'CreatedBy'  => Auth::user()->name,
                        ]);
                    if($create){
                        return redirect($links)->with('suc_message', 'Proforma harus direvisi!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        //ProsesTicket
        }
        else if($request->ActiveStage== '4' || $request->ActiveStage== '1'){

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
                            'Material'         => (!is_null($request->material[$i]) ? $request->material[$i] : "NULL"),
                            'Description'         => $request->description[$i],
                            'ConfirmDeliveryDate'  => Carbon::createFromFormat('d/m/Y', $request->deliverydatesap)->format('Y-m-d'),
                            'DeliveryDate'  => Carbon::createFromFormat('d/m/Y', $request->deliverydate)->format('Y-m-d').' '.$request->deliverytime,
                            'TicketID'      => $idticket,
                            'headertext'    => $request->headertext,
                            'DeliveryNote'  => $request->DeliveryNote,
                            'status'        => 'P',
                            'CreatedBy'        => Auth::user()->name,
                            'QtySAP'         => $request->qtysap[$i],
                            'Quantity'      => $request->QtyDelivery[$request->ID[$i]][0],
                            'SPBDate'       => $spbdate
                        ];
                        $create2 = DetailTicket::insert($newinsert);
                    }

                    Notification::where('Number',$request->numbers)->whereIn('Subjek',['Cancel Ticket Local','Approve PO'])
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
                               'Subjek'         => "Ticket Local",
                               'user_by'=>$appsmenu->Vendor,
                               'user_to'=>$datarole[$i],
                               'menu'=>'polocalplandelivery-whs',
                               'is_read'=>1,
                               'comment'=>"$idticket",
                               'created_at'=>$date
                           ]);

                           }
                           LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Local Ongoing',
                            'description' => 'Create Ticket',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $appsmenu->Number,
                            'poitem' => $appsmenu->ItemNumber,
                            'userlogintype' => Auth::user()->title ,
                            'vendortype' => 'Local',
                            'CreatedBy'  => Auth::user()->name,
                             ]);

                if($create1 && $create2){
                    return redirect($links)->with('suc_message', 'Ticket Berhasil Dibuat !');
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
                        Notification::where('Number',$datapo->Number)->whereIn('Subjek',['Proforma','Revisi Proforma'])
                        ->update([
                            'is_read'=>3,
                         ]);
                        Notification::create([
                            'Number'         => $datapo->Number,
                            'Subjek'         => "Request Proforma",
                            'user_by'=>Auth::user()->name,
                            'user_to'=> $datapo->NRP,
                            'is_read'=>1,
                            'menu'=>"polocalongoing-proc",
                            'comment'=>"Proforma PO NO.$datapo->Number",
                            'created_at'=>$date
                        ]);
                        LogHistory::create([
                            'user'  => Auth::user()->email,
                            'menu'  => 'PO Local Ongoing',
                            'description' => 'Request Proforma',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' => $datapo->Number,
                            'poitem' => $datapo->ItemNumber,
                            'userlogintype' => Auth::user()->title ,
                            'vendortype' => 'Local',
                            'CreatedBy'  => Auth::user()->name,
                         ]);
                if($create){
                    return redirect($links)->with('suc_message', 'Proforma Berhasi Disimpan!');
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
    public function SkipProforma(Request $request)
    {

        if(!empty($request->Number)){

            if($this->PermissionActionMenu('polocalongoing')){
                $links = "polocalongoing";
            }else if($this->PermissionActionMenu('polocalongoing-proc')){
                $links = "polocalongoing-proc";
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
                    $date   = Carbon::now();

                    $appsmenu = VwOngoinglocal::whereIn('Number', $request->Number)->groupBy('Number')->get();
                    foreach( $appsmenu as $q){
                        $number[] = $q->Number ;
                        $itemnumber[] = $q->ItemNumber ;
                        $poid[] = $q->POID ;
                    }
                    $totalpo = count($appsmenu);
                    for ($i = 0; $i < $totalpo; $i++){
                    LogHistory::create([
                        'user'  => Auth::user()->email,
                        'menu'  => 'PO Local Ongoing',
                        'description' => 'Skip Proforma',
                        'date'  => $date->toDateString(),
                        'time'     => $date->toTimeString(),
                        'ponumber' =>  $number[$i],
                        'poitem' => $itemnumber[$i],
                        'userlogintype' => Auth::user()->title ,
                        'vendortype' => 'Local',
                        'CreatedBy'  => Auth::user()->name,
                    ]);
                    }
                    $create = Pdi::whereIn('POID',$poid)->where('ActiveStage','2')
                        ->update([
                            'ActiveStage'=>4,
                        ]);

                    if($create){
                        return redirect($links)->with('suc_message', 'Success Skip Proforma!');
                    }else{
                        return redirect()->back()->with('err_message', 'Proforma gagal diskip!');
                    }
        }
        else{
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
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
     //ApproveParking
     public function UpdateData(Request $request)
     {

        if($this->PermissionActionMenu('polocalongoing')->u==1){

             $update = Pdi::where('ID',$request->ID)
                 ->update([
                     'ConfirmedQuantity'=>$request->qty,
                     'LastModifiedBy'=>'Update Data Ongoing',
                 ]);
                 if($update){
                     return redirect('polocalongoing')->with('suc_message', 'Update Data Success!');
                 }else{
                     return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                 }

         }else{
             return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
         }
     }





}
