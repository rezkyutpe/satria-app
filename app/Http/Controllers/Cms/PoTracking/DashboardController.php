<?php

namespace App\Http\Controllers\Cms\PoTracking;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\Comments;
use App\Models\Table\PoTracking\SubcontLeadtimeMaster;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\Po;
use App\Models\Table\PoTracking\ParkingInvoice;
use App\Models\Table\PoTracking\ParkingInvoiceDocument;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\Table\PoTracking\MigrationProcurementPO;
use App\Models\Table\PoTracking\ParkingInvoiceSPBNumber;
use App\Models\Table\PoTracking\ParkingInvoiceLog;
use App\Models\Table\PoTracking\WithholdingTaxCode;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\VwUserRoleGroup;
use Exception;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwHistoryall;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\CompletenessComponent\VwPoTrackingReqDateMaterial;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\UserVendorWTHTax;

use Illuminate\Support\Facades\Http;

use PDF;
use DateTime;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('potracking') == 0) {
                return redirect('/')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }
    // cARIPO
    public function CariPO(Request $request)
    {
        $cek1 = VwnewpoAll::where('Number', $request->Number)->first();
        $cek2 = VwongoingAll::where('Number', $request->Number)->first();
        $cek3 = VwHistoryall::where('Number', $request->Number)->first();
        $actionmenu =  $this->PermissionActionMenu('potracking');
        if ($request->Number != null) {
            $request->session()->put('Number', $request->Number);
        }

        if ($cek1 != NULL && $cek1->VendorType == 'Vendor Local') {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $link = 'polocalnewpo-vendor';
            } else if ($actionmenu->group == 23) {
                $link = 'polocalnewpo-proc';
            } else if ($actionmenu->group == 30) {
                $link = 'polocalnewpo-whs';
            } else if ($actionmenu->group == 31) {
                $link = 'polocalnewpo-nonmanagement';
            } else {
                $link = 'polocalnewpo';
            }
        } else if ($cek1 != NULL && $cek1->VendorType == 'Vendor SubCont') {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $link = 'subcontractornewpo-vendor';
            } else if ($actionmenu->group == 23) {
                $link = 'subcontractornewpo-proc';
            } else if ($actionmenu->group == 30) {
                $link = 'subcontractornewpo-whs';
            } else if ($actionmenu->group == 31) {
                $link = 'subcontractornewpo-nonmanagement';
            } else {
                $link = 'subcontractornewpo';
            }
        } else if ($cek2 != NULL && $cek2->VendorType == 'Vendor Local') {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $link = 'polocalongoing-vendor';
            } else if ($actionmenu->group == 23) {
                $link = 'polocalongoing-proc';
            } else if ($actionmenu->group == 30) {
                $link = 'polocalongoing-whs';
            } else if ($actionmenu->group == 31) {
                $link = 'polocalongoing-nonmanagement';
            } else {
                $link = 'polocalongoing';
            }
        } else if ($cek2 != NULL && $cek2->VendorType == 'Vendor SubCont') {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $link = 'subcontractorongoing-vendor';
            } else if ($actionmenu->group == 23) {
                $link = 'subcontractorongoing-proc';
            } else if ($actionmenu->group == 30) {
                $link = 'subcontractorongoing-whs';
            } else if ($actionmenu->group == 31) {
                $link = 'subcontractorongoing-nonmanagement';
            } else {
                $link = 'subcontractorongoing';
            }
        } else if ($cek3 != NULL && $cek3->VendorType == 'Vendor Local') {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $link = 'polocalhistory-vendor';
            } else if ($actionmenu->group == 23) {
                $link = 'polocalhistory-proc';
            } else if ($actionmenu->group == 30) {
                $link = 'polocalhistory-whs';
            } else if ($actionmenu->group == 31) {
                $link = 'polocalhistory-nonmanagement';
            } else {
                $link = 'polocalhistory';
            }
        } else if ($cek3 != NULL && $cek3->VendorType == 'Vendor SubCont') {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $link = 'subcontractorhistory-vendor';
            } else if ($actionmenu->group == 23) {
                $link = 'subcontractorhistory-proc';
            } else if ($actionmenu->group == 30) {
                $link = 'subcontractorhistory-whs';
            } else if ($actionmenu->group == 31) {
                $link = 'subcontractorhistory-nonmanagement';
            } else {
                $link = 'subcontractorhistory';
            }
        } else {
            return redirect()->back()->with('err_message', 'PO Tidak Ada!');
        }
        return redirect($link);
    }
    // PO All- View gr
    public function view_detailgr(Request $request)
    {
        $data    = VwongoingAll::where('POID', $request->number)->where('ItemNumber', $request->item)->groupby('DocumentNumber', 'DocumentNumberItem')->orderBy('RefDocumentNumber', 'ASC')->orderBy('GoodsReceiptDate', 'ASC')->get();
        $data = array(
            'data'        => $data,
        );
        echo json_encode($data);
    }

    // PO All- View gr History
    public function view_detailgrhistory(Request $request)
    {
        $dataHistory    = VwHistoryall::where('POID', $request->number)->where('ItemNumber', $request->item)->groupby('DocumentNumber', 'DocumentNumberItem')->orderBy('RefDocumentNumber', 'ASC')->orderBy('GoodsReceiptDate', 'ASC')->get();
        $data = array(
            'data'        => $dataHistory,
        );
        echo json_encode($data);
    }

    //viewcariparking
    public function viewcariparking(Request $request)
    {
        $mulai = Carbon::now();
        $dataHeaderPO = Po::where('Number', $request->number)->first();
        $dataVendor = UserVendor::where('VendorCode', $dataHeaderPO->VendorCode)->first();
        $data_wth_vendor = UserVendorWTHTax::where('VendorCode', $dataVendor->VendorCode_new)->get();
        $data_taxcode = WithholdingTaxCode::all();

        //DATA ONGOING
        $dataOngoing = Pdi::where('POID', $dataHeaderPO->ID)
            ->whereIn('MovementType', ['101', '105','109'])
            ->distinct()
            ->orderBy('ItemNumber')
            ->get();
        foreach($dataOngoing as $baris){
            if($baris->RefDocumentNumber == null){
                $getspb = Pdi::where('DocumentNumberRef',$baris->DocumentNumberRef)->whereNotNull('RefDocumentNumber')->first();
                if($getspb != null){
                    $baris->setAttribute('RefDocumentNumber', $getspb->RefDocumentNumber);
                }
            }
        }
        $nomorSPB = array_unique(array_column($dataOngoing->toArray(),'RefDocumentNumber'));
        
        //CEK YANG REVERSAL
        $getPDI_reversal = Pdi::where('POID',$dataHeaderPO->ID)->whereIn('MovementType', ['102', '122', '106','110'])->distinct()->get();
        if(count($getPDI_reversal) > 0 ){
            foreach($getPDI_reversal as $pdi){
                if($pdi->RefDocumentNumber == null){
                    $getspb = Pdi::where('DocumentNumberRef',$pdi->DocumentNumberRef)->whereNotNull('RefDocumentNumber')->first();
                    $pdi->setAttribute('RefocumentNumber', $getspb->RefDocumentNumber);
                }
                $arr_spb_reversal[] = [
                    'ItemNumber' => $pdi->ItemNumber,
                    'RefDocumentNumber' => $pdi->RefDocumentNumber,
                    'GoodsReceiptQuantity' => $pdi->GoodsReceiptQuantity
                ];
            }
            foreach ($dataOngoing as $data) {
                foreach ($arr_spb_reversal as $key => $data2) {
                    if (($data->RefDocumentNumber == $data2['RefDocumentNumber']) && $data->ItemNumber == $data2['ItemNumber'] && ($data->MovementType == '105' || $data->MovementType == '101' || $data->MovementType == '109')) {
                        $hasilkurang = $data->GoodsReceiptQuantity - $data2['GoodsReceiptQuantity'];
                        $data->setAttribute('GoodsReceiptQuantity', $hasilkurang);
                        unset($arr_spb_reversal[$key]);
                    }
                }
            }
        }

        //SPB yang sudah terpakai
        $getSPBNumber = ParkingInvoiceSPBNumber::where('Number', $dataHeaderPO->Number)->where('Status', 1)->select('SPB_Number as RefDocumentNumber')->distinct()->get();
        $nomorSPB_yangterpakai = array_unique(array_column($getSPBNumber->toArray(),'RefDocumentNumber'));
        foreach ($nomorSPB as $key => $item) {
            if(in_array($item, $nomorSPB_yangterpakai)){
                $nomorSPB[$key] = null;
            }
        }

        //SPB yang sudah di invoice
        foreach ($nomorSPB as $key => $item) {
            $cek = Pdi::where('RefDocumentNumber', $item)->whereIn('POCategory', ['T', 'Q'])->get();
            if (count($cek) > 0) {
                $nomorSPB[$key] = null;
            }
        }
        $nomorSPB = array_values($nomorSPB);
        
        // dd($dataOngoing,$nomorSPB);

        $selesai = Carbon::now();
        $data = array(
            'dataPO' => $dataHeaderPO,
            'dataVendor' => $dataVendor,
            'dataOngoing' => $dataOngoing,
            'dataSPB' => $nomorSPB,
            'data_wth_vendor' => $data_wth_vendor,
            'data_tax_code' => $data_taxcode,
            'mulai' => $mulai,
            'selesai' => $selesai
        );
        echo json_encode($data);
    }
    //viewcariparking_detail
    public function viewcariparking_detail(Request $request)
    {
        $parking_header = ParkingInvoice::where('InvoiceNumber', $request->inv_no)->where('created_at', $request->created_at)->first();
        $parking_body = ParkingInvoice::where('InvoiceNumber', $request->inv_no)->where('created_at', $request->created_at)->get();
        $document = ParkingInvoiceDocument::where('InvoiceNumber', $request->inv_no)->where('created_at', $request->created_at)->get();
        // dd($parking_header->toArray(),$request->created_at);

        $datauser = Po::where('Number', $parking_header->Number)->first();
        if ($datauser->VendorCode != Auth::user()->email) {
            $action = "Update";
        } else {
            $action = "";
        }
        $user_email = Auth::user()->email;

        $wi_tax_code = WithholdingTaxCode::all();

        $data = array(
            'header'    => $parking_header,
            'body'      => $parking_body,
            'document'  => $document,
            'user'      => $user_email,
            'tax_code'  => $wi_tax_code
        );
        echo json_encode($data);
    }

    //parking or approve parking
    public function Parkinginvoice(Request $request)
    {
        // dd($request->toArray());
        try {
            $appsmenu = VwPo::where('Number', $request->Number)->first();
            if ($appsmenu != null) {
                //CEK AUTHORIZATION MENU
                if ($this->PermissionActionMenu('polocalongoing')) {
                    $link = "polocalongoing";
                } else if ($this->PermissionActionMenu('polocalongoing-vendor')) {
                    $link = "polocalongoing-vendor";
                } else if ($this->PermissionActionMenu('polocalongoing-proc')) {
                    $link = "polocalongoing-proc";
                } else if ($this->PermissionActionMenu('polocalongoing-nonmanagement')) {
                    $link = "polocalongoing-nonmanagement";
                } else if ($this->PermissionActionMenu('subcontractorongoing')) {
                    $link = "subcontractorongoing";
                } else if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                    $link = "subcontractorongoing-vendor";
                } else if ($this->PermissionActionMenu('subcontractorongoing-proc')) {
                    $link = "subcontractorongoing-proc";
                }
                else if ($this->PermissionActionMenu('subcontractorongoing-nonmanagement')) {
                    $link = "subcontractorongoing-nonmanagement";
                }
                 else {
                    return redirect()->back()->with('err_message', 'Akses Ditolak!');
                }


                //Log Parking Invoice
                $date   = Carbon::now();
                $LogParking = [
                    'Number'        => $request->Number,
                    'InvoiceNumber' => $request->invoice_no,
                    'Description'   => chop($request->Update, "?"),
                    'Name'          => Auth::user()->name,
                    'updated_by'    => Auth::user()->email
                ];

                if ($request->Update == "Receive Document?") {
                    $create1 = ParkingInvoice::where('InvoiceNumber', $request->invoice_no)
                        ->where('Status', "Request Parking")->where('IsReject', 0)
                        ->update([
                            'ReceiveDocumentDate' => Carbon::now()->format('Y-m-d'),
                            'Status'        => "Receive Document",
                            'updated_at'    => $date
                        ]);
                    $create2 = ParkingInvoiceLog::updateOrCreate(
                        [
                            'Number'        => $request->Number,
                            'InvoiceNumber' => $request->invoice_no,
                            'Description'   => chop($request->Update, "?")
                        ],
                        $LogParking
                    );

                    if ($create1) {
                        return redirect()->back()->with('suc_message', 'Invoice Number ' . $request->invoice_no . ' Receive Document Success ! ');
                    } else {
                        return redirect()->back()->with('err_message', 'Invoice Number ' . $request->invoice_no . ' Receive Document Failed ! ');
                    }
                }if ($request->Update == "Approve Parking?") {

                    if ($this->PermissionActionMenu('polocalongoing-vendor')) {
                        return redirect()->back()->with('err_message', 'Otorisasi Ditolak!');
                    }
                    if ($this->PermissionActionMenu('subcontractorongoing-vendor')) {
                        return redirect()->back()->with('err_message', 'Otorisasi Ditolak!');
                    }

                    $header = ParkingInvoice::where('InvoiceNumber', $request->invoice_no)->where('IsReject',0)->first();
                    $body   = ParkingInvoice::where('InvoiceNumber', $request->invoice_no)->where('IsReject', 0)->orderBy('ItemNumber')->get();

                    $no = 0;
                    foreach ($body as $item2) {
                        $textbody[] = [
                            'INVOICE_DOC_ITEM' => str_pad($no + 1, 6, "0", STR_PAD_LEFT),
                            'PO_NUMBER' => $header->Number,
                            'PO_ITEM' => str_pad($item2->ItemNumber, 5, "0", STR_PAD_LEFT),
                            'REF_DOC' => $item2->DocumentNumber,
                            'REF_DOC_YEAR' => Carbon::parse($item2->GRDate)->format('Y'),
                            'REF_DOC_IT' => str_pad($item2->DocumentNumberItem, 4, "0", STR_PAD_LEFT),
                            'TAX_CODE' => $item2->VAT,
                            'ITEM_AMOUNT' => $item2->TotalPrice,
                            'QUANTITY' => (int)$item2->Qty,
                            'PO_UNIT' => $item2->UoM,
                            'ITEM_TEXT' => 'PO ' . $header->Number . ' ITEM ' . $item2->ItemNumber,
                            'FINAL_INV' => 'X'
                        ];
                        $no++;
                    }
                    if ($header->wi_tax_type == NULL) {
                        $wi_tax_code = "";
                        $wi_tax_type = "";
                    } else {
                        $wi_tax_code = $header->wi_tax_code;
                        $wi_tax_type = $header->wi_tax_type;
                    }

                    $grossamount = $header->GrossAmount + $header->PPN;

                    $textfull = [
                        'INVOICE_IND' => 'X',
                        'DOC_DATE' => Carbon::parse($header->InvoiceDate)->format('d.m.Y'),
                        'PSTNG_DATE' => Carbon::now()->format('d.m.Y'),
                        'REF_DOC_NO' => str_replace(['.','-'],'', $header->Reference),
                        'COMP_CODE' => $header->CompanyCode,
                        'CURRENCY' => $header->Currency,
                        'GROSS_AMOUNT' => $grossamount,
                        'CALC_TAX_IND' => 'X',
                        'HEADER_TXT' => $header->InvoiceNumber,
                        'ALLOC_NMBR' => Carbon::parse($header->ReceiveDocumentDate)->format('d.m.Y'),
                        'ITEM_TEXT'  => 'PO ' . $header->Number,
                        'WI_TAX_TYPE' => $wi_tax_type,
                        'WI_TAX_CODE' => $wi_tax_code,
                        'ITEMDATA' => $textbody
                    ];

                    // dd(Auth::user()->email,$textfull);

                    // $response = Http::withBody(
                    //                 json_encode($textfull), 'application/json'
                    //             )->post('http://10.48.10.43/satria-rfc-dev/api/BapiParkingPo');

                    // $response = Http::withBody(
                    //     json_encode($textfull),
                    //     'application/json'
                    // )->post('http://10.48.10.43/satria-rfc-qa/api/BapiParkingPo');

                    $response = Http::withBody(
                        json_encode($textfull),
                        'application/json'
                    )->post('http://10.48.10.43/satria-rfc/api/BapiParkingPo');

                    $arr = json_decode($response, true);
                    // dd('tahan dulu.',$textfull, json_encode($textfull), $arr,$response);
                    if ($arr["code"] == 200) {
                        $create1 = ParkingInvoice::where('InvoiceNumber', $request->invoice_no)->where('Status', "Receive Document")
                            ->update([
                                'ApproveParkingDate' => Carbon::now()->format('Y-m-d'),
                                'Status'            => "Approve Parking",
                                'DocumentNumberSAP' => $arr['returnValue']['INVOICEDOCNUMBER'],
                                'FiscalYear'        => $arr['returnValue']['FISCALYEAR'],
                                'updated_at'        => $date
                            ]);
                        $create2 = ParkingInvoiceLog::updateOrCreate(
                            [
                                'Number'        => $request->Number,
                                'InvoiceNumber' => $request->invoice_no,
                                'Description'   => chop($request->Update, "?")
                            ],
                            $LogParking
                        );

                        Notification::create([
                            'Number'        => $appsmenu->Number,
                            'Subjek'        => "Approve Parking",
                            'user_by'       => Auth::user()->name,
                            'user_to'       => $appsmenu->Vendor,
                            'is_read'       => 1,
                            'menu'          => 'historyparking',
                            'comment'       => "Approve Parking PO.No $appsmenu->Number",
                            'created_at'    => $date
                        ]);
                        LogHistory::updateOrCreate([
                            'user'          => Auth::user()->email,
                            'menu'          => 'History Parking',
                            'description'   => 'Approve Parking',
                            'date'          => $date->toDateString(),
                            'time'          => $date->toTimeString(),
                            'ponumber'      =>  $appsmenu->Number,
                            'poitem'        =>  $appsmenu->ItemNumber,
                            'userlogintype' => Auth::user()->title,
                            'vendortype'    => '-',
                            'CreatedBy'     => Auth::user()->name,
                        ]);

                        return redirect()->back()->with('suc_message', 'Doc Number : ' . $arr["returnValue"]["INVOICEDOCNUMBER"] . ' | Fiscal Year : ' . $arr["returnValue"]["FISCALYEAR"] . ' | Parking PO Number ' . $header->Number . ' ' . $arr["message"]);
                    } else {

                        ParkingInvoice::where('InvoiceNumber', $request->invoice_no)
                            ->where('Status', "Receive Document")->where('IsReject', 0)
                            ->update([
                                'Status'        => "Reject Parking",
                                'Remark'        => 'Parking PO Number ' . $header->Number . ' Failed | ' . $arr['message'],
                                'IsReject'      => 1,
                                'updated_at'    => $date
                            ]);
                        $spbnumber = ParkingInvoice::select('Number', 'RefDocumentNumber')->where('InvoiceNumber', $request->invoice_no)->where('IsReject', 1)->distinct()->get();
                        if (count($spbnumber) > 0) {
                            foreach ($spbnumber as $spb) {
                                ParkingInvoiceSPBNumber::where('Number', $spb->Number)->where('SPB_Number', $spb->RefDocumentNumber)
                                    ->update(['Status' => 0]);
                            }

                            ParkingInvoiceDocument::where('InvoiceNumber', $spbnumber[0]->InvoiceNumber)->where('created_at',$request->created_at)
                                ->update(['Status' => 0]);
                        }
                        return redirect()->back()->with('err_message', 'Parking PO Number ' . $header->Number . ' Failed | ' . $arr['message']);
                    }
                }if ($request->Update == "Reject Parking?") {
                    $create1 = ParkingInvoice::where('InvoiceNumber', $request->invoice_no)
                        ->where('IsReject', 0)
                        ->update([
                            'Status'        => "Reject Parking",
                            'Remark'        => $request->remark_parking,
                            'IsReject'      => 1,
                            'updated_at'    => $date
                        ]);
                    $spbnumber = ParkingInvoice::select('Number', 'RefDocumentNumber')->where('InvoiceNumber', $request->invoice_no)->where('IsReject', 1)->distinct()->get();
                    if (count($spbnumber) > 0) {
                        foreach ($spbnumber as $spb) {
                            ParkingInvoiceSPBNumber::where('Number', $spb->Number)->where('SPB_Number', $spb->RefDocumentNumber)
                                ->update(['Status' => 0]);
                        }
                    }
                    ParkingInvoiceDocument::where('InvoiceNumber', $request->invoice_no)
                        ->where('created_at',$request->created_at)
                        ->update(['Status' => 0]);
                    $create2 = ParkingInvoiceLog::updateOrCreate(
                        [
                            'Number'        => $request->Number,
                            'InvoiceNumber' => $request->invoice_no,
                            'Description'   => chop($request->Update, "?")
                        ],
                        $LogParking
                    );
                    if ($create1) {
                        return redirect()->back()->with('suc_message', 'Invoice Number ' . $request->invoice_no . ' Successfully Reject ! ');
                    } else {
                        return redirect()->back()->with('err_message', 'Invoice Number ' . $request->invoice_no . ' Failed to Reject ! ');
                    }
                }
                // Vendor Save Parking
                else {
                    if(!isset($request->GRQuantity)){
                        return redirect()->back()->with('error', 'PO'.$request->PONumber[0].', Surat Jalan Not Selected!');
                    }
                    if (count($request->GRQuantity) < 1) {
                        return redirect()->back()->with('error', 'Surat Jalan Not Selected!');
                    }

                    //VALIDASI INVOICE NUMBER, NO. FAKTUR PAJAK
                    $cekinvoice = ParkingInvoice::select('InvoiceNumber')->distinct()->where('InvoiceNumber', $request->invoice_no)->where('IsReject', 0)->get();
                    if (count($cekinvoice) > 0) {
                        return redirect()->back()->with('err_message', 'FAILED. Invoice Number ' . $request->invoice_no . ' has been created!');
                    }
                    $ceknofaktur = ParkingInvoice::select('Reference')->distinct()->where('Reference', $request->reference)->where('IsReject', 0)->get();
                    if (count($ceknofaktur) > 0) {
                        return redirect()->back()->with('err_message', 'FAILED. No. Faktur Pajak ' . $request->reference . ' has been created!');
                    }

                    $request->validate([
                        'filename.*' => 'required|mimes:PDF,pdf',
                    ]);

                    $data = count($request->GRQuantity);

                    if (isset($request->wi_tax_code)) {
                        $wi_tax_code = $request->wi_tax_code;
                        $wi_tax_type = $request->wi_tax_type;
                    } else {
                        $wi_tax_code = NULL;
                        $wi_tax_type = NULL;
                    }

                    $pphnya = str_replace('.', '', $request->pph);

                    $nows = Carbon::now()->format('Y-m-d H:i:s');

                    for ($i = 0; $i < $data; $i++) {
                        $insert[] = [
                            'Number'                => $request->PONumber[$i],
                            'ItemNumber'            => $request->ItemNumber[$i],
                            'Material'              => $request->Material[$i],
                            'Description'           => $request->Description[$i],
                            'VendorName'            => $request->VendorName[$i],
                            'POCreator'             => $request->POCreator[$i],
                            'Qty'                   => $request->GRQuantity[$i],
                            'GRDate'                => $request->GRDate[$i],
                            'InvoiceDate'           => Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d'),
                            'InvoiceNumber'         => $request->invoice_no,
                            'GrossAmount'           => $request->amount,
                            'PPN'                   => $request->ppn,
                            'PPH'                   => $pphnya,
                            'AmountTotal'           => $request->amounttotal,
                            'Reference'             => $request->reference,
                            'DocumentNumber'        => $request->DocumentNumber[$i],
                            'DocumentNumberItem'    => $request->DocumentNumberItem[$i],
                            'wi_tax_code'           => $wi_tax_code,
                            'wi_tax_type'           => $wi_tax_type,
                            'Price'                 => $request->Price[$i],
                            'Currency'              => $request->Currency[$i],
                            'TotalPrice'            => $request->TotalPrice[$i],
                            'RefDocumentNumber'     => $request->RefDocumentNumber[$i],
                            'VAT'                   => $request->VAT[$i],
                            'UoM'                   => $request->UoM[$i],
                            'CompanyCode'           => $request->CompanyCode[$i],
                            'Status'                => "Request Parking",
                            'Remark'                => NULL,
                            'IsReject'              => 0,
                            'created_at'            => $nows,
                            'created_by'            => Auth::user()->email,
                        ];
                    }
                    $create1 = ParkingInvoice::insert($insert);
                    unset($insert);

                    foreach ($request->SPB_Number as $item) {
                        ParkingInvoiceSPBNumber::updateOrCreate(
                            [
                                'Number'        => $request->PONumber[0],
                                'SPB_Number'    => $item,
                                'InvoiceNumber' => $request->invoice_no
                            ],
                            [
                                'Number'        => $request->PONumber[0],
                                'SPB_Number'    => $item,
                                'InvoiceNumber' => $request->invoice_no,
                                'Status'        => 1,
                                'created_at'    => $nows,
                                'created_by'    => Auth::user()->email
                            ]
                        );
                    }
                    foreach ($request->file('filename') as $parkingdocument) {
                        if ($parkingdocument->isValid()) {
                            $parkingdocumentName =  Carbon::createFromFormat('d/m/Y', $request->invoice_date)->format('Y-m-d') . '_' . str_replace(' ', '_', $parkingdocument->getClientOriginalName());
                            if (!file_exists(public_path('potracking/parking_invoice/' . $appsmenu->Number))) {
                                mkdir(public_path('potracking/parking_invoice/' . $appsmenu->Number), 0777, true);
                            }
                            $parkingdocument->move(public_path('potracking/parking_invoice/' . $appsmenu->Number), $parkingdocumentName);
                        }
                        $insert[] = [
                            'Number'        => $request->Number,
                            'InvoiceNumber' => $request->invoice_no,
                            'FileName'      => $parkingdocumentName,
                            'Status'        => 1,
                            'created_at'    => $nows,
                            'CreatedBy'     => Auth::user()->email
                        ];
                    }
                    $create2 = ParkingInvoiceDocument::insert($insert);

                    if ($create2) {
                        Notification::create([
                            'Number'         => $appsmenu->Number,
                            'Subjek'         => "Request Parking",
                            'user_by' => Auth::user()->name,
                            'user_to' => $appsmenu->NRP,
                            'is_read' => 1,
                            'menu' => "historyparking",
                            'comment' => "Request Parking PO.No $appsmenu->Number",
                            'created_at' => $date
                        ]);

                        LogHistory::updateOrCreate([
                            'user'  => Auth::user()->email,
                            'menu'  => $link,
                            'description' => 'Request Parking',
                            'date'  => $date->toDateString(),
                            'time'     => $date->toTimeString(),
                            'ponumber' =>  $appsmenu->Number,
                            'poitem' =>  $appsmenu->ItemNumber,
                            'userlogintype' => Auth::user()->title,
                            'vendortype' => null,
                            'CreatedBy'  => Auth::user()->name,
                        ]);

                        $LogParkingVendor = [
                            'Number'        => $request->Number,
                            'InvoiceNumber' => $request->invoice_no,
                            'Description'   => 'Request Parking',
                            'updated_by'    => Auth::user()->email
                        ];
                        $create2 = ParkingInvoiceLog::updateOrCreate(
                            [
                                'Number'        => $request->Number,
                                'InvoiceNumber' => $request->invoice_no
                            ],
                            $LogParkingVendor
                        );
                        return redirect()->back()->with('suc_message', 'Request Parking Invoice Berhasil! PO : '.$request->PONumber[0]);
                    } else {
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    // PO All- View confirmpo
    public function view_confirmpo(Request $request)
    {

        $get_po = VwnewpoAll::where('POID', $request->number)->where('ActiveStage', null)->orderby('ItemNumber')->get();
        $datapo = VwnewpoAll::select('Vendor', 'Number')->where('POID', $request->number)->first();
        $data = array(
            'data'        => $get_po,
            'datapo'        => $datapo,
        );
        echo json_encode($data);
    }

    // PO All- View negosiasi
    public function view_negosiasipo(Request $request)
    {
        $dataid = VwnewpoAll::where('POID', $request->number)->where('ItemNumber', $request->item)->first();
        $data = VwnewpoAll::where('POID', $request->number)->where('ActiveStage', 1)->distinct()->orderBy('ItemNumber', 'Asc')->get();

        if ($this->PermissionActionMenu('polocalnewpo')) {
            $actionmenu =  $this->PermissionActionMenu('polocalnewpo');
        } else if ($this->PermissionActionMenu('subcontractornewpo')) {
            $actionmenu =  $this->PermissionActionMenu('subcontractornewpo');
        } else if ($this->PermissionActionMenu('poimportnewpo')) {
            $actionmenu =  $this->PermissionActionMenu('poimportnewpo');
        }

        $material_potracking    = VwnewpoAll::where('POID', $request->number)->select('Material')->distinct()->get()->toArray();
        $ccr_reqdate            = VwPoTrackingReqDateMaterial::whereIn('material', $material_potracking)->groupby('material')->selectRaw('material,MIN(req_date) AS req_date')->get();
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

        $data = array(
            'dataid'      => $dataid,
            'data'        => $data,
            'actionmenu'  => $actionmenu,
        );

        echo json_encode($data);
    }
    //ceknotif
    public function cek_coment(Request $request)
    {
        $Name    = Auth::user()->name;
        $po    = VwPo::where('Number', $request->number)->where('ItemNumber', $request->item)->first();
        $datar    = Comments::where('Number', $request->number)->where('ItemNumber', $request->item)->groupBy('comment', 'created_at')->orderBy('id', 'ASC')->get();
        $date   = Carbon::now();
        Comments::where('Number', $request->number)->where('ItemNumber', $request->item)->where('user_to', $Name)
            ->update([
                'is_read' => 1,
                'updated_at' => $date
            ]);
        $data = array(
            'datar'        => $datar,
            'Name'        => $Name,
            'Po'        => $po,
        );
        echo json_encode($data);
    }
    public function cek_ticket(Request $request)
    {
        $datanotif = Notification::where('id', $request->id)->where('user_to', Auth::user()->name)->first();
        if ($datanotif->menu == "Ticket Local" || $datanotif->menu == "Ticket Subcont" || $datanotif->menu == "Cancel Ticket Local" || $datanotif->menu == "Cancel Ticket Subcont" || $datanotif->menu == "Approve Ticket Local" || $datanotif->menu == "Approve Ticket Subcont") {
            $datafirst = DetailTicket::where('TicketID', $datanotif->Subjek)->orderBy('Status', 'asc')->first();
            $data = DetailTicket::where('TicketID', $datanotif->Subjek)->orderBy('Status', 'asc')->get();
            $status = 1;
        } elseif ($datanotif->menu == "polocalnewpo" || $datanotif->menu == "subcontractornewpo" || $datanotif->menu == "importnewpo") {
            $datafirst = VwnewpoAll::where('Number', $datanotif->Number)->orderBy('Status', 'asc')->first();
            $data = VwnewpoAll::where('Number', $datanotif->Number)->orderBy('Status', 'asc')->get();
            $status = 2;
        }

        $data = array(
            'data' => $data,
            'datanotif' => $datanotif,
            'datafirst' => $datafirst,
            'status' => $status,

        );
        echo json_encode($data);
    }
    public function CekDelivery(Request $request)
    {
        $actionmenu =  $this->PermissionActionMenu('potracking');
        $date_now = Carbon::now()->format('Y-m-d');
        if ($request->id == "delivery") {
            $status = ['A', 'D'];
            $date = 'DeliveryDate';
            $filter = 'asc';
        } else if ($request->id == "deliverytoday") {
            $status = ['A', 'D'];
            $date = 'DeliveryDate';
            $filter = 'asc';
        } else if ($request->id == "arrived") {
            $status = ['S'];
            $date = 'SecurityDate';
            $filter = 'desc';
        } else if ($request->id == "arrivedtoday") {
            $status = ['S'];
            $date = 'SecurityDate';
            $filter = 'desc';
        }

        if ($request->id == "deliverytoday" || $request->id == "arrivedtoday") {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $data = DetailTicket::whereIn('status', $status)->whereDate($date, $date_now)->where('CreatedBy', Auth::user()->name)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', $filter)->get();
            } else {
                $data = DetailTicket::whereIn('status', $status)->whereDate($date, $date_now)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', $filter)->get();
            }
        } else if ($request->id == "delivery") {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $data = DetailTicket::whereIn('status', $status)->where('CreatedBy', Auth::user()->name)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', $filter)->get();
            } else {
                $data = DetailTicket::whereIn('status', $status)->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', $filter)->get();
            }
        } else {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $data = DetailTicket::whereIn('status', $status)->where('CreatedBy', Auth::user()->name)->whereNotIn('SecurityDate', ['', 'NULL'])->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', $filter)->get();
            } else {
                $data = DetailTicket::whereIn('status', $status)->whereNotIn('SecurityDate', ['', 'NULL'])->groupBy('Number', 'ItemNumber', 'TicketID')->orderBy('DeliveryDate', $filter)->get();
            }
        }


        $data = array(
            'data' => $data,
            'tanggal' => $date,
        );
        echo json_encode($data);
    }
    public function CekMaterialVendor(Request $request)
    {
        $actionmenu =  $this->PermissionActionMenu('potracking');
        $datamaterial = PdiHistory::select('Material', 'NetPrice')->where('NetPrice', '>', 10000000)->groupBy('Material')->get();
        foreach ($datamaterial as $items) {
            $material[] = $items->Material;
        }
        if ($request->id == "material") {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $data =  DetailTicket::selectRaw('CreatedBy,Material,Description, SUM(Quantity) as TotalMaterial')->groupBy('Material')->orderByDesc('TotalMaterial')->where('status', 'S')->whereIn('Material', $material)->where('CreatedBy', Auth::user()->name)->get();
            } else {
                $data =  DetailTicket::selectRaw('Material,Description, SUM(Quantity) as TotalMaterial')->whereIn('Material', $material)->where('status', 'S')->groupBy('Material')->orderByDesc('TotalMaterial')->get();
            }
        } else {
            if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                $data = DetailTicket::selectRaw('CreatedBy,Number, COUNT(distinct(TicketID)) as TotalVendors , COUNT(ItemNumber) as TotalItem')->groupBy('CreatedBy')->orderByDesc('TotalVendors')->where('status', 'S')->where('CreatedBy', Auth::user()->name)->take(1)->get();
            } else {
                $data = DetailTicket::selectRaw('CreatedBy,Number, COUNT(distinct(TicketID)) as TotalVendors , COUNT(ItemNumber) as TotalItem')->groupBy('CreatedBy')->orderByDesc('TotalVendors')->where('status', 'S')->take(10)->get();
            }
        }


        $data = array(
            'data' => $data,

        );
        echo json_encode($data);
    }
    public function CekUser(Request $request)
    {
        //totaluser
        $datavendor = UserVendor::all();
        $vendor = [];
        foreach ($datavendor as $item) {
            $vendor[] = $item->VendorCode;
        }
        $datauser    =  VwUserRoleGroup::select('username')->where('group', 15)->get();
        foreach ($datauser as $item) {
            $user[] = $item->username;
        }
        array_push($user, "", "NULL");
        if ($request->status == "Active") {
            $data =  LogHistory::whereDate('created_at', Carbon::today())->whereNotIn('CreatedBy', $user)->groupBy('CreatedBy')->get();
        } elseif ($request->status == "All") {
            $data =  LogHistory::selectRaw('CreatedBy,COUNT(distinct(date)) as TotalLogin, MAX(updated_at) as created_at')->whereNotIn('CreatedBy', $user)->groupBy('CreatedBy')->orderBy('id', 'DESC')->get();
        } elseif ($request->status == "Internal") {
            $data =  LogHistory::selectRaw('CreatedBy,COUNT(distinct(date)) as TotalLogin, MAX(updated_at) as created_at')->whereNotIn('user', $vendor)->whereNotIn('CreatedBy', $user)->groupBy('CreatedBy')->get();
        } elseif ($request->status == "Vendor") {
            $data =   LogHistory::selectRaw('CreatedBy,COUNT(distinct(date)) as TotalLogin, MAX(updated_at) as created_at')->whereIn('user', $vendor)->whereNotIn('CreatedBy', ['', 'NULL'])->groupBy('CreatedBy')->get();
        }
        $data = array(
            'data' => $data,
        );
        echo json_encode($data);
    }
    //Cekdeetail
    public function DetailDelivery($id)
    {
        $month = date("m", strtotime($id));
        $years =  Carbon::now()->format('Y');
        $actionmenu =  $this->PermissionActionMenu('potracking');
        if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
            $data = DetailTicket::where('status', 'S')->whereNotIn('SecurityDate', ['', 'NULL'])->where('CreatedBy', Auth::user()->name)->whereMonth('DeliveryDate', $month)->whereYear('DeliveryDate', $years)->groupBy('TicketID', 'ItemNumber', 'TicketID')->get();
        } else {
            $data = DetailTicket::where('status', 'S')->whereNotIn('SecurityDate', ['', 'NULL'])->whereMonth('DeliveryDate', $month)->whereYear('DeliveryDate', $years)->groupBy('TicketID', 'ItemNumber', 'TicketID')->get();
        }
        return view(
            'po-tracking/master/DetailDelivery',
            compact(
                'data'
            )
        );
    }
    //Confirmkanban
    public function CekKanban(Request $request)
    {
        $notif = Notification::select('Subjek')->where('comment', $request->id)->first();
        $data = DetailTicket::where('TicketID', $request->id)->where('status', 'K')->groupBy('TicketID', 'Number', 'ItemNumber')->get();
        $dataviewticket = DetailTicket::where('TicketID', $request->id)->where('status', 'K')->groupBy('TicketID', 'Number', 'ItemNumber')->first();
        $data = array(
            'notif' => $notif,
            'data' => $data,
            'dataviewticket' => $dataviewticket
        );
        echo json_encode($data);
    }
    public function ConfirmKanban(Request $request)
    {
        try {
            $dates = new DateTime();
            $datetime = $dates->format('Y-m-d h:i:s');
            if ($request->Action == "Approve") {
                $update = DetailTicket::where('TicketID', $request->Ticket)
                    ->update([
                        'Status' => 'D',
                        'AcceptedDate' => $datetime,
                    ]);
            } else {
                $update = DetailTicket::where('TicketID', $request->Ticket)
                    ->update([
                        'Status' => 'C',
                        'AcceptedDate' => $datetime,
                    ]);
            }
            Notification::where('comment', $request->Ticket)
                ->update([
                    'is_read' => 3,
                ]);

            if ($update) {
                return response()->json(['message' => 'Sended.']);
            } else {
                return response()->json(['message' => 'Not Sent']);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
        }
    }
    //insertnotif

    public function InsertComment(Request $request)
    {
        try {
            if ($request->Name == $request->Vendor) {
                $user_to = $request->Proc;
            } else {
                $user_to = $request->Vendor;
            }


            $data    =  VwUserRoleGroup::select('username')->distinct()->where('username', '!=', $request->Name)->whereIn('group', [30, 31, 17, 18, 20, 45])->get();

            if ($request->Name == $request->Vendor || $request->Name == $request->Proc) {
                foreach ($data as $q) {
                    $datarole[] = $q->username;
                }
                array_push($datarole, $user_to);
            } else {
                foreach ($data as $q) {
                    $datarole[] = $q->username;
                }
                array_push($datarole, $request->Vendor, $request->Proc);
            }

            $datavendor = count($datarole);
            if (!empty($request->Comment)) {
                $date   = Carbon::now();
                for ($i = 0; $i < $datavendor; $i++) {
                    Comments::create([
                        'Number' => $request->Number,
                        'ItemNumber' => $request->Item,
                        'user_by' => $request->Name,
                        'user_to' => $datarole[$i],
                        'menu' => 'Pesan',
                        'comment' => $request->Comment,
                        'created_at' => $date
                    ]);
                }

                return response()->json(['message' => 'Sended.']);
            } else {
                return response()->json(['message' => 'Not Sent']);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
        }
    }
    public function SkipProforma(Request $request)
    {
        if (!empty($request->ParentID)) {
            try {
                if ($request->vendor == "Local") {
                    $link = "polocalongoing";
                    $create = Pdi::whereIn('ParentID', $request->ParentID)->where('ActiveStage', '2')
                        ->update([
                            'ActiveStage' => 4,
                        ]);
                    if ($create) {
                        return redirect($link)->with('suc_message', 'Success Skip Proforma!');
                    } else {
                        return redirect()->back()->with('err_message', 'Proforma gagal diskip!');
                    }
                } elseif ($request->vendor == "Subcont") {
                    $link = "subcontractorongoing";
                    $create = Pdi::whereIn('ParentID', $request->ParentID)->where('ActiveStage', '2')
                        ->update([
                            'ActiveStage' => 3,
                        ]);
                    if ($create) {
                        return redirect($link)->with('suc_message', 'Success Skip Proforma!');
                    } else {
                        return redirect()->back()->with('err_message', 'Proforma gagal diskip!');
                    }
                } else {
                    return redirect()->back()->with('err_message', 'Error Request, Exception Error');
                }
            } catch (Exception $e) {
                $this->ErrorLog($e);
                return redirect()->back()->with('err_message', 'Error Request, Exception Error');
            }
        } else {
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
        }
    }
    //allnotification
    public function allnotification()
    {

        $notif = Notification::where('user_to', Auth::user()->name)->orderBy('id', 'DESC')
            ->get();
        if (!empty($notif)) {
            return view(
                'po-tracking/panel/allnotification',
                compact(
                    'notif'
                )
            );
        } else {
            return redirect('/')->with('err_message', 'No Notification!');
        }
    }
    //allmesengger
    public function allmesengger()
    {

        $notif = Comments::where('user_to', Auth::user()->name)->orderBy('id', 'DESC')
            ->get();
        if (!empty($notif)) {
            return view(
                'po-tracking/panel/allmesengger',
                compact(
                    'notif'
                )
            );
        } else {
            return redirect('/')->with('err_message', 'No Mesenger!');
        }
    }

    //DownloadPO
    public function PoPdf($id, $status)
    {
        if ($status == "newpo") {
            $data = VwPo::where('Number', $id)->where('IsClosed', NULL)->groupBy('DeliveryDate', 'ItemNumber')->orderBy('ItemNumber')->get();
            // $data = VwnewpoAll::where('Number', $id)->groupBy('purchasingdocumentitem.DeliveryDate','purchasingdocumentitem.ItemNumber')->orderBy('purchasingdocumentitem.ItemNumber')->get();
            $datapo = VwnewpoAll::where('Number', $id)->first();
            $number_old = MigrationPO::where('ebeln', $id)->select('submi')->first();
            $nrp_code = MigrationProcurementPO::where('procurement', $datapo->NRP)->select('procurement_code')->first();
            if (isset($number_old)) {
                $datapo->setAttribute('Number_old', $number_old->submi);
            } else {
                $datapo->setAttribute('Number_old', NULL);
            }
            if (isset($nrp_code)) {
                $datapo->setAttribute('nrp_code', $nrp_code->procurement_code);
            } else {
                $datapo->setAttribute('nrp_code', NULL);
            }
            $data = array(
                'data' => $data,
                'datapo' => $datapo,
            );
        } else {
            $data = VwPo::where('Number', $id)->where('IsClosed', NULL)->groupBy('DeliveryDate', 'ItemNumber')->orderBy('ItemNumber')->get();
            // $data = VwongoingAll::where('Number', $id)->groupBy('DeliveryDate','ItemNumber')->orderBy('ItemNUmber')->get();
            $datapo = VwongoingAll::where('Number', $id)->first();
            $number_old = MigrationPO::where('ebeln', $id)->select('submi')->first();
            $nrp_code = MigrationProcurementPO::where('procurement', $datapo->NRP)->select('procurement_code')->first();
            if (isset($number_old)) {
                $datapo->setAttribute('Number_old', $number_old->submi);
            } else {
                $datapo->setAttribute('Number_old', NULL);
            }
            if (isset($nrp_code)) {
                $datapo->setAttribute('nrp_code', $nrp_code->procurement_code);
            } else {
                $datapo->setAttribute('nrp_code', NULL);
            }
            $data = array(
                'data' => $data,
                'datapo' => $datapo,
            );
        }
        $pdf = PDF::loadView('po-tracking/polocal/popdf', $data);
        // return view('po-non-sap/po-local/ticketpdf')->with('data', $data);
        return $pdf->stream();
    }
    //Poreturn
    public function Poreturn(Request $request)
    {
        $data = Pdi::where('ID', $request->ID)->where('POID', $request->POID)->first();
        $cekvendortype = VwHistoryall::where('ID', $request->ID)->where('POID', $request->POID)->first();
        if ($cekvendortype->VendorType == 'Vendor Local') {
            $links =  'polocalhistory';
        } else if ($cekvendortype->VendorType == 'Vendor SubCont') {
            $links =  'subcontractorhistory';
        } else if ($cekvendortype->VendorType == 'Vendor Import') {
            $links =  'poimporthistory';
        }

        if (!empty($data)) {
            $poreturn = Pdi::where('POID', $request->POID)->where('IsClosed', 'C')
                ->update([
                    'ActiveStage' => NULL,
                    'IsClosed' => NULL,
                ]);
            if ($poreturn) {
                return redirect($links)->with('suc_message', 'Reverse PO Success!');
            } else {
                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
            }
        } else {
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
    }

    // New PO Insert
    public function poInsert(Request $request)
    {
        if ($request->ID == null) {
            return redirect()->back()->with('err_message', 'Please Select Item!');
        } else {
            $varmain = Pdi::where('ID', $request->ID[0])->first();
            $varCountItemNumber = VwnewpoAll::select('ItemNumber')->where('POID', $varmain->POID)->distinct()->count();

            for ($n = 0; $n < $varCountItemNumber; $n++) {
                $strIDpartial           = "IDpartial" . $n;
                $strConfirmedQuantity   = "ConfirmedQuantity" . $n;
                $strConfirmedDate       = "ConfirmedDate" . $n;

                if ($request->$strIDpartial == null) {
                    continue;
                } else {
                    $varIDpartial           = $request->$strIDpartial;
                    $varConfirmedQuantity   = $request->$strConfirmedQuantity;
                    $varConfirmedDate       = $request->$strConfirmedDate;

                    $totalQty               = array_sum($varConfirmedQuantity);
                    $countQty               = count($varConfirmedQuantity);

                    for ($a = 0; $a < count($varConfirmedDate); $a++) {
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
            if (!empty($varmain)) {
                $checkvendortype = VwnewpoAll::where('ID', $request->ID[0])->first();
                if ($checkvendortype->VendorType == 'Vendor Local') {
                    $links =  'polocalnewpo';
                } else if ($checkvendortype->VendorType == 'Vendor SubCont') {
                    $links =  'subcontractornewpo';
                } else if ($checkvendortype->VendorType == 'Vendor Import') {
                    $links =  'poimportnewpo';
                }

                if ($request->action == "Save") {
                    $date   = Carbon::now();

                    foreach ($request->ID as $itemID) {
                        foreach ($varPartial as $eachitem) {
                            if ($eachitem['ID'] != $itemID) {
                                continue;
                            } else {
                                $appsmenu = Pdi::where('ID', $eachitem['ID'])->whereNotNull('ActiveStage')->first();

                                //Cek Total Quantity partial
                                if ($eachitem['TotalQuantity'] > $appsmenu->Quantity or $eachitem['TotalQuantity'] <= 0) {
                                    return redirect()->back()->with('err_message', 'Cek Total Qty!');
                                }

                                $checkpo    = VwongoingAll::where('POID', $appsmenu->POID)->first();
                                $date1      = Carbon::createFromFormat('Y-m-d', $appsmenu->DeliveryDate)->format('Y-m-d');
                                $date2      = Carbon::createFromFormat('d/m/Y', $eachitem['ConfirmedDate'])->format('Y-m-d');

                                if ($date1 >= $date2 && $eachitem['CountQty'] == 1 && $eachitem['TotalQuantity'] == $appsmenu->Quantity) {
                                    if (isset($checkpo)) {
                                        $confirmed = '1';
                                        $active = $checkpo->ActiveStage;
                                        //Subcont
                                        if ($checkvendortype->VendorType == 'Vendor SubCont') {
                                            $link = "subcontractorongoing";
                                            if (($appsmenu->ReleaseDate < Carbon::now()->format('Y-m-d')) || $appsmenu->ReleaseDate = null) {
                                                $leadtimeitem        = $appsmenu->WorkTime;
                                            } else {
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
                                        } else {
                                            $link = "polocalongoing";
                                            $leadtimeitem   = NULL;
                                            $PB             = NULL;
                                            $Setting        = NULL;
                                            $Fullweld       = NULL;
                                            $Primer         = NULL;
                                        }
                                        //END of Subcont
                                    } else {
                                        $confirmed = '1';
                                        $active = "2";

                                        //Subcont
                                        if ($checkvendortype->VendorType == 'Vendor SubCont') {
                                            $link = "subcontractorongoing";
                                            if (($appsmenu->ReleaseDate < Carbon::now()->format('Y-m-d')) || $appsmenu->ReleaseDate = null) {
                                                $leadtimeitem        = $appsmenu->WorkTime;
                                            } else {
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
                                        } else {
                                            $link = "polocalongoing";
                                            $leadtimeitem   = NULL;
                                            $PB             = NULL;
                                            $Setting        = NULL;
                                            $Fullweld       = NULL;
                                            $Primer         = NULL;
                                        }
                                        //END of Subcont
                                    }

                                    Notification::create(
                                        [
                                            'Number'         => $checkvendortype->Number,
                                            'Subjek'         => "Confirm New PO",
                                            'user_by' => Auth::user()->name,
                                            'user_to' => $checkvendortype->NRP,
                                            'is_read' => 1,
                                            'menu' => $link,
                                            'comment' => "New PO $checkvendortype->Number",
                                        ],
                                        [
                                            'created_at' => $date
                                        ]
                                    );
                                } else {

                                    $confirmed = NULL;
                                    $active = "1";

                                    //Subcont
                                    if ($checkvendortype->VendorType == 'Vendor SubCont') {
                                        $leadtimeitem   = NULL;
                                        $PB             = NULL;
                                        $Setting        = NULL;
                                        $Fullweld       = NULL;
                                        $Primer         = NULL;
                                    } else {
                                        $leadtimeitem   = NULL;
                                        $PB             = NULL;
                                        $Setting        = NULL;
                                        $Fullweld       = NULL;
                                        $Primer         = NULL;
                                    }
                                    Notification::create(
                                        [
                                            'Number'         => $checkvendortype->Number,
                                            'Subjek'         => "Confirm New PO",
                                            'user_by' => Auth::user()->name,
                                            'user_to' => $checkvendortype->NRP,
                                            'is_read' => 1,
                                            'menu' => $links,
                                            'comment' => "New PO $checkvendortype->Number",
                                        ],
                                        [
                                            'created_at' => $date
                                        ]
                                    );
                                }

                                if ($eachitem['No'] == 0) {

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

                                    if ($update) {
                                        $varStatus[] = 'Success';
                                    } else {
                                        $varStatus[] = 'Failed';
                                    }
                                } else {
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
                                        'DocumentNumberItem' => $appsmenu->DocumentNumberItem,
                                        'WorkTime'          => $appsmenu->WorkTime,
                                        'OpenQuantity'      => $appsmenu->OpenQuantity,
                                        'OpenQuantityIR'    => $appsmenu->OpenQuantityIR,
                                        'CreatedBy'         => Auth::user()->name
                                    ];
                                    $update = Pdi::insert($newinsert);

                                    if ($update) {
                                        $varStatus[] = 'Success';
                                    } else {
                                        $varStatus[] = 'Failed';
                                    }
                                }
                            }
                        }
                    }
                    if (in_array('Failed', $varStatus)) {
                        return redirect()->back()->with('err_message', 'Data gagal diproses!');
                    } else {
                        return redirect($links)->with('suc_message', 'Data berhasil diproses!');
                    }
                } else {
                    $date   = Carbon::now();

                    Notification::create([
                        'Number'         => $checkvendortype->Number,
                        'Subjek'         => "Cancel PO",
                        'user_by' => Auth::user()->name,
                        'user_to' => $checkvendortype->NRP,
                        'is_read' => 1,
                        'menu' => $links,
                        'comment' => "Cancel PO $checkvendortype->Number",
                        'created_at' => $date
                    ]);
                    foreach ($request->ID as $itemID) {
                        $update = Pdi::where('ID', $itemID)
                            ->update([
                                'IsClosed' => "C",
                                'ActiveStage' => 1
                            ]);
                        if ($update) {
                            $varStatus[] = 'Success';
                        } else {
                            $varStatus[] = 'Failed';
                        }
                    }
                    if (in_array('Failed', $varStatus)) {
                        return redirect()->back()->with('err_message', 'Data gagal diproses!');
                    } else {
                        return redirect($links)->with('suc_message', 'PO Item berhasil di Cancel!');
                    }
                }
            } else {
                return redirect()->back()->with('err_message', 'Please Select Item!');
            }
        }
    }
    // New PO Update
    public function poUpdate(Request $request)
    {
        if (!empty($request->ID)) {
            $appsmenu = Pdi::whereIn('ID', $request->ID)->get();

            if (!empty($appsmenu)) {
                $cekvendortype = VwnewpoAll::where('ID', $request->ID)->first();
                if ($cekvendortype->VendorType == 'Vendor Local') {
                    $links =  'polocalnewpo';
                } else if ($cekvendortype->VendorType == 'Vendor SubCont') {
                    $links =  'subcontractornewpo';
                } else if ($cekvendortype->VendorType == 'Vendor Import') {
                    $links =  'poimportnewpo';
                }

                $po = Pdi::where('ID', $request->ID)->first();
                if ($request->action == "Yes") {
                    Notification::where('Number', $cekvendortype->Number)->where('Subjek', 'Confirm New PO')
                        ->update([
                            'is_read' => 3,
                        ]);
                    $cekongoing = VwOngoingAll::where('POID', $po->POID)->whereNotNull('ActiveStage')->first();
                    if (isset($cekpo)) {
                        //Subcont
                        if ($cekvendortype->VendorType == 'Vendor SubCont') {
                            for ($i = 0; $i < $appsmenu->count(); $i++) {
                                if (($appsmenu[$i]->ConfirmedDate < Carbon::now()->format('Y-m-d')) || $appsmenu[$i]->ConfirmedDate = null) {
                                    $leadtimeitem        = $appsmenu[$i]->WorkTime;
                                } else {
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
                        } else {
                            $leadtimeitem   = NULL;
                            $PB             = NULL;
                            $Setting        = NULL;
                            $Fullweld       = NULL;
                            $Primer         = NULL;
                            $update =  Pdi::whereIn('ID', $request->ID)
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
                    } else {
                        //Subcont
                        if ($cekvendortype->VendorType == 'Vendor SubCont') {
                            for ($i = 0; $i < $appsmenu->count(); $i++) {
                                if (($appsmenu[$i]->ConfirmedDate < Carbon::now()->format('Y-m-d')) || $appsmenu[$i]->ConfirmedDate = null) {
                                    $leadtimeitem        = $appsmenu[$i]->WorkTime;
                                } else {
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
                        //END of Subcont
                        else {
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
                    }
                    if ($update) {
                        return redirect($links)->with('suc_message', 'PO Di ACC!');
                    } else {
                        return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                    }
                } elseif ($request->action == "Update") {
                    $data = count($request->IDS);
                    for ($i = 0; $i < $data; $i++) {
                        $update =  Pdi::where('ID', $request->IDS[$i])
                            ->update(['ConfirmedDate' => Carbon::createFromFormat('d/m/Y', $request->ConfirmedDate[$i])->format('Y-m-d')]);
                    }
                    if ($update || !$update) {
                        return redirect($links)->with('suc_message', 'PO Di Update!');
                    } else {
                        return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                    }
                } else {
                    Notification::where('Number', $cekvendortype->Number)->where('Subjek', 'Confirm New PO')
                        ->update([
                            'is_read' => 3,
                        ]);
                    $date   = Carbon::now();
                    Notification::create([
                        'Number'         => $cekvendortype->Number,
                        'Subjek'         => "Cancel PO",
                        'user_by' => Auth::user()->name,
                        'user_to' => $cekvendortype->Vendor,
                        'is_read' => 1,
                        'menu' => $links,
                        'comment' => "Cancel PO $cekvendortype->Number",
                        'created_at' => $date
                    ]);
                    $update =  Pdi::whereIn('ID', $request->ID)
                        ->update([
                            'ConfirmedItem' =>  0,
                            'IsClosed'      => 'C',
                        ]);
                }
                if ($update) {
                    return redirect($links)->with('suc_message', 'PO Di Cancel!');
                } else {
                    return redirect()->back()->with('err_message', 'Data gagal disimpan!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } else {
            return redirect()->back()->with('error', 'Please Select Item !!');
        }
    }

    // public function index(Request $request)
    // {

    //     try {
    //         if ($this->PermissionActionMenu('potracking')->r == 1) {
    //             return view('po-tracking/index');
    //         } else {
    //             return redirect('potracking')->with('err_message', 'Access denied!');
    //         }
    //     } catch (Exception $e) {
    //         $this->ErrorLog($e);
    //         return redirect()->back()->with('error', 'Error Request, Exception Error ');
    //     }
    // }
    public function index(Request $request)

    {

        try {
            if ($this->PermissionActionMenu('potracking')->r == 1) {
                $actionmenu =  $this->PermissionActionMenu('potracking');


                $title       = 'Dashboard';
                /* Cek Ticket yang kadaluarsa v2 */
                $date_now = Carbon::now()->format('Y-m-d');
                $get_ticket = DetailTicket::whereIn('status', ['A', 'D', 'P'])->where('DeliveryDate', '<', $date_now)->get();

                if (count($get_ticket) > 0) {
                    $get_ticket = DetailTicket::whereIn('status', ['A', 'D', 'P'])->where('DeliveryDate', '<', $date_now)->whereIn('Plant', ['UCKR', 'PCKR', '1000'])
                        ->update([
                            'status' => 'E',
                            'LastModifiedBy' => 'Update Dashboard',
                            'updated_at' => Carbon::now()
                        ]);
                } else {
                    $get_ticket = '';
                }
                $datamaterial = PdiHistory::select('Material', 'NetPrice')->where('NetPrice', '>', 10000000)->groupBy('Material')->get();
                foreach ($datamaterial as $items) {
                    $material[] = $items->Material;
                }
                /* END of Cek Ticket yang kadaluarsa v2 */
                if ($actionmenu->group == 27 || $actionmenu->group == 28 || $actionmenu->group == 29) {
                    $totaldeliverytoday = DetailTicket::selectRaw('TicketID,COUNT(distinct(TicketID)) as TotalTicket, COUNT(ItemNumber) as TotalItem')->whereDate('DeliveryDate', $date_now)->whereIn('status', ['A', 'D'])->where('CreatedBy', Auth::user()->name)->distinct('TicketID', 'ItemNumber')->first();
                    $totalarrivedtoday = DetailTicket::selectRaw('TicketID,COUNT(distinct(TicketID)) as TotalTicket, COUNT(ItemNumber) as TotalItem')->whereDate('SecurityDate', $date_now)->where('status', 'S')->where('CreatedBy', Auth::user()->name)->distinct('TicketID', 'ItemNumber')->first();
                    $totaldeliverypo = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('status', ['A', 'D'])->where('CreatedBy', Auth::user()->name)->distinct('TicketID', 'ItemNumber')->count();
                    $totalarrivedpo = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->where('status', 'S')->where('CreatedBy', Auth::user()->name)->distinct('TicketID', 'ItemNumber')->count();
                    $mostmaterial = DetailTicket::selectRaw('Material,Description, SUM(Quantity) as TotalMaterial')->groupBy('Material')->orderByDesc('TotalMaterial')->where('status', 'S')->whereIn('Material', $material)->where('CreatedBy', Auth::user()->name)->first();
                    $mostVvendors = DetailTicket::selectRaw('CreatedBy,Number, COUNT(distinct(TicketID)) as TotalVendors , COUNT(ItemNumber) as TotalItem')->groupBy('CreatedBy')->orderByDesc('TotalVendors')->where('status', 'S')->where('CreatedBy', Auth::user()->name)->first();
                    $dataticketarrived   = DetailTicket::select(DetailTicket::raw('COUNT(distinct(TicketID)) as TotalTicket,YEAR(SecurityDate) year, MONTH(SecurityDate) month'))->whereNotIn('SecurityDate', ['', 'NULL'])
                        ->where('status', 'S')->where('CreatedBy', Auth::user()->name)->groupby('year', 'month')
                        ->get();
                } else {
                    $totaldeliverytoday = DetailTicket::selectRaw('TicketID,COUNT(distinct(TicketID)) as TotalTicket, COUNT(ItemNumber) as TotalItem')->whereDate('DeliveryDate', $date_now)->whereIn('status', ['A', 'D'])->distinct('TicketID', 'ItemNumber')->first();
                    $totalarrivedtoday = DetailTicket::selectRaw('TicketID,COUNT(distinct(TicketID)) as TotalTicket, COUNT(ItemNumber) as TotalItem')->whereDate('SecurityDate', $date_now)->where('status', 'S')->distinct('TicketID', 'ItemNumber')->first();
                    $totaldeliverypo = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereIn('status', ['A', 'D'])->distinct('TicketID', 'ItemNumber')->count();
                    $totalarrivedpo = DetailTicket::select('Number', 'ItemNumber', 'TicketID')->whereNotIn('SecurityDate', ['', 'NULL'])->where('status', 'S')->distinct('TicketID', 'ItemNumber')->count();
                    $mostmaterial = DetailTicket::selectRaw('Material,Description, SUM(Quantity) as TotalMaterial')->groupBy('Material')->orderByDesc('TotalMaterial')->whereIn('Material', $material)->where('status', 'S')->first();
                    $mostVvendors = DetailTicket::selectRaw('CreatedBy,Number, COUNT(distinct(TicketID)) as TotalVendors , COUNT(ItemNumber) as TotalItem')->groupBy('CreatedBy')->orderByDesc('TotalVendors')->where('status', 'S')->first();
                    $dataticketarrived   = DetailTicket::select(DetailTicket::raw('COUNT(distinct(TicketID)) as TotalTicket,YEAR(SecurityDate) year, MONTH(SecurityDate) month'))->whereNotIn('SecurityDate', ['', 'NULL'])
                        ->where('status', 'S')->groupby('year', 'month')
                        ->get();
                }

                $max = 0;
                $totals = 0;
                $monthticket = array();
                foreach ($dataticketarrived as $dataarrived) {
                    $monthtotalticket = $dataarrived['month'];
                    $dataticket = $dataarrived['TotalTicket'];
                    if ($monthtotalticket > $totals) {
                        $max = $monthtotalticket;
                    }
                    $monthticket[$monthtotalticket] = $dataticket;
                }

                for ($i = 1; $i <= $max; $i++) {
                    $monthtotalticket = $i;
                    $dataticket = 0;
                    if (isset($monthticket[$i])) {
                        $dataticket = $monthticket[$i];
                    }
                    $var5[] = [
                        'y' => $dataticket,

                    ];
                }
                if (empty($var5)) {
                    $ticketchart = 0;
                } else {
                    $ticketchart =  json_encode($var5);
                }

                //totaluser
                $datavendor = UserVendor::all();
                $vendor = [];
                foreach ($datavendor as $item) {
                    $vendor[] = $item->VendorCode;
                }
                $datauser    =  VwUserRoleGroup::select('username')->where('group', 15)->get();
                foreach ($datauser as $item) {
                    $user[] = $item->username;
                }
                array_push($user, "", "NULL");
                $datauseractive =  LogHistory::whereDate('created_at', Carbon::today())->whereNotIn('CreatedBy', $user)->groupBy('CreatedBy')->get()->count();
                $datainternalactive = LogHistory::whereNotIn('user', $vendor)->whereDate('created_at', Carbon::today())->groupBy('CreatedBy')->get()->count();
                $datavendoractive = LogHistory::whereIn('user', $vendor)->whereDate('created_at', Carbon::today())->groupBy('CreatedBy')->get()->count();
                $datatotaluser =  LogHistory::groupBy('CreatedBy')->whereNotIn('CreatedBy', $user)->get()->count();
                $datatotalinternal = LogHistory::whereNotIn('user', $vendor)->whereNotIn('CreatedBy', $user)->groupBy('CreatedBy')->get()->count();
                $datatotalvendor = LogHistory::whereIn('user', $vendor)->whereNotIn('CreatedBy', ['', 'NULL'])->groupBy('CreatedBy')->get()->count();


                //endtambahan


                //endtambahan
                return view(
                    'po-tracking/dashboard',
                    compact(
                        'actionmenu',
                        'totaldeliverytoday',
                        'totalarrivedtoday',
                        'totaldeliverypo',
                        'totalarrivedpo',
                        'mostmaterial',
                        'mostVvendors',
                        'ticketchart',
                        'datauseractive',
                        'datainternalactive',
                        'datavendoractive',
                        'datatotaluser',
                        'datatotalinternal',
                        'datatotalvendor'
                    )
                );
            } else {
                return redirect('potracking')->with('err_message', 'Access denied!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
