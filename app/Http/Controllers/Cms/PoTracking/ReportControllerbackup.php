<?php

namespace App\Http\Controllers\Cms\PoTracking;

use Response;
use Carbon\Carbon;
use App\Exports\POTracking\PrPoDownload;
use App\Exports\POTracking\DsGrDownload;
use App\Exports\POTracking\DlGdDownload;
use App\Exports\POTracking\POCancelDownload;
use App\Exports\POTracking\MtFvDownload;
use App\Exports\POTracking\KqcDownload;
use App\Exports\POTracking\DownloadPO;
use App\Exports\POTracking\VnFvDownload;
use App\Exports\POTracking\DjpDownload;
use App\Exports\POTracking\DownloadHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwHistoryall;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwLeadtimePRPO;
use App\Models\View\PoTracking\VwMaterialFavorite;
use App\Models\View\PoTracking\VwVendorFavorite;
use App\Models\View\PoTracking\VwStatusDelivery;
use App\Models\View\PoTracking\VwKunjunganQc;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwLeadtimeDeliveryGR;
use App\Models\Table\PoTracking\ParameterSla;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Po;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\Kurs;
use App\Models\Table\PoTracking\Pdi;
use Illuminate\Support\Facades\DB;
use App\Models\Table\PoTracking\PdiHistory;

use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;

