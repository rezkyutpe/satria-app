<?php

namespace App\Http\Controllers\Cms\PoTracking\POSubcontractor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\DetailTicket;;
use App\Models\Table\PoTracking\Notification;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwViewTicket;
use PDF;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReadyDeliveryController extends Controller
{

    // subcontractor

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('subcontractorreadydelivery') == 0  && $this->PermissionMenu('subcontractorreadydelivery-proc') == 0  && $this->PermissionMenu('subcontractorreadydelivery-nonmanagement') == 0 && $this->PermissionMenu('subcontractorreadydelivery-vendor') == 0 && $this->PermissionMenu('subcontractorreadydelivery-whs') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }


    //ReadyDelivery
    public function readyDelivery()
    {
        if($this->PermissionActionMenu('subcontractorreadydelivery')->r==1   ){

            $header_title                   = "PO SUBCONT - READY TO DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont Ready TO Delivery',
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

            $link_newPO                     = 'subcontractornewpo';
            $link_ongoing                   = 'subcontractorongoing';
            $link_planDelivery              = 'subcontractorplandelivery';
            $link_readyToDelivery           = 'subcontractorreadydelivery';
            $link_historyPO                 = 'subcontractorhistory';

            $kodex = ['A','D'] ;

            $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery');
            $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
            $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.VendorCode','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
               leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
               leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
               leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
               ->whereIn('detailticketingdelivery.status',$kodex)
               ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $HistorySubcont          = VwSubcontHistory::select('Number','ItemNumber')->groupBy('Number', 'ItemNumber')->get();

            $countNewpoSubcont       = $NewpoSubcont->count();
            $countOngoingSubcont     = $OngoingSubcont->count();
            $countHistorySubcont     = $HistorySubcont->count();
            $countPlanDeliverySubcont      = $planDelivery->count();
            $countReadyToDeliverySubcont    = $data->count();

            return view('po-tracking/subcontractor/readytodelivery',
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
     //ReadyDeliveryProc
     public function readyDeliveryNonManagement()
     {
         if($this->PermissionActionMenu('subcontractorreadydelivery-nonmanagement')->r==1){

             $header_title                   = "PO SUBCONT - READY TO DELIVERY";
             $date   = Carbon::now();
             LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont Ready TO Delivery',
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

             $link_newPO                     = 'subcontractornewpo-nonmanagement';
             $link_ongoing                   = 'subcontractorongoing-nonmanagement';
             $link_planDelivery              = 'subcontractorplandelivery-nonmanagement';
             $link_readyToDelivery           = 'subcontractorreadydelivery-nonmanagement';
             $link_historyPO                 = 'subcontractorhistory-nonmanagement';
             $kodex = ['A','D'] ;

             $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-nonmanagement');
             $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->
                 groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
             $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.VendorCode','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
               leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
               leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
               leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
               ->whereIn('detailticketingdelivery.status',$kodex)
               ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
             $HistorySubcont          = VwSubcontHistory::select('Number','ItemNumber')->groupBy('Number', 'ItemNumber')->get();

             $countNewpoSubcont       = $NewpoSubcont->count();
             $countOngoingSubcont     = $OngoingSubcont->count();
             $countHistorySubcont     = $HistorySubcont->count();
             $countPlanDeliverySubcont      = $planDelivery->count();
             $countReadyToDeliverySubcont    = $data->count();

             return view('po-tracking/subcontractor/readytodelivery',
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
     //ReadyDeliveryProc
     public function readyDeliveryProc()
     {
         if($this->PermissionActionMenu('subcontractorreadydelivery-proc')->r==1){
         	
             $header_title                   = "PO SUBCONT - READY TO DELIVERY";
             $date   = Carbon::now();
             LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont Ready TO Delivery',
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

             $link_newPO                     = 'subcontractornewpo-proc';
             $link_ongoing                   = 'subcontractorongoing-proc';
             $link_planDelivery              = 'subcontractorplandelivery-proc';
             $link_readyToDelivery           = 'subcontractorreadydelivery-proc';
             $link_historyPO                 = 'subcontractorhistory-proc';

             $kodex = ['A','D'] ;

             $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-proc');
             $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->where('NRP',Auth::user()->email)->
                 groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $planDelivery = VwViewTicket::select('Number','ItemNumber','TicketID')->where('NRP',Auth::user()->email)->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
             $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.VendorCode','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
                leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
                leftjoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                ->where('po.CreatedBy',Auth::user()->email)->whereIn('detailticketingdelivery.status',$kodex)
                ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
             $HistorySubcont          = VwSubcontHistory::select('Number','ItemNumber')->where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

             $countNewpoSubcont       = $NewpoSubcont->count();
             $countOngoingSubcont     = $OngoingSubcont->count();
             $countHistorySubcont     = $HistorySubcont->count();
             $countPlanDeliverySubcont      = $planDelivery->count();
             $countReadyToDeliverySubcont    = $data->count();

             return view('po-tracking/subcontractor/readytodelivery',
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
      //ReadyDeliveryVendor
    public function readyDeliveryVendor(Request $request)
    {
        if($this->PermissionActionMenu('subcontractorreadydelivery-vendor')->r==1){

            $header_title                   = "PO SUBCONT - READY TO DELIVERY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont Ready TO Delivery',
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

            $link_newPO                     = 'subcontractornewpo-vendor';
            $link_ongoing                   = 'subcontractorongoing-vendor';
            $link_planDelivery              = 'subcontractorplandelivery-vendor';
            $link_readyToDelivery           = 'subcontractorreadydelivery-vendor';
            $link_historyPO                 = 'subcontractorhistory-vendor';

            $kodex = ['A','D'] ;

            $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-vendor');
            $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->where('VendorCode',Auth::user()->email)->
                groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
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
                    $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.VendorCode','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
                    leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                    leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
                    leftJoin('uservendors', function($join)
                    {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('uservendors.VendorCode',Auth::user()->email)->where('detailticketingdelivery.Number',$request->Number)
                    ->whereIn('detailticketingdelivery.status',$kodex)
                    ->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            }else{
                    $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.VendorCode','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
                    leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
                    leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
                    leftJoin('uservendors', function($join)
                    {
                        $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                        ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                    })
                        ->where('uservendors.VendorCode',Auth::user()->email)->whereIn('detailticketingdelivery.status',$kodex)
                    ->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            }

            $HistorySubcont          = VwSubcontHistory::select('Number','ItemNumber')->where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber')->get();

            $countNewpoSubcont       = $NewpoSubcont->count();
            $countOngoingSubcont     = $OngoingSubcont->count();
            $countHistorySubcont     = $HistorySubcont->count();
            $countPlanDeliverySubcont      = $planDelivery->count();
            $countReadyToDeliverySubcont    = $data->count();

            return view('po-tracking/subcontractor/readytodelivery',
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
       //ReadyDeliveryProc
       public function readyDeliveryWhs()
       {
           if($this->PermissionActionMenu('subcontractorreadydelivery-whs')->r==1){

               $header_title                   = "PO SUBCONT - READY TO DELIVERY";
               $date   = Carbon::now();
               LogHistory::updateOrCreate([
                'user'  => Auth::user()->email,
                'menu'  => 'PO Subcont Ready TO Delivery',
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

               $link_newPO                     = 'subcontractornewpo-whs';
               $link_ongoing                   = 'subcontractorongoing-whs';
               $link_planDelivery              = 'subcontractorplandelivery-whs';
               $link_readyToDelivery           = 'subcontractorreadydelivery-whs';
               $link_historyPO                 = 'subcontractorhistory-whs';
               $kodex = ['A','D'] ;

               $actionmenu =  $this->PermissionActionMenu('subcontractorreadydelivery-whs');
               $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
               $OngoingSubcont  = VwSubcontOngoing::select('POID','ItemNumber')->
                   groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
               $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
               $data = DetailTicket::select('detailticketingdelivery.*','po.CreatedBy as NRP','po.PurchaseOrderCreator','po.ReleaseDate','po.VendorCode','po.Date','purchasingdocumentitem.POID','purchasingdocumentitem.DeliveryDate as DeliveryDateS','purchasingdocumentitem.POID','uservendors.Name as Vendor','uservendors.VendorType','uservendors.VendorCode','uservendors.VendorCode_new')->
               leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
               leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
               leftJoin('uservendors', function($join)
                {
                    $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                    ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
                })
               ->whereIn('detailticketingdelivery.status',$kodex)
               ->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
               $HistorySubcont          = VwSubcontHistory::select('Number','ItemNumber')->groupBy('Number', 'ItemNumber')->get();

               $countNewpoSubcont       = $NewpoSubcont->count();
               $countOngoingSubcont     = $OngoingSubcont->count();
               $countHistorySubcont     = $HistorySubcont->count();
               $countPlanDeliverySubcont      = $planDelivery->count();
               $countReadyToDeliverySubcont    = $data->count();

               return view('po-tracking/subcontractor/readytodelivery',
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
        //ProsesUpdate
    public function ProsesUpdate(Request $request)
    {
    	if($this->PermissionActionMenu('subcontractorreadydelivery-whs')){
                $link = "subcontractorreadydelivery-whs";
            }else if($this->PermissionActionMenu('subcontractorreadydelivery')){
                $link = "subcontractorreadydelivery";
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
           'menu'  => 'PO Subcont Ready TO Delivery',
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
            'menu'  => 'PO Subcont Ready TO Delivery',
            'description' => 'Download Ticket',
            'date'  => $date->toDateString(),
            'time'     => $date->toTimeString(),
            'ponumber' => $dataviewticket->Number,
            'poitem' => NULL,
            'userlogintype' => Auth::user()->title ,
            'vendortype' => 'Subcont',
            'CreatedBy'  => Auth::user()->name,
          ]);
         Notification::where('Number',$dataviewticket->Number)->where('Subjek','Approve Ticket Subcont')
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

             $pdf = PDF::loadView('po-tracking/subcontractor/subcontractorticket', $data);

         // return view('po-non-sap/po-local/ticketpdf')->with('data', $data);
         return $pdf->stream();
         }else{
             return redirect()->back()->with('err_message', 'Data Tidak Ditemukan');
         }

     }





}
