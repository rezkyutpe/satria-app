<?php

namespace App\Http\Controllers\Cms\PoTracking;

use File;
use Response;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table\PoTracking\Pdi;
use App\Models\View\PoTracking\VwPo;
use Illuminate\Support\Facades\Auth;
use App\Models\View\PoTracking\Vwindex;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\Vendors;
use App\Models\Table\PoTracking\Shipment;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\View\PoTracking\VwPoImportNewPO;
use App\Models\View\PoTracking\VwPoLocalOngoing;
use App\Models\View\PoTracking\VwPoImportHistory;
use App\Models\View\PoTracking\VwPoImportOngoing;
use App\Models\View\PoTracking\VwHistorytotal;

class PoImportController extends Controller
{
    public $newpo           = 'poimportnewpo';
    public $ongoing         = 'poimportongoing';
    public $planDelivery    = '#';
    public $readyToDelivery = '#';
    public $reportPO        = '#';
    public $historyPO       = 'poimporthistory';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('poimportnewpo') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function poimportnewpo()
    {
        if ($this->PermissionActionMenu('poimportnewpo')->v == 1 || $this->PermissionActionMenu('poimportnewpo')->d == 1  || $this->PermissionActionMenu('poimportnewpo')->u == 1  || $this->PermissionActionMenu('poimportnewpo')->r == 1 || $this->PermissionActionMenu('poimportnewpo')->c == 1) {
            $header_title           = "PO Import - New PO";
            $actionmenu            =  $this->PermissionActionMenu('poimportnewpo');

            $link_search            = "caripoimportnewpo";
            $link_reset             = "poimportnewpo";
            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;
            $actionmenu =  $this->PermissionActionMenu('poimportnewpo');
            if ($this->PermissionActionMenu('poimportnewpo')->c == 1) {
                $data                   = VwPoImportNewPO::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->orderBy('Number', 'asc')->get();
                $PoImportHistory        = VwPoImportHistory::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::where('VendorCode',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->u == 1) {
                $data                   = VwPoImportNewPO::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $PoImportHistory        = VwPoImportHistory::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportOnGoing        = VwPoImportOngoing::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->r == 1 ||$this->PermissionActionMenu('poimportnewpo')->v == 1||$this->PermissionActionMenu('poimportnewpo')->d == 1) {

                $data                   = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('Number', 'asc')->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }
            $countNewpoImport       = $data->count();
            $countHistorypoImport   = $PoImportHistory->count();
            $countongoingpoImport   = $PoImportOnGoing->count();

            return view('po-tracking/poimport/newPO', compact('actionmenu', 'data','actionmenu', 'header_title', 'link_search', 'countNewpoImport', 'countongoingpoImport', 'countHistorypoImport', 'link_reset', 'link_newPO', 'link_ongoing', 'link_planDelivery', 'link_readyToDelivery', 'link_reportPO', 'link_historyPO'));
        } else {
            return redirect('potracking')->with('err_message', 'Access denied!');
        }
    }

    // Halaman Cari New PO Import
    public function caripoimportnewpo(Request $request)
    {
        if ($this->PermissionActionMenu('poimportnewpo')->v == 1 || $this->PermissionActionMenu('poimportnewpo')->d == 1 || $this->PermissionActionMenu('poimportnewpo')->r == 1 || $this->PermissionActionMenu('poimportnewpo')->c == 1) {
            $request->validate([
                'tanggal1' => 'required',
                'tanggal2' => 'required'
            ]);

            $header_title           = "PO Import - New PO Search by PO Date";

            $actionmenu            =  $this->PermissionActionMenu('poimportnewpo');

            $link_search            = "caripoimportnewpo";
            $link_reset             = "poimportnewpo";
            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;

            $date1                  = Carbon::createFromFormat('d/m/Y', trim($request->tanggal1))->format('Y-m-d');
            $date2                  = Carbon::createFromFormat('d/m/Y', trim($request->tanggal2))->format('Y-m-d');

            if ($this->PermissionActionMenu('poimportnewpo')->v == 1) {
                $data                   = VwPoImportNewPO::distinct()->where('VendorCode', Auth::user()->email)->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->u == 1) {
                $data                   = VwPoImportNewPO::distinct()->where('NRP',Auth::user()->email)->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->r == 1 ||$this->PermissionActionMenu('poimportnewpo')->v == 1||$this->PermissionActionMenu('poimportnewpo')->d == 1) {

                $data                   = VwPoImportNewPO::distinct()->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }


            $countNewpoImport       = $PoImport->count();
            $countHistorypoImport   = $PoImportHistory->count();
            $countongoingpoImport   = $PoImportOnGoing->count();

            return view('po-tracking/poimport/newPO', compact('actionmenu', 'data', 'header_title', 'link_search', 'link_reset', 'link_newPO', 'link_ongoing', 'link_planDelivery', 'link_readyToDelivery', 'link_reportPO', 'link_historyPO', 'countNewpoImport', 'countongoingpoImport', 'countHistorypoImport'));
        } else {
            return redirect('potracking')->with('err_message', 'Access denied!');
        }
    }

    public function poimporthistory()
    {
        if ($this->PermissionActionMenu('poimportnewpo')->u == 1 || $this->PermissionActionMenu('poimportnewpo')->v == 1 || $this->PermissionActionMenu('poimportnewpo')->d == 1  || $this->PermissionActionMenu('poimportnewpo')->r == 1 || $this->PermissionActionMenu('poimportnewpo')->c == 1) {
            $header_title           = "PO Import - History";

            $actionmenu            =  $this->PermissionActionMenu('poimportnewpo');

            $link_search            = "caripoimporthistory";
            $link_reset             = "poimporthistory";
            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;

            if ($this->PermissionActionMenu('poimportnewpo')->c == 1) {
                $data                   = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->u == 1) {
                $data                   = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->r == 1 ||$this->PermissionActionMenu('poimportnewpo')->v == 1||$this->PermissionActionMenu('poimportnewpo')->d == 1) {

                $data                   = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->orderBy('id', 'desc')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }

            $countNewpoImport       = $PoImport->count();
            $countHistorypoImport   = $data->count();
            $countongoingpoImport   = $PoImportOnGoing->count();
            $datafinishlocal = VwHistorytotal::all();
            return view('po-tracking/poimport/HistoryImport', compact('actionmenu', 'data', 'header_title', 'link_search', 'link_reset', 'link_newPO', 'link_ongoing', 'link_planDelivery', 'link_readyToDelivery', 'link_reportPO', 'link_historyPO', 'countNewpoImport', 'countongoingpoImport', 'countHistorypoImport','datafinishlocal'));
        } else {
            return redirect('potracking')->with('err_message', 'Access denied!');
        }
    }

    public function caripoimporthistory(Request $request)
    {
        if ($this->PermissionActionMenu('poimportnewpo')->v ==  1  || $this->PermissionActionMenu('poimportnewpo')->d == 1 || $this->PermissionActionMenu('poimportnewpo')->r == 1 || $this->PermissionActionMenu('poimportnewpo')->c == 1) {
            $request->validate([
                'tanggal1' => 'required',
                'tanggal2' => 'required'
            ]);

            $header_title           = "PO Import - History Search by Date";
            $actionmenu            =  $this->PermissionActionMenu('poimportnewpo');

            $link_search            = "caripoimporthistory";
            $link_reset             = "poimporthistory";
            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;

            $date1                  = Carbon::createFromFormat('d/m/Y', trim($request->tanggal1))->format('Y-m-d');
            $date2                  = Carbon::createFromFormat('d/m/Y', trim($request->tanggal2))->format('Y-m-d');

            if ($this->PermissionActionMenu('poimportnewpo')->c == 1) {
                $data                   = VwPoImportHistory::distinct()->where('VendorCode', Auth::user()->email)->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->u == 1) {
                $data                   = VwPoImportHistory::distinct()->where('NRP',Auth::user()->email)->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->r == 1 ||$this->PermissionActionMenu('poimportnewpo')->v == 1||$this->PermissionActionMenu('poimportnewpo')->d == 1) {

                $data                   = VwPoImportHistory::distinct()->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }

            $countNewpoImport       = $PoImport->count();
            $countHistorypoImport   = $PoImportHistory->count();
            $countongoingpoImport   = $PoImportOnGoing->count();

            return view('po-tracking/poimport/newPO', compact('actionmenu', 'data', 'header_title', 'link_search', 'link_reset', 'link_newPO', 'link_ongoing', 'link_planDelivery', 'link_readyToDelivery', 'link_reportPO', 'link_historyPO', 'countNewpoImport', 'countongoingpoImport', 'countHistorypoImport'));
        } else {
            return redirect('potracking')->with('err_message', 'Access denied!');
        }
    }


    public function poimportongoing()
    {
        if ($this->PermissionActionMenu('poimportnewpo')->u == 1 || $this->PermissionActionMenu('poimportnewpo')->d == 1 || $this->PermissionActionMenu('poimportnewpo')->r == 1 || $this->PermissionActionMenu('poimportnewpo')->c == 1) {
            $header_title           = "PO Import - On Going";

            $actionmenu            =  $this->PermissionActionMenu('poimportnewpo');

            $link_search            = "caripoimportongoing";
            $link_reset             = "poimportongoing";
            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;

            $datafinishlocal        = VwPoLocalOngoing::all();
            $lastdate               = date("Y-m-d", strtotime('-3 year'));
            $newdate                = date("Y-m-d");
            $todayDate              = Carbon::now()->format('Y-m-d');

            if ($this->PermissionActionMenu('poimportnewpo')->c == 1) {
                $data                   = VwPoImportOngoing::where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->u == 1) {
                $data                   = VwPoImportOngoing::where('NRP',Auth::user()->email)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->r == 1 ||$this->PermissionActionMenu('poimportnewpo')->v == 1||$this->PermissionActionMenu('poimportnewpo')->d == 1) {
                $data                   = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->get();
            }

            $countNewpoImport       = $PoImport->count();
            $countHistorypoImport   = $PoImportHistory->count();
            $countongoingpoImport   = $data->count();

            return view('po-tracking/poimport/onGoingPO', compact('actionmenu', 'data', 'datafinishlocal', 'header_title', 'link_search', 'link_reset', 'link_newPO', 'link_ongoing', 'link_planDelivery', 'link_readyToDelivery', 'link_reportPO', 'link_historyPO', 'countNewpoImport', 'countongoingpoImport', 'countHistorypoImport'));
        } else {
            return redirect('potracking')->with('err_message', 'Access denied!');
        }
    }

    public function caripoimportongoing(Request $request)
    {
        if ($this->PermissionActionMenu('poimportnewpo')->v == 1 || $this->PermissionActionMenu('poimportnewpo')->d == 1 || $this->PermissionActionMenu('poimportnewpo')->r == 1 || $this->PermissionActionMenu('poimportnewpo')->c == 1) {
            $request->validate([
                'tanggal1' => 'required',
                'tanggal2' => 'required'
            ]);

            $header_title           = "PO Import - On Going Search by Date";

            $actionmenu            =  $this->PermissionActionMenu('poimportnewpo');

            $link_search            = "caripoimportongoing";
            $link_reset             = "poimportongoing";
            $link_newPO             = $this->newpo;
            $link_ongoing           = $this->ongoing;
            $link_planDelivery      = $this->planDelivery;
            $link_readyToDelivery   = $this->readyToDelivery;
            $link_reportPO          = $this->reportPO;
            $link_historyPO         = $this->historyPO;

            $date1                  = Carbon::createFromFormat('d/m/Y', trim($request->tanggal1))->format('Y-m-d');
            $date2                  = Carbon::createFromFormat('d/m/Y', trim($request->tanggal2))->format('Y-m-d');
            $datafinishlocal        = VwPoLocalOngoing::all();

            if ($this->PermissionActionMenu('poimportnewpo')->v == 1) {
                $data                   = VwPoImportOngoing::distinct()->where('VendorCode', Auth::user()->email)->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('VendorCode', Auth::user()->email)->get();
            } elseif ($this->PermissionActionMenu('poimportnewpo')->u == 1) {
                $data                   = VwPoImportOngoing::distinct()->where('NRP',Auth::user()->email)->whereBetween('Date', [$date1, $date2])->groupBy('Number', 'ItemNumber', 'Quantity')->get();
                $PoImport               = VwPoImportNewPO::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportHistory        = VwPoImportHistory::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();
                $PoImportOnGoing        = VwPoImportOngoing::groupBy('Number', 'ItemNumber', 'Quantity')->where('NRP',Auth::user()->email)->get();

            return view('po-tracking/poimport/onGoingPO', compact('actionmenu', 'data', 'datafinishlocal', 'header_title', 'link_search', 'link_reset', 'link_newPO', 'link_ongoing', 'link_planDelivery', 'link_readyToDelivery', 'link_reportPO', 'link_historyPO', 'countNewpoImport', 'countongoingpoImport', 'countHistorypoImport'));
        } else {
            return redirect('potracking')->with('err_message', 'Access denied!');
        }
    }
}
    // Konfirmasi New PO Insert
    public function poImportInsert(Request $request)
    {
        // Get data berdasarkan ID
        $appsmenu = Pdi::where('ID', $request->ID)->first();

        // Jumlah barang yang disetujui vendor
        $qty = $request->ConfirmedQuantity;
        $jumlah = 0;

        // menghitung total quantity
        foreach ($qty as $q) {
            $jumlah += $q;
        }

        // Jika jumlah barang tidak sesuai dengan total Quantity
        if ($jumlah > $appsmenu->Quantity or $jumlah < $appsmenu->Quantity) {
            return redirect()->back()->with('err_message', 'Check Total Quantity!');
        }

        if (!empty($appsmenu)) {
            if ($request->action == "Save") {
                // Menghitung jumlah partial data
                $data   = count($request->ConfirmedQuantity);
                // Mengambil data berdasarkan POID
                $cekpo  = VwPoImportOngoing::where('POID', $appsmenu->POID)->first();

                // Jika Delivery Method Full
                if ((($request->DeliveryDate == $request->ConfirmedDate[0]) || ($request->DeliveryDate >= $request->ConfirmedDate[0])) && $data == 1) {
                    if (isset($cekpo)) {
                        $confirmed  = 1;
                        $active     = $cekpo->ActiveStage;
                        $proformaDocument = $cekpo->ProformaInvoiceDocument;
                    } else {
                        $confirmed  = 1;
                        $active     = 2;
                        $proformaDocument = NULL;
                    }
                } else {
                    $proformaDocument = NULL;
                    $confirmed  = NULL;
                    $active     = 1;
                }

                // Delete Duplicate Data
                // Pdi::where('ParentID', $request->ID)->whereNull('ActiveStage')->whereNull('IsClosed')->delete();

                // Menyimpan data kedalam DB
                for ($i = 0; $i < $data; $i++) {
                    // Data pertama
                    if ($i == 0) {
                        $update = Pdi::where('ID', $request->ID)
                            ->update([
                                'ParentID'          => $request->ID,
                                'ConfirmedDate'     => Carbon::createFromFormat('d/m/Y', trim($request->ConfirmedDate[0]))->format('Y-m-d'),
                                'ConfirmedQuantity' => trim($request->ConfirmedQuantity[0]),
                                'ConfirmedItem'     => $confirmed,
                                'ActiveStage'       => $active,
                                'ProformaInvoiceDocument'   => $proformaDocument
                            ]);
                    } else {
                        $newinsert = [
                            'POID'              => trim($appsmenu->POID),
                            'PRNumber'          => trim($appsmenu->PRNumber),
                            'PRCreateDate'      => trim($appsmenu->PRCreateDate),
                            'PRReleaseDate'     => trim($appsmenu->PRReleaseDate),
                            'DeliveryDate'      => trim($appsmenu->DeliveryDate),
                            'ParentID'          => trim($appsmenu->ID),
                            'ItemNumber'        => trim($appsmenu->ItemNumber),
                            'Material'          => trim($appsmenu->Material),
                            'MaterialVendor'    => trim($appsmenu->MaterialVendor),
                            'Description'       => trim($appsmenu->Description),
                            'NetPrice'          => trim($appsmenu->NetPrice),
                            'Currency'          => trim($appsmenu->Currency),
                            'Quantity'          => trim($appsmenu->Quantity),
                            'OpenQuantity'      => trim($appsmenu->OpenQuantity),
                            'ActiveStage'       => 1,
                            'ProformaInvoiceDocument'   => $proformaDocument,
                            'ConfirmedQuantity' => trim($request->ConfirmedQuantity[$i]),
                            'ConfirmedDate'     => Carbon::createFromFormat('d/m/Y', trim($request->ConfirmedDate[$i]))->format('Y-m-d'),
                        ];
                        $update = Pdi::insert($newinsert);
                    }
                }
                if ($update) {
                    if ($data == 1) {
                        return redirect('poimportongoing')->with('suc_message', 'Data processed successfully!');
                    } else {
                        return redirect('poimportnewpo')->with('suc_message', 'Data processed successfully!');
                    }
                } else {
                    return redirect()->back()->with('err_message', 'Data failed to process!');
                }
            } else {
                $cancel =  Pdi::where('ID', $request->ID)
                    ->update([
                        'ParentID'      => $request->ID,
                        'ConfirmedItem' =>  NULL,
                        'IsClosed'      => 'C',
                        'ActiveStage'   => 1
                    ]);
                if ($cancel) {
                    return redirect('poimporthistory')->with('suc_message', 'Data successfully canceled!');
                } else {
                    return redirect()->back()->with('err_message', 'Data failed to process!');
                }
            }
        } else {
            return redirect()->back()->with('err_message', 'Data not found!');
        }
    }

    //  New PO Negosiasi
    public function poImportUpdate(Request $request)
    {
        $appsmenu = Pdi::where('ID', $request->ID)->first();

        // Delete Duplicate Data
        // Pdi::where('ParentID', $request->ID)->whereNull('ActiveStage')->whereNull('IsClosed')->delete();

        // Mengambil data berdasarkan POID
        $cekpo  = VwPoImportOngoing::where('POID',  $appsmenu->POID)->first();
        if (!empty($appsmenu)) {
            if ($request->action == "Yes") {
                if (isset($cekpo)) {
                    $active     = $cekpo->ActiveStage;
                    $Pid        = $cekpo->ProformaInvoiceDocument;
                } else {
                    $Pid        = NULL;
                    $active     = 2;
                }

                $update =  Pdi::whereIn('ID', $request->ID)
                    ->update([
                        'ActiveStage'   => $active,
                        'ProformaInvoiceDocument' => $Pid,
                        'ConfirmedItem' => 1,
                        'IsClosed' => NULL
                    ]);
                } elseif($request->action == "Update"){
                    $data = count($request->IDS) ;

                    for ($i = 0; $i < $data; $i++) {
                            $update =  Pdi::where('ID', $request->IDS[$i])
                            ->update([
                            'ConfirmedDate'   =>  Carbon::createFromFormat('d/m/Y', $request->ConfirmedDate[$i])->format('Y-m-d'),
                            ]);

                         }

                    if($update ){
                        return redirect('poimportnewpo')->with('suc_message', 'PO Di Update!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                    }
            } elseif ($request->action == "Cancel") {
                // dd($request->ID);
                $cancel =  Pdi::whereIn('ID', $request->ID)
                    ->update([
                        'ConfirmedItem' =>  0,
                        'IsClosed'      => 'C',
                    ]);
                if ($cancel) {
                    return redirect('poimporthistory')->with('suc_message', 'Data successfully cancelled!');
                }
            }
            if ($update) {
                return redirect('poimportongoing')->with('suc_message', 'Data saved successfully!');
            } else {
                return redirect()->back()->with('err_message', 'Data failed to save!');
            }
        } else {
            return redirect()->back()->with('err_message', 'Data not found!');
        }
    }

    // Buat nampilin data modal by id New PO
    public function viewpoimport(Request $request)
    {
        $Polocal    = VwPo::where('ID', $request->id)->orWhere('ParentID', $request->id)->first();
        $viewtable  = VwPoImportNewPO::where('ID', $request->id)->orwhere('ParentID', $request->id)->get();
        $Vendors    = UserVendor::where('VendorCode', $Polocal->VendorCode)->first();
        $actionmenu =  $this->PermissionActionMenu('poimportnewpo');
        $data = array(
            'actionmenu' => $actionmenu,
            'subcont' => $Polocal,
            'viewtable' => $viewtable,
            'vendors' => $Vendors
        );
        echo json_encode($data);
    }

    // Buat nampilin data modal Cancel PO
    public function viewcancelpoimport(Request $request)
    {
        $Polocal    = VwPo::where('ID', $request->id)->first();
        $viewtable  = VwPoImportHistory::where('ID', $request->id)->orwhere('ParentID', $request->id)->get();
        $Vendors    = UserVendor::where('VendorCode', $Polocal->VendorCode)->first();
        $data = array(
            'subcont' => $Polocal,
            'viewtable' => $viewtable,
            'vendors' => $Vendors
        );
        echo json_encode($data);
    }
    // Ajax get Data By PO Number
    public function viewCariDataOngoing(Request $request)
    {
        $dataall    = VwPoImportOngoing::where('Number', $request->id)->groupBy('Number', 'ItemNumber', 'Quantity')->get();
        $dataid     = VwPo::where('Number', $request->id)->first();
        $data = array(
            'dataall'       => $dataall,
            'dataid'        => $dataid,
        );
        echo json_encode($data);
    }
    // Ajax get data By Id Item
    public function viewCariDataOngoingById(Request $request)
    {
        $dataall    = VwPoImportOngoing::where('Number', $request->number)->where('ItemNumber', $request->item)->groupBy('Number', 'ItemNumber')->get();
        $dataid     = VwPo::where('Number', $request->number)->where('ItemNumber', $request->item)->first();
            $data = array(
            'dataall'       => $dataall,
            'dataid'        => $dataid,

        );
        echo json_encode($data);
    }

    public function detailPO(Request $request)
    {
        $dataPO = VwPoImportOngoing::where('ID', $request->id)->first();
        $dataShipmentDocument = Shipment::where('POID', $dataPO->POID)->first();
        $dataPackingList = Shipment::where('POID', $dataPO->POID)->get();

        if ($request->action == 'Download Proforma Invoice') {
            $file = public_path("potracking/proforma_invoice/" . $dataPO->Number . '/' . $dataPO->ProformaInvoiceDocument);
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, $dataPO->ProformaInvoiceDocument, $headers);
        } elseif ($request->action == 'Download Shipment Document') {
            $file = public_path("potracking/ship_book/" . $dataPO->Number . '/' . $dataShipmentDocument->CopyBLDocument);
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, $dataShipmentDocument->CopyBLDocument, $headers);
        } elseif ($request->action == 'Download Packing List Document') {
            $zip = new \ZipArchive();
            $fileName = $dataPO->Number . '_PackingList.zip';
            if (!file_exists(public_path('potracking/document_shipment/' . $dataPO->Number . '/' . $fileName))) {
                if ($zip->open(public_path("potracking/document_shipment/" . $dataPO->Number . '/' . $fileName), \ZipArchive::CREATE) == TRUE) {
                    $files = File::files(public_path("potracking/document_shipment/" . $dataPO->Number));
                    foreach ($files as $key => $value) {
                        $relativeName = basename($value);
                        $zip->addFile($value, $relativeName);
                    }
                    $zip->close();
                }
            }
            return response()->download(public_path("potracking/document_shipment/" . $dataPO->Number . '/' . $fileName));
        }
    }
    // Data Modal Detail Item
    public function dataDetailsPO(Request $request)
    {
        $dataall    = VwPoImportOngoing::where('ID', $request->id)->groupBy('Number', 'ItemNumber', 'Quantity', 'ActualQuantity')->get();
        $dataid     = VwPo::where('ID', $request->id)->first();
        $data = array(
            'dataall'       => $dataall,
            'dataid'        => $dataid
        );
        echo json_encode($data);
    }


    public function uploadProforma(Request $request)
    {
        $appsmenu = VwPo::where('Number', $request->Number)->first();
        $request->validate([
            'filename' => 'required',
            'filename.*' => 'mimes:PDF,pdf|max:5120'
        ]);
        if ($request->hasfile('filename')) {
            foreach ($request->file('filename') as $file) {
                if ($file->isValid()) {
                    if (!file_exists(public_path('potracking/proforma_invoice/' . $request->Number))) {
                        mkdir(public_path('potracking/proforma_invoice/' . $request->Number), 0777, true);
                    }
                    $filename = $request->Number . '_proformaInvoice.pdf';
                    $file->move(public_path('potracking/proforma_invoice/' . $request->Number), $filename);
                }
            }
            $activeStage = array(2, '2a');
            $update =  Pdi::where('POID', $appsmenu->POID)->whereIn('ActiveStage', $activeStage)
                ->update([
                    'ActiveStage'                       => 3,
                    'ApproveProformaInvoiceDocument'    => 0
                ]);
            $update =  Pdi::where('POID', $appsmenu->POID)->where("IsClosed", NULL)
                ->update([
                    'ProformaInvoiceDocument'           => $filename,
                ]);
            if ($update) {
                return redirect('poimportongoing')->with('suc_message', 'Data processed successfully!');
            } else {
                return redirect()->back()->with('err_message', 'Data failed to process! Please check your extension and size of the file!');
            }
        } else {
            return redirect()->back()->with('err_message', 'Data not found!');
        }
    }
    public function verifyProforma(Request $request)
    {
        $dataPO = VwPoImportOngoing::where('Number', $request->Number)->first();
        if ($request->action == 'Revision') {
            $create = Pdi::where('POID', $dataPO->POID)->where('ActiveStage', 3)
                ->update([
                    'ActiveStage'                       => '2a',
                    'ProformaInvoiceDocument'           => NULL,
                    'ApproveProformaInvoiceDocument'    => 0
                ]);
            if ($create) {
                unlink(public_path("potracking/proforma_invoice/$dataPO->Number/$dataPO->ProformaInvoiceDocument"));
            }
        } elseif ($request->action == 'Save') {
            $validasi = $this->validate($request, [
                'confirmed_date' => 'required'
            ]);
            $activeStage = array('2a', 2, 3);
            $create = Pdi::where('POID', $dataPO->POID)->whereIn('ActiveStage', $activeStage)
                ->update([
                    'ActiveStage' => 4,
                    'ConfirmReceivedPaymentDate' => $request->confirmed_date,
                    'ApproveProformaInvoiceDocument'    => 1
                ]);
        }
        if ($create) {
            return redirect('poimportongoing')->with('suc_message', 'Data processed successfully!');
        } else {
            return redirect()->back()->with('err_message', 'Data failed to process!');
        }
    }

    public function documentShipment(Request $request)
    {
        $file =  $_FILES;
        $request->validate([
            'document-shipment.*' => 'required|mimes:PDF,pdf|max:5120',
            'ship-book.*' => 'required|mimes:PDF,pdf|max:5120',
        ]);
        if ($request->hasfile('ship-book') && $request->hasFile('document-shipment')) {
            foreach ($request->file('ship-book') as $shipBook) {
                if ($shipBook->isValid()) {
                    if (!file_exists(public_path('potracking/ship_book/' . $request->Number))) {
                        mkdir(public_path('potracking/ship_book/' . $request->Number), 0777, true);
                    }
                    $shipBookName = $request->Number . '_shipBook.pdf';
                    $shipBook->move(public_path('potracking/ship_book/' . $request->Number), $shipBookName);
                }
            }
            foreach ($request->file('document-shipment') as $shipmentDocument) {
                if ($shipmentDocument->isValid()) {
                    $shipmentDocumentName = $request->Number . '_' . str_replace(' ', '_', $shipmentDocument->getClientOriginalName());
                    if (!file_exists(public_path('potracking/document_shipment/' . $request->Number))) {
                        mkdir(public_path('potracking/document_shipment/' . $request->Number), 0777, true);
                    }
                    $shipmentDocument->move(public_path('potracking/document_shipment/' . $request->Number), $shipmentDocumentName);
                }
                $data = [
                    'POID'                      => $request->POID,
                    'BookingDate'               => $request->ship_book_date,
                    'ATDDate'                   => $request->atd,
                    'CopyBLDate'                => Carbon::now()->toDateString(),
                    'CopyBLDocument'            => $shipBookName,
                    'PackingListDocument'       => $shipmentDocumentName,
                    'ETADate'                   => $request->target_eta,
                    'ATADate'                   => $request->target_ata
                ];
                $create = Shipment::insert($data);
            }
            if ($create) {
                $activeStage = array('2a', 2, 3, 4);
                $update = Pdi::where('POID', $request->POID)->whereIn('ActiveStage', $activeStage)
                    ->update([
                        'ActiveStage'   => 5,
                        'ATA'           => $request->target_ata
                    ]);
                if ($update) {
                    return redirect('poimportongoing')->with('suc_message', 'Data processed successfully!');
                } else {
                    return redirect()->back()->with('err_message', 'Data failed to process!');
                }
            }
        } else {
            return redirect()->back()->with('err_message', 'Data not found!');
        }
    }
}