use Exception;
use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\Concerns\ToArray;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('report-mt-fv') == 0) {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function reportPRCreateToPORelease(Request $request)
    {

        if ($this->PermissionActionMenu('report-pr-po')->r == 1 || $this->PermissionActionMenu('report-pr-po')->u == 1 || $this->PermissionActionMenu('report-pr-po')->d == 1 || $this->PermissionActionMenu('report-pr-po')->v == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report PR TO PO',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );
            if ($request->month != null) {
                $request->session()->put('month', $request->month);
            }
            if ($request->years != null) {
                $request->session()->put('years', $request->years);
            }
            if ($request->potype != null) {
                $request->session()->put('potype', $request->potype);
            }
            if ($request->searchby != null) {
                $request->session()->put('searchby', $request->searchby);
            }
            if ($request->prnumber != null) {
                $request->session()->put('prnumber', $request->prnumber);
            }
            if ($request->reset == 1) {
                $request->session()->forget('month');
                $request->session()->forget('years');
                $request->session()->forget('searchby');
                $request->session()->forget('potype');
                $request->session()->forget('prnumber');
                return redirect('report-pr-po');
            }

            if (!empty($request->searchby)) {
                if (!empty($request->searchby == "PR Create")) {
                    $date = 'PRCreateDate';
                } elseif (!empty($request->searchby == "PR Release")) {
                    $date = 'PRReleaseDate';
                } elseif (!empty($request->searchby == "PO Create")) {
                    $date = 'Date';
                } elseif (!empty($request->searchby == "PO Release")) {
                    $date = 'ReleaseDate';
                }

                if ((!empty($request->month)) && (!empty($request->potype)) && (!empty($request->prnumber)) && (!empty($request->years))) {
                    $getData = VwLeadtimePRPO::whereMonth($date, $request->month)->whereYear($date, $request->years)->whereIn('Type', $request->potype)
                        ->whereIn('PRNumber', $request->prnumber)->get();
                } elseif ((!empty($request->potype)) && (!empty($request->prnumber)) && (!empty($request->years))) {
                    $getData = VwLeadtimePRPO::whereYear($date, $request->years)->whereIn('Type', $request->potype)
                        ->whereIn('PRNumber', $request->prnumber)->get();
                } elseif ((!empty($request->month))  && (!empty($request->years)) && (!empty($request->potype))) {
                    $getData = VwLeadtimePRPO::whereMonth($date, $request->month)->whereYear($date, $request->years)->whereIn('Type', $request->potype)->get();
                } elseif ((!empty($request->potype)) && (!empty($request->prnumber))) {
                    $getData = VwLeadtimePRPO::whereIn('Type', $request->potype)->whereIn('PRNumber', $request->prnumber)->get();
                } elseif ((!empty($request->month))  && (!empty($request->years))) {
                    $getData = VwLeadtimePRPO::whereMonth($date, $request->month)->whereYear($date, $request->years)->get();
                } elseif ((!empty($request->years))) {
                    $getData = VwLeadtimePRPO::whereYear($date, $request->years)->get();
                } elseif ((!empty($request->potype))) {
                    $getData = VwLeadtimePRPO::whereIn('Type', $request->potype)->get();
                } elseif ((!empty($request->prnumber))) {
                    $getData = VwLeadtimePRPO::whereIn('PRNumber', $request->prnumber)->get();
                } else {
                    return redirect()->back()->with('err_message', 'Please Select Item!');
                }
                $prnumber = VwLeadtimePRPO::select('PRNumber')->distinct()->get();
                $type = Po::select('Type')->distinct()->get();
                $datasla = ParameterSla::all();
            } else {
                $now = Carbon::now();
                $getData = VwLeadtimePRPO::whereMonth('PRCreateDate', $now->month)->whereYear('PRCreateDate', $now->year)->get();
                $prnumber = VwLeadtimePRPO::select('PRNumber')->distinct()->get();
                $type = Po::select('Type')->distinct()->get();
                $datasla = ParameterSla::all();
            }
            $action_menu = $this->PermissionActionMenu('report-pr-po');
            return view('po-tracking/master/ReportLeadtimePRPO', compact('getData', 'type', 'prnumber', 'datasla', 'action_menu'));
        } else {
            return redirect('potracking')->with('error', 'Access denied!');
        }
    }
    public function reportVendorFavorite(Request $request)
    {

        if ($this->PermissionActionMenu('report-vn-fv')->r == 1 || $this->PermissionActionMenu('report-vn-fv')->u == 1 || $this->PermissionActionMenu('report-vn-fv')->d == 1 || $this->PermissionActionMenu('report-vn-fv')->v == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report Vendor Favorite',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );

            if ($request->datefilter != null) {
                $request->session()->put('datefilter', $request->datefilter);
            }
            if ($request->searchbystatus != null) {
                $request->session()->put('searchbystatus', $request->searchbystatus);
            }
            if ($request->searchbyvendor != null) {
                $request->session()->put('searchbyvendor', $request->searchbyvendor);
            }
            if ($request->searchbytype != null) {
                $request->session()->put('searchbytype', $request->searchbytype);
            }
            if ($request->reset == 1) {
                $request->session()->forget('datefilter');
                $request->session()->forget('searchbystatus');
                $request->session()->forget('searchbytype');
                $request->session()->forget('searchbyvendor');
                return redirect('report-vn-fv');
            }
            if (!empty($request->searchby)) {
                if ($request->datefilter) {
                    $date         = explode(" - ", $request->datefilter);
                    $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                }
                if (!empty($request->searchbystatus == "New PO")) {
                    $status = VwnewpoAll::select('Number')->distinct()->get()->toArray();
                } elseif (!empty($request->searchbystatus == "Ongoing")) {
                    $status = VwongoingAll::select('Number')->distinct()->get()->toArray();
                } elseif (!empty($request->searchbystatus == "History")) {
                    $status = VwHistoryall::select('Number')->distinct()->get()->toArray();
                } else {
                    $status = VwPo::select('Number')->distinct()->get()->toArray();
                }

                if ((!empty($request->datefilter)) && (!empty($request->searchbystatus)) && (!empty($request->searchbyvendor)) && (!empty($request->searchbytype))) {

                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->whereIn('Number', $status)->whereIn('VendorName', $request->searchbyvendor)->where('VendorType', $request->searchbytype)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])->get();
                } elseif ((!empty($request->datefilter)) && (!empty($request->searchbystatus)) && (!empty($request->searchbytype))) {
                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->whereIn('Number', $status)->where('VendorType', $request->searchbytype)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])->get();
                } elseif ((!empty($request->datefilter))  && (!empty($request->searchbystatus))) {
                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->whereIn('Number', $status)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])->get();
                } elseif ((!empty($request->datefilter)) && (!empty($request->searchbytype))) {
                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->where('VendorType', $request->searchbytype)->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])->get();
                } elseif ((!empty($request->searchbystatus)) && (!empty($request->searchbytype))) {
                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->whereIn('Number', $status)->where('VendorType', $request->searchbytype)->get();
                } elseif ((!empty($request->datefilter))) {
                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->whereBetween('vw_vendor_favorite.ReleaseDate', [$awal, $akhir])
                        ->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')->get();
                } elseif ((!empty($request->searchbystatus))) {
                    $getData =  VwVendorFavorite::selectRaw('vw_vendor_favorite.*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity ,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->whereIn('Number', $status)
                        ->get();
                } elseif ((!empty($request->searchbyvendor))) {
                    $getData =  VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->whereIn('VendorName', $request->searchbyvendor)->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->get();
                } elseif ((!empty($request->searchbytype))) {
                    $getData =  VwVendorFavorite::where('VendorType', $request->searchbytype)->selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')->orderBy('Currency', 'DESC')
                        ->get();
                } else {
                    return redirect()->back()->with('err_message', 'Please Select Item!');
                }

                $Vendor = VwVendorFavorite::select('VendorName')->distinct()->get();
                $Kurs = Kurs::all();
            } else {
                $Vendor = VwVendorFavorite::select('VendorName')->distinct()->get();
                $getData = VwVendorFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('VendorCode')->orderBy('TotalPO', 'DESC')
                    ->get();

                $Kurs = Kurs::all();
            }

            $action_menu = $this->PermissionActionMenu('report-mt-fv');
            return view('po-tracking/master/ReportVendorFavorite', compact('getData', 'action_menu', 'Kurs', 'Vendor'));

            return redirect('potracking')->with('error', 'Access denied!');
        }
    }
    public function reportMaterialFavorite(Request $request)
    {

        if ($this->PermissionActionMenu('report-mt-fv')->c == 1 || $this->PermissionActionMenu('report-mt-fv')->r == 1 || $this->PermissionActionMenu('report-mt-fv')->u == 1 || $this->PermissionActionMenu('report-mt-fv')->d == 1 || $this->PermissionActionMenu('report-mt-fv')->v == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report Material Favorite',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );

            if ($request->datefilter != null) {
                $request->session()->put('datefilter', $request->datefilter);
            }
            if ($request->searchbystatus != null) {
                $request->session()->put('searchbystatus', $request->searchbystatus);
            }
            if ($request->searchbymaterial != null) {
                $request->session()->put('searchbymaterial', $request->searchbymaterial);
            }
            if ($request->searchbytype != null) {
                $request->session()->put('searchbytype', $request->searchbytype);
            }
            if ($request->reset == 1) {
                $request->session()->forget('datefilter');
                $request->session()->forget('searchbystatus');
                $request->session()->forget('searchbytype');
                $request->session()->forget('searchbymaterial');
                return redirect('report-mt-fv');
            }

            if (!empty($request->searchby)) {
                if ($request->datefilter) {
                    $date         = explode(" - ", $request->datefilter);
                    $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                }
                if (!empty($request->searchbystatus == "New PO")) {
                    $status = VwnewpoAll::select('Number')->distinct()->get()->toArray();
                } elseif (!empty($request->searchbystatus == "Ongoing")) {
                    $status = VwongoingAll::select('Number')->distinct()->get()->toArray();
                } elseif (!empty($request->searchbystatus == "History")) {
                    $status = VwHistoryall::select('Number')->distinct()->get()->toArray();
                } else {
                    $status = VwPo::select('Number')->distinct()->get()->toArray();
                }
                if ($this->PermissionActionMenu('report-mt-fv')->c == 1) {
                    if ((!empty($request->datefilter)) && (!empty($request->searchbystatus)) && (!empty($request->searchbymaterial)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->whereIn('Material', $request->searchbymaterial)->where('VendorType', $request->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->datefilter)) && (!empty($request->searchbystatus)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->where('VendorType', $request->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->datefilter))  && (!empty($request->searchbystatus))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->datefilter)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->where('VendorType', $request->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->searchbystatus)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->where('VendorType', $request->searchbytype)->get();
                    } elseif ((!empty($request->datefilter))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                            ->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')->get();
                    } elseif ((!empty($request->searchbystatus))) {
                        $getData =  VwMaterialFavorite::selectRaw('vw_material_favorite.*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)
                            ->get();
                    } elseif ((!empty($request->searchbymaterial))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->whereIn('Material', $request->searchbymaterial)->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->get();
                    } elseif ((!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::where('VendorType', $request->searchbytype)->selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->get();
                    } else {
                        return redirect()->back()->with('err_message', 'Please Select Item!');
                    }
                } else {
                    if ((!empty($request->datefilter)) && (!empty($request->searchbystatus)) && (!empty($request->searchbymaterial)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->whereIn('Material', $request->searchbymaterial)->where('VendorType', $request->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->datefilter)) && (!empty($request->searchbystatus)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->where('VendorType', $request->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->datefilter))  && (!empty($request->searchbystatus))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->datefilter)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->where('VendorType', $request->searchbytype)->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])->get();
                    } elseif ((!empty($request->searchbystatus)) && (!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)->where('VendorType', $request->searchbytype)->get();
                    } elseif ((!empty($request->datefilter))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->whereBetween('vw_material_favorite.ReleaseDate', [$awal, $akhir])
                            ->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')->get();
                    } elseif ((!empty($request->searchbystatus))) {
                        $getData =  VwMaterialFavorite::selectRaw('vw_material_favorite.*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->whereIn('Number', $status)
                            ->get();
                    } elseif ((!empty($request->searchbymaterial))) {
                        $getData =  VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->whereIn('Material', $request->searchbymaterial)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->get();
                    } elseif ((!empty($request->searchbytype))) {
                        $getData =  VwMaterialFavorite::where('VendorType', $request->searchbytype)->selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                            ->get();
                    } else {
                        return redirect()->back()->with('err_message', 'Please Select Item!');
                    }
                }

                $Material = VwMaterialFavorite::select('Material')->distinct()->get();
                $Kurs = Kurs::all();
            } else {
                if ($this->PermissionActionMenu('report-mt-fv')->c == 1) {
                    $Material = VwMaterialFavorite::select('Material')->distinct()->where('VendorCode', Auth::user()->email)->get();
                    $getData = VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO')->where('Material', '!=', '')->where('VendorCode', Auth::user()->email)->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                        ->get();
                    $Kurs = Kurs::all();
                } else {
                    $Material = VwMaterialFavorite::whereNotNull('Material')->select('Material')->distinct()->get();
                    $getData = VwMaterialFavorite::selectRaw('*, sum(TotalAmount) as TotalAmount,sum(TotalQuantity) as TotalQuantity,sum(TotalPO) as TotalPO',)->where('Material', '!=', '')->groupBy('Material', 'Currency')->orderBy('TotalPO', 'DESC')
                        ->get();
                    $Kurs = Kurs::all();
                }
            }

            $action_menu = $this->PermissionActionMenu('report-mt-fv');
            return view('po-tracking/master/ReportMaterialFavorite', compact('getData', 'action_menu', 'Kurs', 'Material'));

            return redirect('potracking')->with('error', 'Access denied!');
        }
    }

    public function reportPOCancel(Request $request)
    {

        if ($this->PermissionActionMenu('report-po-cancel')->r == 1 || $this->PermissionActionMenu('report-po-cancel')->u == 1 ||   $this->PermissionActionMenu('report-po-cancel')->d == 1 || $this->PermissionActionMenu('report-po-cancel')->v == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report PO Cancel',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );
            if ($request->month != null) {
                $request->session()->put('month', $request->month);
            }
            if ($request->years != null) {
                $request->session()->put('years', $request->years);
            }
            if ($request->cancelby != null) {
                $request->session()->put('cancelby', $request->cancelby);
            }
            if ($request->searchby != null) {
                $request->session()->put('searchby', $request->searchby);
            }
            if ($request->ponumber != null) {
                $request->session()->put('ponumber', $request->ponumber);
            }
            if ($request->reset == 1) {
                $request->session()->forget('month');
                $request->session()->forget('years');
                $request->session()->forget('searchby');
                $request->session()->forget('cancelby');
                $request->session()->forget('ponumber');
                return redirect('report-po-cancel');
            }

            if (!empty($request->searchby)) {
                if (!empty($request->searchby == "Delivery Date")) {
                    $date = 'purchasingdocumentitem.DeliveryDate';
                } elseif (!empty($request->searchby == "PO Date")) {
                    $date = 'po.Date';
                } elseif (!empty($request->searchby == "PO ReleaseDate")) {
                    $date = 'po.ReleaseDate';
                }
                if (!empty($request->cancelby == "SAP")) {
                    $cancel = 'L';
                } elseif (!empty($request->cancelby == "PO Tracking")) {
                    $cancel = 'C';
                }

                if ((!empty($request->month)) && (!empty($request->cancelby)) && (!empty($request->ponumber)) && (!empty($request->years))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->whereIn('Number', $request->ponumber)->where('IsClosed', $cancel)->whereMonth($date, $request->month)->whereYear($date, $request->years)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->cancelby)) && (!empty($request->ponumber)) && (!empty($request->years))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->whereIn('Number', $request->ponumber)->where('IsClosed', $cancel)->whereYear($date, $request->years)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->month))  && (!empty($request->years)) && (!empty($request->cancelby))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->where('IsClosed', $cancel)->whereMonth($date, $request->month)->whereYear($date, $request->years)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->cancelby)) && (!empty($request->ponumber))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->whereIn('Number', $request->ponumber)->where('IsClosed', $cancel)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->month))  && (!empty($request->years))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->whereIn('IsClosed', ['C', 'L'])->whereMonth($date, $request->month)->whereYear($date, $request->years)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->cancelby))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->where('IsClosed', $cancel)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->ponumber))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->whereIn('IsClosed', ['C', 'L'])->whereIn('Number', $request->ponumber)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } elseif ((!empty($request->years))) {
                    $getData =  Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                        ->select(
                            'po.Number',
                            'po.Date',
                            'po.ReleaseDate',
                            'po.Type',
                            'po.CreatedBy',
                            'uservendors.Name',
                            'uservendors.VendorCode',
                            'purchasingdocumentitem.ItemNumber',
                            'purchasingdocumentitem.Material',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.Description',
                            'purchasingdocumentitem.DeliveryDate',
                            'purchasingdocumentitem.Quantity',
                            'purchasingdocumentitem.GoodsReceiptQuantity',
                            'purchasingdocumentitem.IsClosed',
                            'purchasingdocumentitem.GoodsReceiptDate'
                        )->whereIn('IsClosed', ['C', 'L'])->whereYear($date, $request->years)->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
                } else {
                    return redirect()->back()->with('err_message', 'Please Select Item!');
                }
                $ponumber = Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                    ->select('po.Number')->whereIn('IsClosed', ['C', 'L'])->groupBy('po.Number')->get();
                $type = Po::select('Type')->distinct()->get();
            } else {
                $ponumber = Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                    ->select('po.Number')->whereIn('IsClosed', ['C', 'L'])->groupBy('po.Number')->get();

                $getData = Pdi::leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                    ->select(
                        'po.Number',
                        'po.Date',
                        'po.ReleaseDate',
                        'po.Type',
                        'po.CreatedBy',
                        'uservendors.Name',
                        'uservendors.VendorCode',
                        'purchasingdocumentitem.ItemNumber',
                        'purchasingdocumentitem.Material',
                        'purchasingdocumentitem.Description',
                        'purchasingdocumentitem.Description',
                        'purchasingdocumentitem.DeliveryDate',
                        'purchasingdocumentitem.Quantity',
                        'purchasingdocumentitem.GoodsReceiptQuantity',
                        'purchasingdocumentitem.IsClosed',
                        'purchasingdocumentitem.GoodsReceiptDate'
                    )->whereIn('IsClosed', ['C', 'L'])->groupBy('po.Number', 'purchasingdocumentitem.ItemNumber')->get();
            }

            $action_menu = $this->PermissionActionMenu('report-po-cancel');
            return view('po-tracking/master/ReportPoCancel', compact('getData', 'action_menu', 'ponumber'));
        } else {
            return redirect('potracking')->with('error', 'Access denied!');
        }
    }
    public function reportDeliveryDateToGRDate(Request $request)
    {
        if ($this->PermissionActionMenu('report-delivery-gr')->r == 1 || $this->PermissionActionMenu('report-delivery-gr')->u == 1 ||   $this->PermissionActionMenu('report-delivery-gr')->d == 1 || $this->PermissionActionMenu('report-delivery-gr')->v == 1 || $this->PermissionActionMenu('report-delivery-gr')->c == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report Delivery TO GR',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );

            if ($request->years != null) {
                $request->session()->put('years', $request->years);
            }
            if ($request->month != null) {
                $request->session()->put('month', $request->month);
            }
            if ($request->statusdelv != null) {
                $request->session()->put('statusdelv', $request->statusdelv);
            }
            if ($request->vendor != null) {
                $request->session()->put('vendor', $request->vendor);
            }

            if ($request->vendortype != null) {
                $request->session()->put('vendortype', $request->vendortype);
            }

            if ($request->ponumber != null) {
                $numbers =  $request->session()->put('ponumber', $request->ponumber);
            }

            if ($request->reset == 1) {
                $request->session()->forget('month');
                $request->session()->forget('years');
                $request->session()->forget('vendor');
                $request->session()->forget('ponumber');
                $request->session()->forget('statusdelv');
                $request->session()->forget('vendortype');
                return redirect('report-delivery-gr');
            }

            if ($request->action == 1) {
                $date = 'DeliveryDate';
                if (!empty($request->statusdelv == "Fullfill-Ontime")) {
                    $statusdev = 'Ontime';
                    $statusrec = 'Fullfill';
                } elseif (!empty($request->statusdelv == "Fullfill-Early")) {
                    $statusdev = 'Early';
                    $statusrec = 'Fullfill';
                } elseif (!empty($request->statusdelv == "Fullfill-Late")) {
                    $statusdev = 'Late';
                    $statusrec = 'Fullfill';
                } elseif (!empty($request->statusdelv == "Partial-Early")) {
                    $statusdev = 'Early';
                    $statusrec = 'Partial';
                } elseif (!empty($request->statusdelv == "Partial-Late")) {
                    $statusdev = 'Late';
                    $statusrec = 'Partial';
                }
                $Data = VwLeadtimeDeliveryGR::groupBy('Number', 'ItemNumber')->orderBy('GoodsReceiptDate', 'DESC')
                    ->orderBy('Number', 'ASC')
                    ->orderBy('ItemNumber', 'ASC');

                if ($this->PermissionActionMenu('report-mt-fv')->c == 1) {
                    if ($request->month != null  && $request->statusdelv != null && $request->ponumber != null && $request->years != null) {
                        $getData = VwLeadtimeDeliveryGR::whereMonth($date, $request->month)->whereYear($date, $request->years)->whereIn('Number', $request->ponumber)
                            ->where('StatusDelivery', $statusdev)->where('StatusReceive', $statusrec)->where('VendorCode', Auth::user()->email)->get();
                    } elseif ($request->month != null && $request->years != null && $request->statusdelv != null) {
                        $getData = VwLeadtimeDeliveryGR::whereMonth($date, $request->month)->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->where('VendorCode', Auth::user()->email)->get();
                    } elseif ($request->month != null && $request->years != null && $request->statusdelv != null) {
                        $getData = VwLeadtimeDeliveryGR::whereMonth($date, $request->month)->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->where('VendorCode', Auth::user()->email)->get();
                    } elseif ($request->month != null && $request->years != null) {
                        $getData = VwLeadtimeDeliveryGR::whereMonth($date, $request->month)->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->where('VendorCode', Auth::user()->email)->get();
                    } elseif ($request->month != null  && $request->years != null) {
                        $getData = VwLeadtimeDeliveryGR::whereMonth($date, $request->month)->whereYear($date, $request->years)->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')
                            ->orderBy('GoodsReceiptDate', 'DESC')->get();
                    } elseif ($request->statusdelv != null) {
                        $getData = VwLeadtimeDeliveryGR::where('StatusDelivery', $statusdev)->where('StatusReceive', $statusrec)->where('VendorCode', Auth::user()->email)->get();
                    } elseif ($request->years != null) {
                        $getData = VwLeadtimeDeliveryGR::whereYear($date, $request->years)->where('VendorCode', Auth::user()->email)->groupBy('Number', 'ItemNumber')
                            ->orderBy('GoodsReceiptDate', 'DESC')->get();
                    } elseif ($request->ponumber != null) {
                        $getData = VwLeadtimeDeliveryGR::whereIn('Number', $request->ponumber)->where('VendorCode', Auth::user()->email)->get();
                    } else {
                        return redirect()->back()->with('err_message', 'Please Select Item!');
                    }
                    $ponumber = VwLeadtimeDeliveryGR::select('Number')->distinct()->where('VendorCode', Auth::user()->email)->get();
                } else {
                    if ($request->month != null && $request->statusdelv != null && $request->ponumber != null && $request->years != null && $request->vendor != null && $request->vendortype != null) {
                        $getData = $Data->whereMonth($date, $request->month)->whereYear($date, $request->years)->whereIn('Number', $request->ponumber)
                            ->where('StatusDelivery', $statusdev)->where('StatusReceive', $statusrec)->where('VendorName', $request->vendor)->where('VendorType', $request->vendortype)->get();
                    } elseif ($request->month != null && $request->years != null && $request->statusdelv != null && $request->vendortype != null) {
                        $getData = $Data->whereMonth($date, $request->month)->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->where('VendorType', $request->vendortype)->get();
                    } elseif ($request->month != null && $request->years != null && $request->statusdelv != null) {
                        $getData = $Data->whereMonth($date, $request->month)->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->get();
                    } elseif ($request->month != null && $request->years != null && $request->vendortype != null) {
                        $getData = $Data->whereMonth($date, $request->month)->whereYear($date, $request->years)->where('VendorType', $request->vendortype)->get();
                    } elseif ($request->years != null && $request->statusdelv != null && $request->vendortype != null) {
                        $getData = $Data->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->where('VendorType', $request->vendortype)->get();
                    } elseif ($request->years != null && $request->statusdelv != null) {
                        $getData = $Data->whereYear($date, $request->years)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->get();
                    } elseif ($request->years != null && $request->vendortype != null) {
                        $getData = $Data->whereYear($date, $request->years)->where('VendorType', $request->vendortype)->get();
                    } elseif ($request->month != null  && $request->years != null) {
                        $getData = $Data->whereMonth($date, $request->month)->whereYear($date, $request->years)->get();
                    } elseif ($request->vendor != null  && $request->statusdelv != null) {
                        $getData = $Data->whereIn('VendorName', $request->vendor)->where('StatusDelivery', $statusdev)
                            ->where('StatusReceive', $statusrec)->get();
                    } elseif ($request->statusdelv != null) {
                        $getData = $Data->where('StatusDelivery', $statusdev)->where('StatusReceive', $statusrec)->get();
                    } elseif ($request->years != null) {
                        $getData = $Data->whereYear($date, $request->years)->get();
                    } elseif ($request->ponumber != null) {
                        $getData = $Data->whereIn('Number', $request->ponumber)->get();
                    } elseif ($request->vendor != null) {
                        $getData = $Data->whereIn('VendorName', $request->vendor)->get();
                    } elseif ($request->vendortype != null) {
                        $getData = $Data->where('VendorType', $request->vendortype)->get();
                    } else {
                        return redirect()->back()->with('err_message', 'Please Select Item!');
                    }
                    $ponumber = VwLeadtimeDeliveryGR::select('Number')->distinct()->get();
                }
            } else {

                if ($this->PermissionActionMenu('report-mt-fv')->c == 1) {
                    $ponumber = VwLeadtimeDeliveryGR::select('Number')->distinct()->where('VendorCode', Auth::user()->email)->get();
                    $getData = VwLeadtimeDeliveryGR::where('VendorCode', Auth::user()->email)
                        ->whereNotNull('Number')
                        ->groupBy('Number', 'ItemNumber')
                        ->orderBy('GoodsReceiptDate', 'DESC')
                        ->orderBy('Number', 'ASC')
                        ->orderBy('ItemNumber', 'ASC')
                        ->get();
                } else {
                    $ponumber = VwLeadtimeDeliveryGR::select('Number')->distinct()->get();
                    $getData = VwLeadtimeDeliveryGR::whereNotNull('Number')
                        ->groupBy('Number', 'ItemNumber')
                        ->orderBy('GoodsReceiptDate', 'DESC')
                        ->orderBy('Number', 'ASC')
                        ->orderBy('ItemNumber', 'ASC')
                        ->paginate(100);
                }
            }

            $datavendor = UserVendor::select('Name')->get();
            $datavendortype = UserVendor::select('VendorType')->distinct()->get();

            $action_menu = $this->PermissionActionMenu('report-delivery-gr');
            // dd($getData->toArray());
            return view('po-tracking/master/reportLeadtimeDeliveryGR', compact('getData', 'ponumber', 'action_menu', 'datavendor', 'datavendortype'));
        } else {
            return redirect('potracking')->with('error', 'Access denied!');
        }
    }

    public function reportKunjunganQC(Request $request)
    {
        if ($this->PermissionActionMenu('report-kunjungan-qc')->r == 1 || $this->PermissionActionMenu('report-kunjungan-qc')->u == 1 || $this->PermissionActionMenu('report-kunjungan-qc')->d == 1 || $this->PermissionActionMenu('report-kunjungan-qc')->v == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report Kunjungan QC',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );
            if (!empty($request->searchby == "Planning QC")) {
                $dates = 'DeliveryDate';
            } elseif (!empty($request->searchby == "Delivery Date")) {
                $dates = 'PlanningQCDate';
            } else {
                $dates = 'req_date';
            }
            if ($request->vendor != null) {
                $request->session()->put('vendor', $request->vendor);
            }
            if ($request->number != null) {
                $request->session()->put('number', $request->number);
            }
            if ($request->material != null) {
                $request->session()->put('material', $request->material);
            }
            if ($request->searchby != null) {
                $request->session()->put('searchby', $request->searchby);
            }
            if ($request->datefilter != null) {
                $request->session()->put('datefilter', $request->datefilter);
            }
            if ($request->reset == 1) {
                $request->session()->forget('vendor');
                $request->session()->forget('number');
                $request->session()->forget('material');
                $request->session()->forget('searchby');
                $request->session()->forget('datefilter');
                return redirect('report-kunjungan-qc');
            }
            if (!empty($request->searchby)) {
                if ($request->datefilter) {
                    $date         = explode(" - ", $request->datefilter);
                    $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                }

                if ((!empty($request->datefilter)) && (!empty($request->vendor))) {
                    $data =  VwKunjunganQc::whereIn('Vendor', $request->vendor)->whereBetween("vw_kunjungan_qc.$dates", [$awal, $akhir])->orderBy('PlanningQCDate', 'DESC')->get();
                } elseif ((!empty($request->datefilter))) {
                    $data =  VwKunjunganQc::whereBetween("vw_kunjungan_qc.$dates", [$awal, $akhir])->orderBy('PlanningQCDate', 'DESC')->get();
                } elseif ((!empty($request->vendor))) {
                    $data =  VwKunjunganQc::whereIn('Vendor', $request->vendor)->orderBy('PlanningQCDate', 'DESC')->get();
                } elseif ((!empty($request->number))) {
                    $data =  VwKunjunganQc::whereIn('Number', $request->number)->orderBy('PlanningQCDate', 'DESC')->get();
                } elseif ((!empty($request->material))) {
                    $data =  VwKunjunganQc::whereIn('Material', $request->material)->orderBy('PlanningQCDate', 'DESC')->get();
                } else {
                    return redirect()->back()->with('err_message', 'Please Select Item!');
                }
                $vendor = VwKunjunganQc::groupBy('Vendor')->get();
                $number = VwKunjunganQc::groupBy('Number')->get();
                $material = VwKunjunganQc::groupBy('Material')->get();
            } else {

                $data = VwKunjunganQc::orderBy('PlanningQCDate', 'DESC')->get();
                $vendor = VwKunjunganQc::groupBy('Vendor')->get();
                $number = VwKunjunganQc::groupBy('Number')->get();
                $material = VwKunjunganQc::groupBy('Material')->get();
            }

            $material_qc = VwKunjunganQc::select('Material')->distinct()->get()->toArray();

            /* Req Date CCR */
            $ccr_reqdate = VwPoTrackingReqDateMaterial::whereIn('material', $material_qc)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
            foreach ($data as $a) {
                foreach ($ccr_reqdate as $b) {
                    if ($a->Material == $b->material) {
                        $a->setAttribute('req_date', $b->req_date);
                        break;
                    } else {
                        continue;
                    }
                }
            }
            /* END of Req Date CCR */

            // dd($getData->toArray());

            $action_menu = $this->PermissionActionMenu('report-kunjungan-qc');
            // dd($getData->toArray());
            return view('po-tracking/master/reportKunjunganQC', compact('data', 'action_menu', 'vendor', 'number', 'material'));
        } else {
            return redirect('potracking')->with('error', 'Access denied!');
        }
    }
    public function JadwalPengiriman(Request $request)
    {

        try {
            $kodex = ['A', 'D', 'S', 'W', 'Q', 'R', 'X'];
            if ($this->PermissionActionMenu('jadwalpengiriman')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate(
                    [
                        'user'  => Auth::user()->email,
                        'menu'  => 'Report',
                        'description' => 'Report Jadwal Pengiriman',
                        'date'  => $date->toDateString(),
                        'ponumber' => NULL,
                        'poitem' => NULL,
                        'userlogintype' => Auth::user()->title,
                        'vendortype' => NULL,
                        'CreatedBy'  => Auth::user()->name,
                    ],
                    [
                        'time'     => $date->toTimeString()
                    ]
                );

                if ($request->datefilter != null) {
                    $request->session()->put('datefilter', $request->datefilter);
                }
                if ($request->reset == 1) {

                    $request->session()->forget('datefilter');
                    return redirect('jadwalpengiriman');
                }
                if (!empty($request->datefilter)) {
                    if ($request->datefilter) {
                        $date         = explode(" - ", $request->datefilter);
                        $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                        $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    }
                    if ((!empty($request->datefilter))) {
                        $datapengiriman = VwViewTicket::whereIn('status', $kodex)->where('DeliveryDate', '>', $date)->whereBetween("DeliveryDate", [$awal, $akhir])->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', 'asc')->get();
                        $datahistorypengiriman = VwViewTicket::whereIn('status', $kodex)->where('DeliveryDate', '<', $date)->whereBetween("DeliveryDate", [$awal, $akhir])->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', 'asc')->get();
                    } else {
                        return redirect()->back()->with('err_message', 'Please Select Item!');
                    }
                } else {

                    $datapengiriman = VwViewTicket::whereIn('status', $kodex)->where('DeliveryDate', '>', $date)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', 'asc')->get();
                    $datahistorypengiriman = VwViewTicket::whereIn('status', $kodex)->where('DeliveryDate', '<', $date)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', 'asc')->get();
                }
                // dd($getData->toArray());
                $action_menu = $this->PermissionActionMenu('jadwalpengiriman');
                // dd($getData->toArray());
                return view('po-tracking/master/reportJadwalPengiriman', compact('datapengiriman', 'datahistorypengiriman', 'action_menu'));
            } else {
                return redirect('potracking')->with('error', 'Access denied!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function reportDeliveryGood(Request $request)
    {
        if ($this->PermissionActionMenu('report-dl-gd')->r == 1 || $this->PermissionActionMenu('report-dl-gd')->u == 1 || $this->PermissionActionMenu('report-dl-gd')->d == 1 || $this->PermissionActionMenu('report-dl-gd')->v == 1) {
            $date   = Carbon::now();
            LogHistory::updateOrCreate(
                [
                    'user'  => Auth::user()->email,
                    'menu'  => 'Report',
                    'description' => 'Report Delivery Good',
                    'date'  => $date->toDateString(),
                    'ponumber' => NULL,
                    'poitem' => NULL,
                    'userlogintype' => Auth::user()->title,
                    'vendortype' => NULL,
                    'CreatedBy'  => Auth::user()->name,
                ],
                [
                    'time'     => $date->toTimeString()
                ]
            );
            if ($request->searchbyvendor != null) {
                $request->session()->put('searchbyvendor', $request->searchbyvendor);
            }
            if ($request->searchbytype != null) {
                $request->session()->put('searchbytype', $request->searchbytype);
            }
            if ($request->reset == 1) {
                $request->session()->forget('searchbyvendor');
                $request->session()->forget('searchbytype');
                return redirect('report-dl-gd');
            }
            if (!empty($request->searchbyvendor) || !empty($request->searchbytype)) {
                if ((!empty($request->searchbyvendor))  && (!empty($request->searchbytype))) {
                    $terburuk = VwStatusDelivery::whereIn('VendorName', $request->searchbyvendor)->where('VendorType', $request->searchbytype)->orderBy('performance', 'ASC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                    $terbaik = VwStatusDelivery::whereIn('VendorName', $request->searchbyvendor)->where('VendorType', $request->searchbytype)->orderBy('performance', 'DESC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                } elseif ((!empty($request->searchbyvendor))) {
                    $terburuk = VwStatusDelivery::whereIn('VendorName', $request->searchbyvendor)->orderBy('performance', 'ASC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                    $terbaik = VwStatusDelivery::whereIn('VendorName', $request->searchbyvendor)->orderBy('performance', 'DESC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                } elseif ((!empty($request->searchbytype))) {
                    $terburuk = VwStatusDelivery::where('VendorType', $request->searchbytype)->orderBy('performance', 'ASC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                    $terbaik = VwStatusDelivery::where('VendorType', $request->searchbytype)->orderBy('performance', 'DESC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                }
                $VendorName = VwStatusDelivery::select('VendorName')->distinct()->get();
            } else {

                $VendorName = VwStatusDelivery::select('VendorName')->distinct()->get();
                $terburuk = VwStatusDelivery::whereNotNull('VendorName')->orderBy('performance', 'ASC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
                $terbaik = VwStatusDelivery::orderBy('performance', 'DESC')->orderBy('totalStatusDelivery', 'DESC')->limit(10)->get();
            }

            // dd($getData->toArray());
            $action_menu = $this->PermissionActionMenu('report-dl-gd');
            // dd($getData->toArray());
            return view('po-tracking/master/reportStatusDelivery', compact('terbaik', 'terburuk', 'VendorName', 'action_menu'));
        } else {
            return redirect('potracking')->with('error', 'Access denied!');
        }
    }
    public function pocanceldownload(Request $request)
    {
        try {
            $month =  $request->session()->get('month');
            $years = $request->session()->get('years');
            $datefilter = $request->session()->get('searchby');
            $cancelby = $request->session()->get('cancelby');
            $ponumber = $request->session()->get('ponumber');

            return Excel::download(new PoCancelDownload($month, $years, $datefilter, $cancelby, $ponumber), 'PO Cancel.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function prpoDownload(Request $request)
    {
        try {
            $month =  $request->session()->get('month');
            $years = $request->session()->get('years');
            $datefilter = $request->session()->get('searchby');
            $potype = $request->session()->get('potype');
            $prnumber = $request->session()->get('prnumber');

            return Excel::download(new PrPoDownload($month, $years, $datefilter, $potype, $prnumber), 'PRTOPORELEASE.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function DownloadFilePo($id, $status)
    {

        try {

            return Excel::download(new DownloadPO($id, $status), 'DataPO.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }


    public function dsgrDownload(Request $request)
    {
        try {
            $month =  $request->session()->get('month');
            $years = $request->session()->get('years');
            $vendor = $request->session()->get('vendor');
            $vendortype = $request->session()->get('vendortype');
            $statusdelv = $request->session()->get('statusdelv');
            $ponumber = $request->session()->get('ponumber');

            return Excel::download(new DsGrDownload($month, $years, $vendor, $vendortype, $statusdelv, $ponumber), 'DELIVERYTOGR.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function dlgdDownload(Request $request)
    {
        try {

            $searchbyvendor = $request->session()->get('searchbyvendor');
            $searchbytype = $request->session()->get('searchbytype');
            $status = $request->status;

            return Excel::download(new DlGdDownload($searchbytype, $searchbyvendor, $status), 'Status Delivery.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function vnfvdownload(Request $request)
    {
        try {

            $datefilter = $request->session()->get('datefilter');
            $searchbystatus = $request->session()->get('searchbystatus');
            $searchbytype = $request->session()->get('searchbytype');
            $searchbyvendor = $request->session()->get('searchbyvendor');

            return Excel::download(new VnFvDownload($datefilter, $searchbystatus, $searchbytype, $searchbyvendor), 'VendorFavorite.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function mtfvdownload(Request $request)
    {
        try {

            $datefilter = $request->session()->get('datefilter');
            $searchbystatus = $request->session()->get('searchbystatus');
            $searchbytype = $request->session()->get('searchbytype');
            $searchbymaterial = $request->session()->get('searchbymaterial');
            $searchbymaterial = $request->session()->get('searchbymaterial');

            return Excel::download(new MtFvDownload($datefilter, $searchbystatus, $searchbytype, $searchbymaterial), 'MaterialFavorite.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function kqcdownload(Request $request)
    {
        try {

            $datefilter = $request->session()->get('datefilter');
            $material = $request->session()->get('material');
            $vendor = $request->session()->get('vendor');
            $number = $request->session()->get('number');
            $searchby = $request->session()->get('searchby');

            return Excel::download(new KqcDownload($datefilter, $material, $vendor, $number, $searchby), 'Kunjungan QC.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function DownloadJadwalPengiriman(Request $request)
    {
        try {

            $datefilter = $request->session()->get('datefilter');

            return Excel::download(new DjpDownload($datefilter,), 'Report Jadwal Pengiriman.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function loghistorydownload($id)
    {

        try {

            return Excel::download(new DownloadHistory($id), 'DataHistoryUser.xlsx');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function view_detaildsgr(Request $request)
    {

        $data    = VwLeadtimeDeliveryGR::where('Number', $request->number)->where('ItemNumber', $request->item)->orderBy('GoodsReceiptDate', 'desc')->get();

        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }
    public function caridetailmaterial(Request $request)
    {
        if ($this->PermissionActionMenu('report-mt-fv')->c == 1) {
            $data    = VwPo::selectRaw('*, sum(DISTINCT Quantity) as TotalQuantity')->where('Material', $request->id)->where('Currency', $request->currency)->where('VendorCode', Auth::user()->email)->whereNotNull('ReleaseDate')->groupBy('Material', 'Currency', 'Number')->get();
        } else {
            $data    = VwPo::selectRaw('*, sum(DISTINCT Quantity) as TotalQuantity')->where('Material', $request->id)->where('Currency', $request->currency)->whereNotNull('ReleaseDate')->groupBy('Material', 'Currency', 'Number')->get();
        }
        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }
    public function caridetailvendor(Request $request)

    {
        $data    = VwVendorFavorite::where('VendorCode', $request->id)->groupBy('VendorCode', 'Material', 'Number', 'Currency')->get();

        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }


    public function caridetailpo(Request $request)
    {
        $data    = VwPo::where('Number', $request->id)->first();
        $dataall    = VwPo::where('Number', $request->id)->groupBy('Number', 'ItemNumber')->get();
        $data = array(
            'data'        => $data,
            'dataall'        => $dataall,
        );
        echo json_encode($data);
    }
    public function caridetailpr(Request $request)
    {
        $dataall    = VwPo::where('PRNumber', $request->id)->groupBy('Number', 'ItemNumber')->get();
        $data    = VwPo::where('PRNumber', $request->id)->first();
        $data = array(
            'data'        => $data,
            'dataall'        => $dataall,
        );
        echo json_encode($data);
    }
}
