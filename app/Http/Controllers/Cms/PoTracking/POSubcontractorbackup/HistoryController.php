<?php

namespace App\Http\Controllers\Cms\PoTracking\POSubcontractor;
use App\Http\Controllers\Controller;
use App\Models\Table\PoTracking\DetailTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwViewTicket;
use Illuminate\Support\Carbon;

class HistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if ($this->PermissionMenu('subcontractorhistory') == 0 && $this->PermissionMenu('subcontractorhistory-nonmanagement') == 0 && $this->PermissionMenu('subcontractorhistory-proc') == 0 && $this->PermissionMenu('subcontractorhistory-vendor') == 0 && $this->PermissionMenu('subcontractorhistory-whs') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }


    //poHIstoryview
    public function Subcontractorhistory()
    {

        if($this->PermissionActionMenu('subcontractorhistory')->r==1 ){

            $header_title                   = "PO SUBCONT - PO HISTORY";
            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                 'user'  => Auth::user()->email,
                 'menu'  => 'PO History Subcont',
                 'description' => 'Display PO History Subcont',
                 'date'  => $date->toDateString(),
                 'ponumber' => NULL,
                 'poitem' => NULL,
                 'userlogintype' => Auth::user()->title ,
                 'vendortype' => 'Subcont',
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


            $actionmenu =  $this->PermissionActionMenu('subcontractorhistory');
            $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
            $OngoingSubcont = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->get();
            $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ID', 'asc')->get();
            $data = VwSubcontHistory::select('ID','Number','NRP','PurchaseOrderCreator','Vendor','VendorCode_new','VendorCode','POID','Date','ReleaseDate','DeliveryDate','ParentID','ItemNumber',
            'Material','MaterialVendor','Description','NetPrice','Currency','Quantity',
           'ConfirmedDate','ConfirmedQuantity','ActiveStage','IsClosed','totalgr')->
            groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

            $countNewpoSubcont       = $NewpoSubcont->count();
            $countOngoingSubcont     = $OngoingSubcont->count();
            $countHistorySubcont     = $data->count();
            $countPlanDeliverySubcont      = $planDelivery->count();
            $countReadyToDeliverySubcont    = $readyToDelivery->count();

            return view('po-tracking/subcontractor/subcontractorhistory',
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
      //poHIstoryviewNonManagement
      public function SubcontractorhistoryNonManagement()
      {
          if($this->PermissionActionMenu('subcontractorhistory-nonmanagement')->r==1 ){

              $header_title                   = "PO SUBCONT - PO HISTORY";

              $date   = Carbon::now();
              LogHistory::updateOrCreate([
                   'user'  => Auth::user()->email,
                   'menu'  => 'PO History Subcont',
                   'description' => 'Display PO History Subcont',
                   'date'  => $date->toDateString(),
                   'ponumber' => NULL,
                   'poitem' => NULL,
                   'userlogintype' => Auth::user()->title ,
                   'vendortype' => 'Subcont',
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


              $actionmenu =  $this->PermissionActionMenu('subcontractornewpo');
             $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
             $OngoingSubcont = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->get();
             $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
             $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ID', 'asc')->get();
             $data = VwSubcontHistory::select('ID','Number','NRP','PurchaseOrderCreator','Vendor','VendorCode_new','VendorCode','POID','Date','ReleaseDate','DeliveryDate','ParentID','ItemNumber',
                  'Material','MaterialVendor','Description','Quantity','totalgr',
                 'ConfirmedDate','ConfirmedQuantity','ActiveStage','IsClosed')->
                  groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

              $countNewpoSubcont       = $NewpoSubcont->count();
              $countOngoingSubcont     = $OngoingSubcont->count();
              $countHistorySubcont     = $data->count();
              $countPlanDeliverySubcont      = $planDelivery->count();
              $countReadyToDeliverySubcont    = $readyToDelivery->count();

              return view('po-tracking/subcontractor/subcontractorhistory-nonmanagement',
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
  //poHIstoryviewProc
  public function SubcontractorhistoryProc()
  {
      if($this->PermissionActionMenu('subcontractorhistory-proc')->r==1 ){

          $header_title                   = "PO SUBCONT - PO HISTORY";

          $date   = Carbon::now();
            LogHistory::updateOrCreate([
                 'user'  => Auth::user()->email,
                 'menu'  => 'PO History Subcont',
                 'description' => 'Display PO History Subcont',
                 'date'  => $date->toDateString(),
                 'ponumber' => NULL,
                 'poitem' => NULL,
                 'userlogintype' => Auth::user()->title ,
                 'vendortype' => 'Subcont',
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


          $actionmenu =  $this->PermissionActionMenu('subcontractorhistory-proc');
          $NewpoSubcont = VwSubcontNewpo::where('NRP',Auth::user()->email)->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
          $OngoingSubcont = VwSubcontOngoing::select('POID','ItemNumber')->where('NRP',Auth::user()->email)->distinct('POID','ItemNumber')->get();
          $data = VwSubcontHistory::select('ID','Number','NRP','PurchaseOrderCreator','Vendor','VendorCode_new','VendorCode','POID','Date','ReleaseDate','DeliveryDate','ParentID','ItemNumber','Material','MaterialVendor','Description','NetPrice','Currency','Quantity','ConfirmedDate','ConfirmedQuantity','totalgr','ActiveStage','IsClosed')
          ->where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
          $planDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->
          leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
          leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->
          leftJoin('uservendors', function($join)
              {
                  $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                  ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
              })
          ->where('po.CreatedBy',Auth::user()->email)
          ->where('detailticketingdelivery.status','P')->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

          $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
              ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
              {
                  $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                  ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
              })
              ->where('po.CreatedBy',Auth::user()->email)
              ->whereIn('detailticketingdelivery.status',$kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();

          $countNewpoSubcont       = $NewpoSubcont->count();
          $countOngoingSubcont     = $OngoingSubcont->count();
          $countHistorySubcont     = $data->count();
          $countPlanDeliverySubcont      = $planDelivery->count();
          $countReadyToDeliverySubcont    = $readyToDelivery->count();

          return view('po-tracking/subcontractor/subcontractorhistory',
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
   //poHIstoryviewVendor
   public function SubcontractorhistoryVendor()
   {
       if($this->PermissionActionMenu('subcontractorhistory-vendor')->r==1 ){

           $header_title                   = "PO SUBCONT - PO HISTORY";
           $date   = Carbon::now();
           $date   = Carbon::now();
            LogHistory::updateOrCreate([
                 'user'  => Auth::user()->email,
                 'menu'  => 'PO History Subcont',
                 'description' => 'Display PO History Subcont',
                 'date'  => $date->toDateString(),
                 'ponumber' => NULL,
                 'poitem' => NULL,
                 'userlogintype' => Auth::user()->title ,
                 'vendortype' => 'Subcont',
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

           $actionmenu =  $this->PermissionActionMenu('subcontractorhistory-vendor');
           $NewpoSubcont = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->groupBy('Number','ItemNumber')->orderBy('Number', 'asc')->get();
           $OngoingSubcont = VwSubcontOngoing::select('POID','ItemNumber')->where('VendorCode',Auth::user()->email)->distinct('POID','ItemNumber')->get();
           $planDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->
              leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')->
              leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
              {
                  $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                  ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
              })
              ->where('uservendors.VendorCode',Auth::user()->email)
              ->where('detailticketingdelivery.status','P')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
            $readyToDelivery = DetailTicket::select('detailticketingdelivery.Number','detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->leftjoin('purchasingdocumentitem', 'purchasingdocumentitem.ID', '=', 'detailticketingdelivery.PDIID')
              ->leftjoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', function($join)
              {
                  $join->on('uservendors.VendorCode', '=', 'po.VendorCode')
                  ->orOn('uservendors.VendorCode_new', '=', 'po.VendorCode');
              })
              ->where('uservendors.VendorCode',Auth::user()->email)
              ->whereIn('detailticketingdelivery.status',$kodex)->groupBy('detailticketingdelivery.Number', 'detailticketingdelivery.ItemNumber','detailticketingdelivery.TicketID')->orderBy('detailticketingdelivery.Number', 'asc')->orderBy('detailticketingdelivery.ItemNumber', 'asc')->get();
           $data = VwSubcontHistory::select('ID','Number','NRP','PurchaseOrderCreator','Vendor','VendorCode_new','VendorCode','POID','Date','ReleaseDate','DeliveryDate','ParentID','ItemNumber','Material','MaterialVendor','Description','NetPrice','Currency','Quantity','ConfirmedDate','totalgr','ConfirmedQuantity','ActiveStage','IsClosed')
           ->where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();
           $countNewpoSubcont       = $NewpoSubcont->count();
           $countOngoingSubcont     = $OngoingSubcont->count();
           $countHistorySubcont     = $data->count();
           $countPlanDeliverySubcont      = $planDelivery->count();
           $countReadyToDeliverySubcont    = $readyToDelivery->count();

           return view('po-tracking/subcontractor/subcontractorhistory',
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
    //poHIstoryviewNonManagement
    public function SubcontractorhistoryWhs()
    {
        if($this->PermissionActionMenu('subcontractorhistory-whs')->r==1 ){

            $header_title                   = "PO SUBCONT - PO HISTORY";

            $date   = Carbon::now();
            LogHistory::updateOrCreate([
                 'user'  => Auth::user()->email,
                 'menu'  => 'PO History Subcont',
                 'description' => 'Display PO History Subcont',
                 'date'  => $date->toDateString(),
                 'ponumber' => NULL,
                 'poitem' => NULL,
                 'userlogintype' => Auth::user()->title ,
                 'vendortype' => 'Subcont',
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


         $actionmenu =  $this->PermissionActionMenu('subcontractornewpo');
           $NewpoSubcont = VwSubcontNewpo::select('Number','ItemNumber')->groupBy('Number','ItemNumber','Quantity')->orderBy('Number', 'asc')->get();
           $OngoingSubcont = VwSubcontOngoing::select('POID','ItemNumber')->distinct('POID','ItemNumber')->get();
           $planDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->where('status','P')->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ItemNumber', 'asc')->get();
           $readyToDelivery = DetailTicket::select('Number','ItemNumber','TicketID')->whereIn('status',$kodex)->groupBy('Number', 'ItemNumber','TicketID')->orderBy('ID', 'asc')->get();
           $data = VwSubcontHistory::select('ID','Number','NRP','PurchaseOrderCreator','Vendor','VendorCode_new','VendorCode','POID','Date','ReleaseDate','DeliveryDate','ParentID','ItemNumber',
                'Material','MaterialVendor','Description','Quantity','totalgr',
               'ConfirmedDate','ConfirmedQuantity','ActiveStage','IsClosed')->
                groupBy('Number', 'ItemNumber')->orderBy('id', 'desc')->get();

            $countNewpoSubcont       = $NewpoSubcont->count();
            $countOngoingSubcont     = $OngoingSubcont->count();
            $countHistorySubcont     = $data->count();
            $countPlanDeliverySubcont      = $planDelivery->count();
            $countReadyToDeliverySubcont    = $readyToDelivery->count();

            return view('po-tracking/subcontractor/subcontractorhistory-nonmanagement',
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
        if($this->PermissionActionMenu('subcontractorhistory')){
            $links =  'subcontractorhistory';
        }else if($this->PermissionActionMenu('subcontractorhistory-vendor')){
            $links =  'subcontractorhistory-vendor';
        }else {
          return redirect()->back()->with('err_message', 'Acces Denied!');
        }

        if(!empty($data)){
            $poreturn = Pdi::where('POID',$request->POID)->where('IsClosed','C')
                ->update([
                    'ActiveStage'=>NULL,
                    'IsClosed'=>NULL,
                    'LastModifiedBy'=>'Reverse PO',
                ]);
                if($poreturn){
                    return redirect($links)->with('suc_message', 'Reverse PO Success!');
                }else{
                    return redirect()->back()->with('err_message', 'Reverse PO gagal !');
                }
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }




}
