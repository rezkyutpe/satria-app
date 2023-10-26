<?php

namespace App\Http\Controllers\Cms\PoTracking\POLocal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;;
use App\Models\Table\PoTracking\Notification;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwPoLocalOngoing;
use PDF;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReadyDeliveryController extends Controller
{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('polocalreadydelivery') == 0 && $this->PermissionMenu('polocalreadydelivery-nonmanagement') == 0 && $this->PermissionMenu('polocalreadydelivery-proc') == 0 && $this->PermissionMenu('polocalreadydelivery-vendor') == 0 && $this->PermissionMenu('polocalreadydelivery-whs') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }


    //ReadyDelivery
    public function readyDelivery()
    {
        if($this->PermissionActionMenu('polocalreadydelivery')->r==1   ){

            $header_title                   = "PO LOCAL - READY TO DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ready TO Delivery',
                'description' => 'Display Ready TO Delivery',
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

            $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery');
            $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
            $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
            leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
            leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
            leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
            ->whereIn('detailticketingdelivery.status',$kodex)
            ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $data->count();

            $datafinishlocal = VwPoLocalOngoing::all();

            return view('po-tracking/polocal/readytodelivery',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
     //ReadyDeliveryProc
     public function readyDeliveryNonManagement()
     {
         if($this->PermissionActionMenu('polocalreadydelivery-nonmanagement')->r==1   ){

             $header_title                   = "PO LOCAL - READY TO DELIVERY";
             $date   = Carbon::now();
             LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ready TO Delivery',
                'description' => 'Display Ready TO Delivery',
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

             $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-nonmanagement');
             $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->get();
             $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
             $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
             leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
             leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
             leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
             ->whereIn('detailticketingdelivery.status',$kodex)
             ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
             $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber')->get();

             $countNewpoPolocal       = $NewpoPolocal->count();
             $countOngoingPolocal     = $OngoingPolocal->count();
             $countHistoryPolocal     = $HistoryPolocal->count();
             $countplanDelivery      = $planDelivery->count();
             $countreadyToDelivery    = $data->count();

             $datafinishlocal = VwPoLocalOngoing::all();

             return view('po-tracking/polocal/readytodelivery',
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
             ));
         }else{
             return redirect('/')->with('err_message', 'Akses Ditolak!');
         }
     }
     //ReadyDeliveryProc
     public function readyDeliveryProc()
     {
         if($this->PermissionActionMenu('polocalreadydelivery-proc')->r==1   ){

             $header_title                   = "PO LOCAL - READY TO DELIVERY";
             $date   = Carbon::now();
             LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ready TO Delivery',
                'description' => 'Display Ready TO Delivery',
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

             $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-proc');
             $NewpoPolocal = VwLocalnewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->where('NRP',Auth::user()->email)->distinct('POID','ItemNumber')->get();
             $planDelivery = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
             leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
             leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
             {
                 $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                 ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
             })
             ->where('po.CreatedBy',Auth::user()->email)
             ->where('detailticketingdelivery.status','P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
              $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
                leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
                leftjoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                ->where('po.CreatedBy',Auth::user()->email)->whereIn('detailticketingdelivery.status',$kodex)
                ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
             $HistoryPolocal          = VwHistoryLocal::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

             $countNewpoPolocal       = $NewpoPolocal->count();
             $countOngoingPolocal     = $OngoingPolocal->count();
             $countHistoryPolocal     = $HistoryPolocal->count();
             $countplanDelivery      = $planDelivery->count();
             $countreadyToDelivery    = $data->count();

             $datafinishlocal = VwPoLocalOngoing::all();

             return view('po-tracking/polocal/readytodelivery',
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
             ));
         }else{
             return redirect('/')->with('err_message', 'Akses Ditolak!');
         }
     }
      //ReadyDeliveryVendor
    public function readyDeliveryVendor(Request $request)
    {
        if($this->PermissionActionMenu('polocalreadydelivery-vendor')->r==1   ){

            $header_title                   = "PO LOCAL - READY TO DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Local Ready TO Delivery',
                'description' => 'Display Ready TO Delivery',
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

            $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-vendor');
            $NewpoPolocal = VwLocalnewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->where('VendorCode',Auth::user()->email)->distinct('POID','ItemNumber')->get();
            $planDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->
            leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
            leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
            {
                $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
            })
            ->where('uservendors.VendorCode',Auth::user()->email)
            ->where('detailticketingdelivery.status','P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

              if($request->Number){
                $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
                leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
                leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode',Auth::user()->email)->where('detailticketingdelivery.Number',$request->Number)
                ->whereIn('detailticketingdelivery.status',$kodex)
                ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            }else{
                $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
                leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
                leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
                    ->where('uservendors.VendorCode',Auth::user()->email)->whereIn('detailticketingdelivery.status',$kodex)
                ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            }
            $HistoryPolocal          = VwHistoryLocal::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $data->count();

            $datafinishlocal = VwPoLocalOngoing::all();

            return view('po-tracking/polocal/readytodelivery',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    //ReadyDeliveryProc
    public function readyDeliveryWhs()
    {
        if($this->PermissionActionMenu('polocalreadydelivery-whs')->r==1   ){

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
               'userlogintype' => Auth::user()->title ,
               'vendortype' => 'Local',
               'CreatedBy'  => Auth::user()->name,
             ]);

            $link_newPO                     = 'polocalnewpo-whs';
            $link_ongoing                   = 'polocalongoing-whs';
            $link_planDelivery              = 'polocalplandelivery-whs';
            $link_readyToDelivery           = 'polocalreadydelivery-whs';
            $link_historyPO                 = 'polocalhistory-whs';
            $kodex = ['A','D'] ;

            $actionmenu =  $this->PermissionActionMenu('polocalreadydelivery-whs');
            $NewpoPolocal = VwLocalnewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $OngoingPolocal  = VwOngoinglocal::select('POID','ItemNumber')->distinct('POID','ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
            $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
               leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
               leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
               leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
               ->whereIn('detailticketingdelivery.status',$kodex)
               ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistoryPolocal          = VwHistoryLocal::groupBy('Number', 'ItemNumber')->get();

            $countNewpoPolocal       = $NewpoPolocal->count();
            $countOngoingPolocal     = $OngoingPolocal->count();
            $countHistoryPolocal     = $HistoryPolocal->count();
            $countplanDelivery      = $planDelivery->count();
            $countreadyToDelivery    = $data->count();

            $datafinishlocal = VwPoLocalOngoing::all();

            return view('po-tracking/polocal/readytodelivery',
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
            ));
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
     //ProsesUpdate
    public function ProsesUpdate(Request $request)
    {
        if($this->PermissionActionMenu('polocalreadydelivery-whs')){
                $link = "polocalreadydelivery-whs";
            }else if($this->PermissionActionMenu('polocalreadydelivery')){
                $link = "polocalreadydelivery";
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
        }
        if($request->status == "Update"){
            $status = "D";
            $tanggal = "DeliveryDate";
        }else{
            $status = "C";
            $tanggal = "ConfirmTicketDate";
        }
        $date   = Carbon::now();
        $dataviewticket = DetailTicket::where('TicketID', $request->ID)->first();

        LogHistory::create([
           'user'  => Auth::user()->email,
           'menu'  => 'PO Local Ready TO Delivery',
           'description' => 'Update Ticket',
           'date'  => $date->toDateString(),
           'time'     => $date->toTimeString(),
           'ponumber' => $dataviewticket->Number,
           'poitem' => NULL,
           'userlogintype' => Auth::user()->title ,
           'vendortype' => 'Local',
           'CreatedBy'  => Auth::user()->name,
         ]);

         $update = DetailTicket::where('TicketID',$request->ID)->whereIn('status',['D','A','S'])
         ->update([
             'remarks'=>$request->remarks,
             'status'=>$status,
             $tanggal=> Carbon::createFromFormat('d/m/Y', $request->ConfirmTicketDate)->format('Y-m-d'),

         ]);

        if($update){
            return redirect($link)->with('suc_message', 'Ticket Succes !');
        }else{
            return redirect()->back()->with('err_message', 'Data gagal di Proses!');
        }

    }
     //DownloadTIcket
     public function TicketPdf(Request $request)
     {

        $data = VwViewTicket::where('TicketID', $request->TicketID)->whereIn('status',['D','A'])->WhereNotIn('status' ,['C'])->groupBy('ID','ItemNumber')->orderBy('ID', 'asc')->get();
         foreach($data as $a){
             $id_tiket[] = $a->IDTicket;
         }

         if(!empty($data)){
            DetailTicket::whereIn('ID',$id_tiket)
             ->update([
                 'status'=>'D',
             ]);
             $date   = Carbon::now();

         $dataviewticket = VwViewTicket::where('TicketID', $request->TicketID)->first();
         LogHistory::create([
            'user'  => Auth::user()->email,
            'menu'  => 'PO Local Ready TO Delivery',
            'description' => 'Download Ticket',
            'date'  => $date->toDateString(),
            'time'     => $date->toTimeString(),
            'ponumber' => $dataviewticket->Number,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Local',
            'CreatedBy'  => Auth::user()->name,
          ]);
         Notification::where('Number',$dataviewticket->Number)->where('Subjek','Approve Ticket Local')
         ->update([
             'is_read'=>3,
          ]);
         $deliverydate = date('d/m/Y', strtotime($dataviewticket->DeliveryDate));
         $deliverytime = date('H:i:s', strtotime($dataviewticket->DeliveryDate));

             $dataallviewticket = VwViewTicket::where('TicketID', $request->TicketID)->select('ItemNumber','Material','Description','DeliveryDates','Quantity')->get()->toArray();
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
                         $dataviewticket->VendorCode_new.'|'.
                         $dataviewticket->DeliveryNote.'|'.
                         $dataviewticket->headertextgr.'|'.
                         $deliverydate.'|'.$deliverytime
                         ;
            if(strlen($dataallviewticket1) > 1000){
                $dataallviewticket1 = substr($dataallviewticket1,0,500).'...';
            }
            
             $qrcode         = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($qrstr.'||'.$dataallviewticket1.'||'.$spbdatenya.'|'));

             $data = array(
                 'data'              => $data,
                 'dataviewticket'    => $dataviewticket,
                 'qrcode'            => $qrcode,
             );

             $pdf = PDF::loadView('po-tracking/polocal/ticket', $data);

         // return view('po-non-sap/po-local/ticketpdf')->with('data', $data);
         return $pdf->stream();
         }else{
             return redirect()->back()->with('err_message', 'Data Tidak Ditemukan');
         }

     }


}
