<?php

namespace App\Http\Controllers\Cms\PoTracking;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Exception;

//Models
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\ParkingInvoiceSPBNumber;
use App\Models\Table\PoTracking\Po;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\UserVendorWTHTax;
use App\Models\Table\PoTracking\WithholdingTaxCode;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\Notification;
use App\Models\Table\PoTracking\ParkingInvoice;
use App\Models\Table\PoTracking\ParkingInvoiceDocument;
use App\Models\Table\PoTracking\ParkingInvoiceLog;
use App\Models\Table\PoTracking\MigrationProcurementPO;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwTotalGRPdi;

class ParkingInvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (
                $this->PermissionMenu('potracking') == 0
            ){
                return redirect('/')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    //----- INTERNAL USER ------
    public function open_parkinginvoice(Request $request)
    {
        if($this->PermissionActionMenu('openparkinginvoice')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('openparkinginvoice');

            $openIR = Pdi::where('OpenQuantityIR','>', 0)
                ->whereColumn('OpenQuantity','<','Quantity')
                ->whereColumn('OpenQuantityIR','>=','OpenQuantity')
                ->distinct()->pluck('POID');
            $NoSPBygdiparking = ParkingInvoiceSPBNumber::where('Status', 1)->distinct()->pluck('SPB_Number');

            if($request->reset == 1){
                $request->session()->forget('kolom');
                $request->session()->forget('isi');
            }

            if(isset($request->kolom) && $request->isi != ''){
                $request->session()->put('kolom',$request->kolom);
                $request->session()->put('isi',$request->isi);
            }
            if($request->session()->get('kolom')){

                $dataPO = Pdi::leftjoin('po','purchasingdocumentitem.POID','=','po.ID')
                    ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                    ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                    ->join('vw_totalgr_pdi',function($join){
                        $join->on('po.ID','=','vw_totalgr_pdi.POID');
                        $join->on('purchasingdocumentitem.ItemNumber','=','vw_totalgr_pdi.ItemNumber');
                    })
                    ->whereIn('po.ID',$openIR)
                    ->whereNotIn('purchasingdocumentitem.RefDocumentNumber',$NoSPBygdiparking)
                    ->whereColumn('vw_totalgr_pdi.totalir','<','vw_totalgr_pdi.totalgr')
                    ->where('vw_totalgr_pdi.totalgr','>', 0)
                    ->whereNotNull('uservendors.Name')
                    ->where($request->session()->get('kolom'), 'LIKE', '%'.$request->session()->get('isi').'%')
                    ->selectRaw('po.* ,uservendors.*, vw_totalgr_pdi.*, migration_procurement_po.procurement_code AS NRP ,purchasingdocumentitem.ItemNumber, purchasingdocumentitem.Material, purchasingdocumentitem.Description, purchasingdocumentitem.Quantity')
                    ->distinct()
                    ->get();
            }else{
                $dataPO = Pdi::leftjoin('po','purchasingdocumentitem.POID','=','po.ID')
                    ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                    ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                    ->join('vw_totalgr_pdi',function($join){
                        $join->on('purchasingdocumentitem.POID','=','vw_totalgr_pdi.POID');
                        $join->on('purchasingdocumentitem.ItemNumber','=','vw_totalgr_pdi.ItemNumber');
                    })
                    ->whereIn('po.ID',$openIR)
                    ->whereNotIn('purchasingdocumentitem.RefDocumentNumber',$NoSPBygdiparking)
                    ->whereColumn('vw_totalgr_pdi.totalir','<','vw_totalgr_pdi.totalgr')
                    ->where('vw_totalgr_pdi.totalgr','>', 0)
                    ->whereNotNull('uservendors.Name')
                    ->selectRaw('po.* ,uservendors.*, vw_totalgr_pdi.*, migration_procurement_po.procurement_code AS NRP ,purchasingdocumentitem.ItemNumber, purchasingdocumentitem.Material, purchasingdocumentitem.Description, purchasingdocumentitem.Quantity')
                    ->distinct()
                    ->paginate(10);
            }

            return view(
                'po-tracking/parkinginvoice/open',
                compact(
                    'actionmenu',
                    'dataPO'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
        
    }

    public function onprocess_parkinginvoice()
    {
        if($this->PermissionActionMenu('onprocessparkinginvoice')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('onprocessparkinginvoice');

            $dataPI = ParkingInvoice::leftjoin('po','parkinginvoice.Number','=','po.Number')
                ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                ->whereNotIn('parkinginvoice.Status',['Approve Parking','Reject Parking'])
                ->selectRaw('*,
                    parkinginvoice.created_at AS created_at,
                    parkinginvoice.Status AS Status,
                    parkinginvoice.Reference AS Reference,
                    migration_procurement_po.procurement AS NRP
                ')
                ->groupBy('parkinginvoice.created_at')
                ->orderBy('parkinginvoice.created_at','ASC')
                ->get();

            return view(
                'po-tracking/parkinginvoice/onprocess',
                compact(
                    'actionmenu',
                    'dataPI'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
    }

    public function history_parkinginvoice()
    {
        if($this->PermissionActionMenu('historyparkinginvoice')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('historyparkinginvoice');

            $dataPI = ParkingInvoice::leftjoin('po','parkinginvoice.Number','=','po.Number')
                ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                ->whereIn('parkinginvoice.Status',['Approve Parking','Reject Parking'])
                ->selectRaw('*,
                    parkinginvoice.created_at AS created_at,
                    parkinginvoice.Status AS Status,
                    parkinginvoice.Reference AS Reference,
                    migration_procurement_po.procurement AS NRP
                ')
                ->groupBy('parkinginvoice.created_at')
                ->orderBy('parkinginvoice.ReceiveDocumentDate','DESC')
                ->get();

            return view(
                'po-tracking/parkinginvoice/history',
                compact(
                    'actionmenu',
                    'dataPI'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
    }

    //------ VENDOR -------
    public function open_parkinginvoice_vendor()
    {
        if($this->PermissionActionMenu('openparkinginvoicevendor')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('openparkinginvoicevendor');

            $openIR = Pdi::select('POID')
                ->where('OpenQuantityIR','>', 0)
                ->whereColumn('OpenQuantity','<','Quantity')
                ->whereColumn('OpenQuantityIR','>=','OpenQuantity')
                ->distinct()->get()->toArray();

            $NoSPBygdiparking = ParkingInvoiceSPBNumber::select('SPB_Number')
                ->where('Status', 1)
                ->distinct()->get()->toArray();
            $dataPO = Pdi::leftjoin('po','purchasingdocumentitem.POID','=','po.ID')
                ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                ->leftjoin('vw_totalgr_pdi',function($join){
                    $join->on('po.ID','=','vw_totalgr_pdi.POID');
                    $join->on('purchasingdocumentitem.ItemNumber','=','vw_totalgr_pdi.ItemNumber');
                })
                ->where('po.VendorCode', Auth::user()->email)
                ->whereIn('po.ID',$openIR)
                ->whereNotIn('purchasingdocumentitem.RefDocumentNumber',$NoSPBygdiparking)
                ->whereColumn('vw_totalgr_pdi.totalir','<','vw_totalgr_pdi.totalgr')
                ->selectRaw('po.* ,uservendors.*, vw_totalgr_pdi.*, migration_procurement_po.procurement_code AS NRP ,purchasingdocumentitem.ItemNumber, purchasingdocumentitem.Material, purchasingdocumentitem.Description, purchasingdocumentitem.Quantity')
                ->distinct()
                ->get();

            // dd($dataPO->toArray());
            return view(
                'po-tracking/parkinginvoice/vendor_open',
                compact(
                    'actionmenu',
                    'dataPO'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
        
    }

    public function onprocess_parkinginvoice_vendor()
    {
        if($this->PermissionActionMenu('onprocessparkinginvoicevendor')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('onprocessparkinginvoicevendor');

            $dataPI = ParkingInvoice::leftjoin('po','parkinginvoice.Number','=','po.Number')
                ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                ->whereNotIn('parkinginvoice.Status',['Approve Parking','Reject Parking'])
                ->where('po.VendorCode', Auth::user()->email)
                ->selectRaw('*,
                    parkinginvoice.created_at AS created_at,
                    parkinginvoice.Status AS Status,
                    parkinginvoice.Reference AS Reference,
                    migration_procurement_po.procurement AS NRP
                ')
                ->groupBy('parkinginvoice.created_at')
                ->orderBy('parkinginvoice.created_at','ASC')
                ->get();

            return view(
                'po-tracking/parkinginvoice/vendor_onprocess',
                compact(
                    'actionmenu',
                    'dataPI'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
    }

    public function history_parkinginvoice_vendor()
    {
        if($this->PermissionActionMenu('historyparkinginvoicevendor')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('historyparkinginvoicevendor');

            $dataPI = ParkingInvoice::leftjoin('po','parkinginvoice.Number','=','po.Number')
                ->leftjoin('uservendors','po.VendorCode','=','uservendors.VendorCode')
                ->leftjoin('migration_procurement_po','migration_procurement_po.procurement','=','po.CreatedBy')
                ->whereIn('parkinginvoice.Status',['Approve Parking','Reject Parking'])
                ->where('po.VendorCode', Auth::user()->email)
                ->selectRaw('*,
                    parkinginvoice.created_at AS created_at,
                    parkinginvoice.Status AS Status,
                    parkinginvoice.Reference AS Reference,
                    migration_procurement_po.procurement AS NRP
                ')
                ->groupBy('parkinginvoice.created_at')
                ->orderBy('parkinginvoice.created_at','ASC')
                ->get();

            return view(
                'po-tracking/parkinginvoice/vendor_history',
                compact(
                    'actionmenu',
                    'dataPI'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
    }
    

    //------------ PROSESNYA DISINI --------------

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
    public function parkinginvoice(Request $request)
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
                } else if ($this->PermissionActionMenu('subcontractorongoing-nonmanagement')) {
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
                    //tambahan nomor parking
                    $countParkingNumber = ParkingInvoice::select('ParkingNumber')->distinct()->get()->count();
                    $parkingnumber = 'PI-'.str_pad(($countParkingNumber), 7, "0", STR_PAD_LEFT);

                    $create1 = ParkingInvoice::where('InvoiceNumber', $request->invoice_no)
                        ->where('Status', "Request Parking")->where('IsReject', 0)
                        ->update([
                            'ParkingNumber' => $parkingnumber,
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
                    } else { //jika parking to sap gagal
                        $err_message1 = 'already processing';
                        $err_message2 = 'is not allowed';
                        if(strpos($arr['message'],$err_message1) !== false){
                            return redirect()->back()->with('err_message', 'Parking PO Number ' . $header->Number . ' Failed | ' . $arr['message']);  
                        }
                        elseif(strpos($arr['message'],$err_message2) !== false){
                            return redirect()->back()->with('err_message', 'Parking PO Number ' . $header->Number . ' Failed | ' . $arr['message']);  
                        }
                        else{
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

                    // $request->validate([
                    //     'filename.*' => 'required|mimes:PDF,pdf,Pdf',
                    // ]);

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
                    
                    //tambahan nomor parking
                    // $countinvoice = ParkingInvoice::select('created_at')->distinct()->get()->count();
                    // $parkingnumber = 'PI'.Carbon::now()->format('mY').'-'.str_pad(($countinvoice + 1), 7, "0", STR_PAD_LEFT);

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
                            'ParkingNumber'         => NULL,
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

    //-------------------Tambahan Log Parking 28 Ags 2023 -----------
    public function logparkinginvoice()
    {
        if($this->PermissionActionMenu('logparkinginvoice')->r==1 ){

            $actionmenu =  $this->PermissionActionMenu('logparkinginvoice');

            $dataPI = ParkingInvoiceLog::orderBy('created_at','DESC')->get();

            return view(
                'po-tracking/parkinginvoice/log',
                compact(
                    'actionmenu',
                    'dataPI'
                )
            );
        }
        else{
            return redirect('/')->with('err_message', 'Access denied!');
        }
    }
}
