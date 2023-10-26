<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\MstApps;
use App\Models\Table\Elsa\MstDept;
use App\Models\MstInfo;
use App\Models\Table\Elsa\InventoryRequest;
use App\Models\Table\Elsa\Ticket;
use App\Models\Table\Elsa\Comment;
use App\Models\Table\Elsa\UsingAsset;
use App\Models\Table\Elsa\MstLocMesin;
use App\Models\View\Elsa\VwTicket;
use App\Models\View\Elsa\VwComment;
use App\Models\View\Elsa\VwNotifComment;
use App\Models\View\Elsa\VwPr;
use App\Models\View\Elsa\VwUsingAsset;
use App\Models\Table\Elsa\History;
use App\Models\Table\Qrgad\MsPerusahaan;
use App\Models\Table\Qrgad\MsRuangan;
use App\Models\Table\Qrgad\TbJadwalRuangan;
use App\Models\Table\Qrgad\TbTripRequest;
use App\Models\User;
use App\Models\View\CompletenessComponent\VwComments;
use App\Models\Table\PoTracking\Comments as PoTrackingComments;
use App\Models\Table\PoTracking\Comments;
use App\Models\Table\PoTracking\Notification;
use App\Models\View\Qrgad\VwJadwalRuangan;
use App\Models\View\Qrgad\VwKeluhan;
use App\Models\View\Qrgad\VwTabelInventory;
use App\Models\View\Qrgad\VwTrip;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Blade::withoutDoubleEncoding();
        view()->composer(['panel.sidebar','fe-layouts.sidebar','fe-layouts.master', 'completeness-component.panel.sidebar', 'po-tracking.panel.sidebar','Qrgad.layout.sidebar','cogs.panel.sidebar'], function ($view) { // bayu add  'completeness-component.panel.sidebar'
          
          $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('app',Auth::user()->accessed_app)->orderBy('menu', 'asc')->get();
          $haveapp =  VwPermissionAppsMenu::select('app','app_name','logo')->where('user', Auth::user()->id)->groupBy('app_name')->get();
          $mainmenu = VwPermissionAppsMenu::select('main')->where('user', Auth::user()->id)->where('app',Auth::user()->accessed_app)->orderBy('main', 'asc')->groupBy('main')->get();
          $maintop = VwPermissionAppsMenu::select('topmain')->where('user', Auth::user()->id)->where('app',Auth::user()->accessed_app)->orderBy('topmain', 'asc')->groupBy('topmain')->get();
        
        
          $notifreqpr = VwPr::select('pr_id as id_req','pr_number as fk_id','pr_category as fk_desc','inventory_nama as ket','pr_description as message','rate','accept_to','approve_to','accepted','accepted_date','accepted_remark','approved','approved_date','approved_remark',
          'status','created_at','created_by')->whereIn('status',[0,1])->where('accept_to',Auth::user()->email)->orWhere('approve_to',Auth::user()->email)->whereIn('status',[0,1])->get();

          $notifreqticket = VwTicket::select('id as id_req','ticket_id as fk_id','subject as fk_desc','reporter_name as ket','message as message','rate','approve_dept_to','approve_dept','approve_dept_at','approve_dept_remark', 'status','created_at','created_by')->where('status','1')->where('approve_dept_to',Auth::user()->email)->get();
          
          $dept= MstDept::whereIn('id',['8927','8884','8915','8931','8921','8933','8930','8905'])->get();
          $mstloc= MstLocMesin::get();
          $usingasset= VwUsingAsset::where('user_nrp', Auth::user()->email)->orderBy('asset_id','asc')->get();
          $ticket = VwTicket::where('reporter_nrp',Auth::user()->email)->orderBy('created_at', 'desc')->whereIn('flag',[1,2,3,4,9])->get();
          $pr = InventoryRequest::where('pr_nrp',Auth::user()->email)->orderBy('created_at', 'desc')->whereIn('status',[0,1])->get();
          $comment = VwNotifComment::where('reporter_nrp',Auth::user()->email)->where('created_by','!=',Auth::user()->email)->orWhere('assist_id',Auth::user()->id)->where('created_by','!=',Auth::user()->email)->orderBy('created_at', 'desc')->get();
          $count_comment = VwNotifComment::where('reporter_nrp',Auth::user()->email)->where('created_by','!=',Auth::user()->email)->where('notif','!=',0)->orWhere('assist_id',Auth::user()->id)->where('created_by','!=',Auth::user()->email)->where('notif','!=',0)->orderBy('created_at', 'desc')->count();
          
          $roomLoans = VwJadwalRuangan::all();
          foreach ($roomLoans as $jadwal){
                $roomLoanArray[] = [
                    'title' => " | ".$jadwal->agenda,
                    'start' => $jadwal->start,
                    'end' => $jadwal->end,
                    'backgroundColor' => $jadwal->color,
                ];
          }

        $carLoans = VwTrip::where('status', ">=", 3 )->whereNotNull('kendaraan')->where('status_trip', '!=', 0)->orderBy('departure_time', 'DESC')->get(); //peminjaman kendaraan (internal, bukan grab) yang sudah berangkat
        foreach ($carLoans as $carLoan){
            $carLoanArray[] = [
                'title' => " | ".$carLoan->kendaraan,
                'start' => $carLoan->waktu_berangkat,
                'end' => $carLoan->waktu_pulang,
                'backgroundColor' => "#147df5ff",
            ];
        }

          $rooms = MsRuangan::all()->where('status', 1);
          $companies = MsPerusahaan::all()->where('status', 1);
          $notifHisTms = TbTripRequest::where('pemohon', Auth::user()->email)->count();
          $notifHisRoom = TbJadwalRuangan::where('peminjam', Auth::user()->email)->count();

          $datamenu = [];
          foreach($mainmenu as $main){
            $menu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('main',$main->main)->where('app',Auth::user()->accessed_app)->orderBy('app_menu', 'asc')->get();
            $datamenu[] = [
                'main'=>$main->main,
                'menu'=>$menu,
                'icon' => $main->icon
            ];
          }
          $datatopmenu = [];
          $datamainmenu = [];
          foreach($maintop as $top){
          $menutop = VwPermissionAppsMenu::select('topmain','main')->where('topmain',$top->topmain)->where('topmain','!=',null)->where('user', Auth::user()->id)->where('app',Auth::user()->accessed_app)->orderBy('main', 'asc')->groupBy('main')->get();
            foreach($menutop as $main){
              $menu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('main',$main->main)->where('app',Auth::user()->accessed_app)->orderBy('menu', 'asc')->get();
              $datamainmenu[] = [
                  'topmain'=>$main->topmain,
                  'main'=>$main->main,
                  'menu'=>$menu
              ];
            }
            $datatopmenu[] = [
                'topmain'=>$top->topmain,
                'main'=>$datamainmenu
            ];
          }

          $data = array(
              'notifreqpr' => $notifreqpr,
              'notifreqticket' => $notifreqticket,
              'haveapp' => $haveapp,
              'appsmenu' => $appsmenu,
              'datamenu' => $datamenu,
              'datatopmenu' => $datatopmenu,
              'comment' => $comment,
              'count_comment' => $count_comment,
              'pr' => $pr,
              'ticket' => $ticket,
              'dept' => $dept,
              'mstloc' => $mstloc,
              'usingasset' => $usingasset,
              'roomLoanArray' => $roomLoanArray,
              'carLoanArray' => $carLoanArray,
              'rooms' => $rooms,
              'companies' => $companies,
              'notifHisTms' => $notifHisTms,
              'notifHisRoom' => $notifHisRoom,
          );
          $view->with('data', $data);
        });

       //potracking
       view()->composer(['po-tracking.panel.header'], function ($view) {
            $notifchat = Comments::whereNull('is_read')->groupby('Number', 'ItemNumber', 'comment')->where('user_to', Auth::user()->name)->where('menu', 'Pesan')->orderBy('id', 'DESC')
                ->get();
            $recentrecord = Comments::where('user_to', Auth::user()->name)->groupby('Number', 'ItemNumber')->selectRaw('MAX(id) as id')->limit(15)->get()->toArray();
            $notifschat = Comments::orderBy('created_at', 'DESC')->select('id', 'Number', 'ItemNumber', 'user_by', 'user_to', 'menu', 'comment', 'is_read', 'created_at')
                ->where('user_to', Auth::user()->name)->where('menu', 'Pesan')->whereIn('id', $recentrecord)->get();
            $notif = Notification::groupby('Number', 'Subjek', 'comment')->where('user_to', Auth::user()->name)->whereNotIn('menu', ['Pesan', 'Kanban'])->where('is_read', '!=', 3)->orderBy('id', 'DESC')
                ->get();
            $notifs = Notification::select('id', 'Number', 'comment', 'Subjek', 'user_by', 'user_to', 'menu', 'created_at')->where('user_to', Auth::user()->name)->whereNotIn('menu', ['Pesan', 'Kanban'])->where('is_read', '!=', 3)->get();
            $notifkanban = Notification::groupby('Number', 'Subjek', 'comment')->where('user_to', Auth::user()->name)->where('menu', '=', 'Kanban')->where('is_read', '=', 1)->orderBy('id', 'DESC')
                ->get();
            $countnotifchat       = $notifchat->count();
            $countnotif      = $notif->count();
            $countnotifkanban      = $notifkanban->count();
            $data    = array(
                'countnotifchat'  => $countnotifchat,
                'notifchat'     => $notifschat,
                'notifkanban'     => $notifkanban,
                'countnotif'  => $countnotif,
                'countnotifkanban'  => $countnotifkanban,
                'notif'     => $notifs
            );
            $view->with('data', $data);
        });
        //bayu
        view()->composer(['completeness-component.panel.header'], function ($view){
            $ccr_last_chat    = VwComments::where('user_to', Auth::user()->id)->where('user_by', '!=', Auth::user()->id)->where('is_read', null)->groupby('MATNR')->selectRaw('MAX(id) as id')->get();
            $comment_ccr      = VwComments::whereIn('id', $ccr_last_chat)->latest()->get();
                            
            $po_last_chat   = Comments::where('user_to', Auth::user()->name)->groupby('Number','ItemNumber')->where('is_read', null)->selectRaw('MAX(id) as id')->get();
            $comment_po     = Comments::whereIn('id', $po_last_chat)->latest()->get();
            
            $data_ccr       = [];
            $data_po        = [];
            
            foreach ($comment_ccr as $ccr) {
                $data_ccr[] = [
                    'apps'      => 'CCR',
                    'sender'    => $ccr->nama_pengirim,
                    'po_no'     => '-',
                    'itemNumber'=> '-',
                    'material'  => $ccr->MATNR,
                    'chat'      => $ccr->comment,
                    'is_read'   => $ccr->is_read,
                    'created_at'=> $ccr->created_at,
                ];
            }
            
            foreach ($comment_po as $po) {
                $data_po[] = [
                    'apps'      => 'PO Tracking',
                    'sender'    => $po->user_by,
                    'po_no'     => $po->Number,
                    'itemNumber'=> $po->ItemNumber,
                    'material'  => '-',
                    'chat'      => $po->comment,
                    'is_read'   => $po->is_read,
                    'created_at'=> $po->created_at,
                ];
            }

            $comment[] = array_merge($data_ccr, $data_po);
            $comment = $comment[0];
            
            array_multisort( array_column($comment, "is_read"), SORT_ASC, array_column($comment, "created_at"), SORT_DESC, $comment );
            
            $komentar = array_slice($comment, 0, 5);
            
            $data    = array(
                'komentar'  => $komentar,
                'count'     => count($comment)
            );
            $view->with('data', $data);
        });
        
        view()->composer("Qrgad/layout/notification", function($view){
          if(Auth::user()->role_id == "LV00000001" || Auth::user()->role_id == "LV00000002"){

              $view->with([
                  "notif_keluhan" => VwKeluhan::where('status', 0)->orderBy('input_time', 'DESC')->get(),
                  "notif_trip" => VwTrip::where('status', 1)->orWhere('status', 2)->orWhere('status', 3)->whereNull('set_trip_time')->orderBy('input_time', 'DESC')->get(),
                  "notif_inventory" => VwTabelInventory::where("stock", "<=" , VwTabelInventory::raw('minimal_stock'))->orderBy('last_out', 'DESC')->get()
              ]);

          } else if(Auth::user()->role_id == "LV00000004"){

              $view->with([
                  "notif_keluhan" => VwKeluhan::where('email', Auth::user()->email)->where('status', 1)->orderBy('input_time', 'DESC')->get(),
                  "notif_trip" => VwTrip::where('email', Auth::user()->email)->where('status', 0)->orWhere('status', 2)->orWhere('status', 3)->WhereNotNull('set_trip_time')->orderBy('input_time', 'DESC')->get(),
                  "notif_inventory" => []
              ]);
              
          } else {
              $view->with([
                  "notif_keluhan" => [],
                  "notif_trip" => [],
                  "notif_inventory" => []
              ]);

          }
      });
        Paginator::useBootstrap();
    }
}
