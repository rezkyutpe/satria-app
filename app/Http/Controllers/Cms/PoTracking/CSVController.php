<?php

namespace App\Http\Controllers\Cms\PoTracking;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Models\Table\PoTracking\Potrackingsap;
use App\Models\Table\PoTracking\Po;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PdiHistory;
use App\Models\Table\PoTracking\LogErrorCSV;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\MigrationPO;
use App\Models\Table\PoTracking\MigrationProcurementPO;

use App\Models\View\PoTracking\VwOpenQuantity;
use App\Models\View\PoTracking\VwHistoryall;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwongoingAll;

use Exception;

class CSVController extends Controller
{

    public function uploadCSVtoDB()
    {
        $CSVfilename = '';
        try {
            $allFiles       = File::Files(public_path('potracking/sap'));
            $latestFile     = $allFiles[0];
            if($latestFile != false){
                if($latestFile->getSize() != 0){
                    $CSVfilename    = $latestFile->getFilename();
                    $location       = public_path('potracking/sap/'.$CSVfilename);
                    $worksheet      = fopen("{$location}", "r");
                    $flag           = true;
                    if ($worksheet !== FALSE) {
                        while ($worksheet_data = fgetcsv($worksheet,0,';')){
                            if($flag){$flag = false; continue;}
                            $dataCSVPotracking[] = $worksheet_data;
                        }
                        fclose($worksheet);
                    } else {
                        echo 'File not found!';
                    }

                    /* Get Migration Procurement PO */
                    $get_proc = MigrationProcurementPO::select('name','procurement','procurement_code')->get()->toArray();

                    /* Get Old Vendor Code*/
                    $get_vendorcode = UserVendor::select('VendorCode','VendorCode_new')->get()->toArray();

                    $get_migration_po = MigrationPO::select('ebeln','submi','ernam','name1')->distinct()->get()->toArray();

                    foreach ($dataCSVPotracking as $dataCSV) {

                        // loekz
                        if ($dataCSV[3] == "#N/A" || $dataCSV[3] == '') {
                            $dataCSV[3] = NULL;
                        }

                        // udate
                        if ($dataCSV[4] == '#N/A' || $dataCSV[4] == '00.00.0000' || $dataCSV[4] == '' || $dataCSV[4] == '0') {
                            $dataCSV[4] = NULL;
                        }
                        else {
                            $dataCSV[4]  = Carbon::createFromFormat('d.m.Y', $dataCSV[4])->format('Y-m-d');
                        }

                        // aedat -> 'aedat' menjadi 'bedat' pada tanggal 15 Agustus 2022
                        if ($dataCSV[5] == '#N/A' || $dataCSV[5] == '00.00.0000' || $dataCSV[5] == '' || $dataCSV[5] == '0') {
                            $dataCSV[5] = NULL;
                        }
                        else {
                            $dataCSV[5]  = Carbon::createFromFormat('d.m.Y', $dataCSV[5])->format('Y-m-d');
                        }

                        // ernam dan name2
                        foreach($get_proc as $proc){
                            if($dataCSV[6] == $proc['procurement_code']){
                                $dataCSV[6] = $proc['procurement'];
                                $dataCSV[7] = $proc['name'];
                                break;
                            }
                        }

                        foreach($get_migration_po as $item){
                            if($dataCSV[1] == $item['ebeln']){
                                $dataCSV[6] = $item['ernam'];
                                $dataCSV[7] = $item['name1'];
                                break;
                            }
                        }

                        // menge
                        $dataCSV[8] = str_replace(",", "", $dataCSV[8]);

                        // lifnr
                        foreach($get_vendorcode as $item){
                            if(ltrim($dataCSV[9], '0') == $item['VendorCode_new']){
                                $dataCSV[9] = $item['VendorCode'];
                                break;
                            }
                        }

                        // belnr
                        if ($dataCSV[15] == "#N/A" || $dataCSV[15] == '') {
                            $dataCSV[15] = NULL;
                        }

                        // buzei
                        if ($dataCSV[16] == "#N/A" || $dataCSV[16] == '' || $dataCSV[16] == '0000') {
                            $dataCSV[16] = NULL;
                        }

                        // bewtp
                        if ($dataCSV[17] == "#N/A" || $dataCSV[17] == '') {
                            $dataCSV[17] = NULL;
                        }

                        // bwart
                        if ($dataCSV[18] == "#N/A" || $dataCSV[18] == '') {
                            $dataCSV[18] = NULL;
                        }

                        // budat
                        if ($dataCSV[19] == '#N/A' || $dataCSV[19] == '00.00.0000' || $dataCSV[19] == '' || $dataCSV[19] == '0') {
                            $dataCSV[19] = NULL;
                        }
                        else {
                            $dataCSV[19]  = Carbon::createFromFormat('d.m.Y', $dataCSV[19])->format('Y-m-d');
                        }

                        // grmen
                        $dataCSV[20] = str_replace(",", "", $dataCSV[20]);

                        // wrbtr
                        $dataCSV[21] = str_replace(",", "", $dataCSV[21]);
                        // $dataCSV[21] = str_replace(",", ".", $dataCSV[21]);

                        // waer1
                        if ($dataCSV[22] == "#N/A" || $dataCSV[22] == '') {
                            $dataCSV[22] = NULL;
                        }

                        // elikz
                        if ($dataCSV[23] == "#N/A" || $dataCSV[23] == '') {
                            $dataCSV[23] = NULL;
                        }

                        // erekz
                        if ($dataCSV[24] == "#N/A" || $dataCSV[24] == '') {
                            $dataCSV[24] = NULL;
                        }

                        // knttp
                        if ($dataCSV[25] == "#N/A" || $dataCSV[25] == '') {
                            $dataCSV[25] = NULL;
                        }

                        // banfn
                        if ($dataCSV[27] == "#N/A" || $dataCSV[27] == '') {
                            $dataCSV[27] = NULL;
                        }else{
                            $dataCSV[27] = ltrim($dataCSV[27], '0');
                        }

                        // frgdt
                        if ($dataCSV[29] == '#N/A' || $dataCSV[29] == '00.00.0000' || $dataCSV[29] == '' || $dataCSV[29] == '0') {
                            $dataCSV[29] = NULL;
                        }
                        else {
                            $dataCSV[29]  = Carbon::createFromFormat('d.m.Y', $dataCSV[29])->format('Y-m-d');
                        }

                        // cpudt
                        if ($dataCSV[30] == '#N/A' || $dataCSV[30] == '00.00.0000' || $dataCSV[30] == '' || $dataCSV[30] == '0') {
                            $dataCSV[30] = NULL;
                        }
                        else {
                            $dataCSV[30]  = Carbon::createFromFormat('d.m.Y', $dataCSV[30])->format('Y-m-d');
                        }

                        // cputm
                        if ($dataCSV[31] == '#N/A' || $dataCSV[31] == '00:00:00' || $dataCSV[31] == '' || $dataCSV[31] == '0') {
                            $dataCSV[31] = NULL;
                        }
                        else {
                            $dataCSV[31]  = Carbon::parse($dataCSV[31])->format('H:i:s');
                        }

                        // reewr
                        $dataCSV[32] = str_replace(",", "", $dataCSV[32]);
                        // $dataCSV[32] = str_replace(",", ".", $dataCSV[32]);

                        // werks
                        if ($dataCSV[34] == "#N/A" || $dataCSV[34] == '') {
                            $dataCSV[34] = NULL;
                        }

                        // bldat
                        if ($dataCSV[35] == '#N/A' || $dataCSV[35] == '00.00.0000' || $dataCSV[35] == '' || $dataCSV[35] == '0') {
                            $dataCSV[35] = NULL;
                        }
                        else {
                            $dataCSV[35]  = Carbon::createFromFormat('d.m.Y', $dataCSV[35])->format('Y-m-d');
                        }

                        // erna1
                        if ($dataCSV[36] == "#N/A" || $dataCSV[36] == '') {
                            $dataCSV[36] = NULL;
                        }
                        
                        // vbeln
                        if ($dataCSV[37] == "#N/A" || $dataCSV[37] == '') {
                            $dataCSV[37] = NULL;
                        }

                        //maktx
                        $dataCSV[39]      = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $dataCSV[39]);
                        
                        // eindt
                        if ($dataCSV[40] == '#N/A' || $dataCSV[40] == '00.00.0000' || $dataCSV[40] == '' || $dataCSV[40] == '0') {
                            $dataCSV[40] = NULL;
                        }
                        else {
                            $dataCSV[40]  = Carbon::createFromFormat('d.m.Y', $dataCSV[40])->format('Y-m-d');
                        }

                        // bstmg
                        $dataCSV[41] = str_replace(",", "", $dataCSV[41]);

                        // outgr
                        $dataCSV[42] = str_replace(",", "", $dataCSV[42]);
                        
                        // podat
                        if ($dataCSV[43] == '#N/A' || $dataCSV[43] == '00.00.0000' || $dataCSV[43] == '' || $dataCSV[43] == 0) {
                            $dataCSV[43] = NULL;
                        }
                        else {
                            $dataCSV[43]  = Carbon::createFromFormat('d.m.Y', $dataCSV[43])->format('Y-m-d');
                        }

                        // netpr
                        $dataCSV[44] = str_replace(",", "", $dataCSV[44]);
                        // $dataCSV[44] = str_replace(".", "", $dataCSV[44]);
                        
                        // idnlf
                        if ($dataCSV[45] == "#N/A" || $dataCSV[45] == '') {
                            $dataCSV[45] = NULL;
                        }
                        
                        // badat
                        if ($dataCSV[46] == '#N/A' || $dataCSV[46] == '00.00.0000' || $dataCSV[46] == '' || $dataCSV[46] == '0') {
                            $dataCSV[46] = NULL;
                        }
                        else {
                            $dataCSV[46]  = Carbon::createFromFormat('d.m.Y', $dataCSV[46])->format('Y-m-d');
                        }

                        //txz01
                        $dataCSV[47]      = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $dataCSV[47]);
                        
                        // frgkz
                        if ($dataCSV[48] == "#N/A" || $dataCSV[48] == '') {
                            $dataCSV[48] = NULL;
                        }

                        // xblnr
                        if ($dataCSV[49] == "#N/A" || $dataCSV[49] == '') {
                            $dataCSV[49] = NULL;
                        }

                        if(isset($dataCSV[51])){
                            // SHKZG
                            if ($dataCSV[50] == "#N/A" || $dataCSV[50] == '') {
                                $dataCSV[50] = NULL;
                            }

                            //CHANGENR
                            if ($dataCSV[52] == "#N/A" || $dataCSV[52] == '') {
                                $dataCSV[52] = NULL;
                            }

                            //NAME_FIRST
                            if ($dataCSV[53] == "#N/A" || $dataCSV[53] == '') {
                                $dataCSV[53] = NULL;
                            }

                            //STRAS
                            if ($dataCSV[54] == "#N/A" || $dataCSV[54] == '') {
                                $dataCSV[54] = NULL;
                            }

                            //ORT01
                            if ($dataCSV[55] == "#N/A" || $dataCSV[55] == '') {
                                $dataCSV[55] = NULL;
                            }

                            //PSTLZ
                            if ($dataCSV[56] == "#N/A" || $dataCSV[56] == '0' || $dataCSV[56] == '000-000' || $dataCSV[56] == '') {
                                $dataCSV[56] = NULL;
                            }

                            //TELF1
                            if ($dataCSV[57] == "#N/A" || $dataCSV[57] == '') {
                                $dataCSV[57] = NULL;
                            }

                            //TELFX
                            if ($dataCSV[58] == "#N/A" || $dataCSV[58] == '') {
                                $dataCSV[58] = NULL;
                            }

                            //BOLNR
                            if ($dataCSV[59] == "#N/A" || $dataCSV[59] == '') {
                                $dataCSV[59] = NULL;
                            }

                            //INCO2
                            if ($dataCSV[60] == "#N/A" || $dataCSV[60] == '0' || $dataCSV[60] == '') {
                                $dataCSV[60] = NULL;
                            }

                            //WERKS_2
                            if ($dataCSV[62] == "#N/A" || $dataCSV[62] == '0' || $dataCSV[62] == '') {
                                $dataCSV[62] = NULL;
                            }

                            //LGORT
                            if ($dataCSV[63] == "#N/A" || $dataCSV[63] == '0' || $dataCSV[63] == '') {
                                $dataCSV[63] = NULL;
                            }

                            //LFBNR
                            if ($dataCSV[66] == "#N/A" || $dataCSV[66] == '0' || $dataCSV[66] == '') {
                                $dataCSV[66] = NULL;
                            }

                            //LFPOS
                            if ($dataCSV[67] == "#N/A" || $dataCSV[67] == '0' || $dataCSV[67] == '') {
                                $dataCSV[67] = NULL;
                            }


                        }

                        if(isset($dataCSV[51])){
                            $dataInsertCSV = [
                                'bsart'         => $dataCSV[0],
                                'ebeln'         => $dataCSV[1],
                                'ebelp'         => ltrim($dataCSV[2], '0'),
                                'loekz'         => $dataCSV[3],
                                'udate'         => $dataCSV[4],
                                'aedat'         => $dataCSV[5],
                                'ernam'         => $dataCSV[6],
                                'name2'         => $dataCSV[7],
                                'menge'         => $dataCSV[8],
                                'lifnr'         => ltrim($dataCSV[9], '0'),
                                'name1'         => $dataCSV[10],
                                'zterm'         => $dataCSV[11],
                                'ekgrp'         => $dataCSV[12],
                                'waers'         => $dataCSV[13],
                                'gjahr'         => $dataCSV[14],
                                'belnr'         => $dataCSV[15],
                                'buzei'         => $dataCSV[16],
                                'bewtp'         => $dataCSV[17],
                                'bwart'         => $dataCSV[18],
                                'budat'         => $dataCSV[19],
                                'grmen'         => $dataCSV[20],
                                'wrbtr'         => $dataCSV[21],
                                'waer1'         => $dataCSV[22],
                                'elikz'         => $dataCSV[23],
                                'erekz'         => $dataCSV[24],
                                'knttp'         => $dataCSV[25],
                                'plifz'         => $dataCSV[26],
                                'banfn'         => $dataCSV[27],
                                'bnfpo'         => $dataCSV[28],
                                'frgdt'         => $dataCSV[29],
                                'cpudt'         => $dataCSV[30],
                                'cputm'         => $dataCSV[31],
                                'reewr'         => $dataCSV[32],
                                'matnr'         => $dataCSV[33],
                                'werks'         => $dataCSV[34],
                                'bldat'         => $dataCSV[35],
                                'erna1'         => $dataCSV[36],
                                'vbeln'         => $dataCSV[37],
                                'posnr'         => $dataCSV[38],
                                'maktx'         => $dataCSV[39],
                                'eindt'         => $dataCSV[40],
                                'bstmg'         => $dataCSV[41],
                                'outgr'         => $dataCSV[42],
                                'podat'         => $dataCSV[43],
                                'netpr'         => (double)$dataCSV[44],
                                'idnlf'         => $dataCSV[45],
                                'badat'         => $dataCSV[46],
                                'txz01'         => $dataCSV[47],
                                'frgkz'         => $dataCSV[48],
                                'xblnr'         => $dataCSV[49],
                                'SHKZG'         => $dataCSV[50],
                                'TEXT'          => $dataCSV[51],
                                'CHANGENR'      => $dataCSV[52],
                                'NAME_FIRST'    => $dataCSV[53],
                                'STRAS'         => $dataCSV[54],
                                'ORT01'         => $dataCSV[55],
                                'PSTLZ'         => $dataCSV[56],
                                'TELF1'         => $dataCSV[57],
                                'TELFX'         => $dataCSV[58],
                                'BOLNR'         => $dataCSV[59],
                                'INCO2'         => $dataCSV[60],
                                'MWSKZ'         => $dataCSV[61],
                                'WERKS_2'       => $dataCSV[62],
                                'LGORT'         => $dataCSV[63],
                                'MEINS'         => $dataCSV[64],
                                'BUKRS'         => $dataCSV[65],
                                'LFBNR'         => $dataCSV[66],
                                'LFPOS'         => ltrim($dataCSV[67], '0')
                            ];
                        }else{
                            $dataInsertCSV = [
                                'bsart'         => $dataCSV[0],
                                'ebeln'         => $dataCSV[1],
                                'ebelp'         => ltrim($dataCSV[2], '0'),
                                'loekz'         => $dataCSV[3],
                                'udate'         => $dataCSV[4],
                                'aedat'         => $dataCSV[5],
                                'ernam'         => $dataCSV[6],
                                'name2'         => $dataCSV[7],
                                'menge'         => $dataCSV[8],
                                'lifnr'         => ltrim($dataCSV[9], '0'),
                                'name1'         => $dataCSV[10],
                                'zterm'         => $dataCSV[11],
                                'ekgrp'         => $dataCSV[12],
                                'waers'         => $dataCSV[13],
                                'gjahr'         => $dataCSV[14],
                                'belnr'         => $dataCSV[15],
                                'buzei'         => $dataCSV[16],
                                'bewtp'         => $dataCSV[17],
                                'bwart'         => $dataCSV[18],
                                'budat'         => $dataCSV[19],
                                'grmen'         => $dataCSV[20],
                                'wrbtr'         => $dataCSV[21],
                                'waer1'         => $dataCSV[22],
                                'elikz'         => $dataCSV[23],
                                'erekz'         => $dataCSV[24],
                                'knttp'         => $dataCSV[25],
                                'plifz'         => $dataCSV[26],
                                'banfn'         => $dataCSV[27],
                                'bnfpo'         => $dataCSV[28],
                                'frgdt'         => $dataCSV[29],
                                'cpudt'         => $dataCSV[30],
                                'cputm'         => $dataCSV[31],
                                'reewr'         => $dataCSV[32],
                                'matnr'         => $dataCSV[33],
                                'werks'         => $dataCSV[34],
                                'bldat'         => $dataCSV[35],
                                'erna1'         => $dataCSV[36],
                                'vbeln'         => $dataCSV[37],
                                'posnr'         => $dataCSV[38],
                                'maktx'         => $dataCSV[39],
                                'eindt'         => $dataCSV[40],
                                'bstmg'         => $dataCSV[41],
                                'outgr'         => $dataCSV[42],
                                'podat'         => $dataCSV[43],
                                'netpr'         => (double)$dataCSV[44],
                                'idnlf'         => $dataCSV[45],
                                'badat'         => $dataCSV[46],
                                'txz01'         => $dataCSV[47],
                                'frgkz'         => $dataCSV[48],
                                'xblnr'         => $dataCSV[49],
                                'SHKZG'         => NULL,
                                'TEXT'          => NULL,
                                'CHANGENR'      => NULL,
                                'NAME_FIRST'    => NULL,
                                'STRAS'         => NULL,
                                'ORT01'         => NULL,
                                'PSTLZ'         => NULL,
                                'TELF1'         => NULL,
                                'TELFX'         => NULL,
                                'BOLNR'         => NULL,
                                'INCO2'         => NULL,
                                'MWSKZ'         => NULL,
                                'WERKS_2'       => NULL,
                                'LGORT'         => NULL,
                                'MEINS'         => NULL,
                                'BUKRS'         => NULL,
                                'LFBNR'         => NULL,
                                'LFPOS'         => NULL
                            ];
                        }
                        
                        $arrdataInsertCSV[]    = $dataInsertCSV;
                    }

                    // Mengosongkan data dari DB
                    Potrackingsap::truncate();

                    // Insert data ke DB
                    $chunks = array_chunk($arrdataInsertCSV, 500);
                    foreach ($chunks as $Insert) {
                        Potrackingsap::insert($Insert);
                    }

                    /* truncate + insert new data */
                    // $this->insertCSV($CSVfilename);

                    /* update data */
                    // $this->updateCSV($CSVfilename);

                    /* update data */
                    $this->updateCSV_v2($CSVfilename);

                    // Move File to History Folder
                    File::move(public_path('potracking/sap/'.$CSVfilename), public_path('potracking/sap/history/'. date('d-m-Y') .'_'.date('H-i-s').'_'.$CSVfilename));
                }
            }
        } catch (Exception $e) {
            // $this->ErrorLog($e);
            $message    = $e->getMessage();
            $code       = $e->getCode();
            $string     = $e->__toString();
            $create     = LogErrorCSV::create([
                'action'        => url()->current(),
                'filename'      => $CSVfilename,
                'code'          => $code,
                'message'       => $message,
                'ex_string'     => $string,
                'created_by'    => 'System'
            ]);
        }
    }

    public function updateCSV_v2($filename) //Update or Create data into table po,pdi,pdihistory,uservendors
    {
        try{
            $getPTS = Potrackingsap::orderBy('ebeln','asc')->orderBy('ebelp','asc')->get();
            foreach($getPTS as $a){
                if($a['bewtp'] == 'E'){
                    $var_GR = $a['bstmg'];
                }else{
                    $var_GR = $a['grmen'];
                }
                $updatePDI = [
                    'Number'                => $a['ebeln'],
                    'ItemNumber'            => $a['ebelp'],
                    'IsClosed'              => $a['loekz'],
                    'Quantity'              => $a['menge'],
                    'Currency'              => $a['waers'],
                    'PRNumber'              => $a['banfn'],
                    'PRReleaseDate'         => $a['frgdt'],
                    'Material'              => $a['matnr'],
                    'NetPrice'              => $a['netpr'],
                    'MaterialVendor'        => $a['idnlf'],
                    'PRCreateDate'          => $a['badat'],
                    'Description'           => $a['txz01'],

                    'DeliveryDate'          => $a['eindt'],
                    'GoodsReceiptQuantity'  => $var_GR,
                    'GoodsReceiptDate'      => $a['budat'],
                    'POCategory'            => $a['bewtp'],
                    'MovementType'          => $a['bwart'],

                    //Tambahan 19 Juli 2022
                    'DocumentNumber'        => $a['belnr'],
                    'DocumentNumberItem'    => $a['buzei'],
                    'PayTerms'              => $a['zterm'],
                    'CreatedBy'             => "SyncDataSAP",

                    //Tambahan 2 Sep 2022
                    'WorkTime'              => $a['plifz'],

                    //Tambahan 19 Sep 2022
                    'PRItem'                => $a['bnfpo'],
                    // 'NetValue'              => $a['reewr'],
                    'InboundNumber'         => $a['vbeln'],
                    'GoodsReceiptDate2'     => $a['cpudt'],
                    'GoodsReceiptTime2'     => $a['cputm'],
                    'PurchasingGroup'       => $a['ekgrp'],

                    //Tambahan kolom dari SAP HANA
                    'RefDocumentNumber'     => $a['xblnr'],
                    'IRDebetCredit'         => $a['SHKZG'],
                    'VAT'                   => $a['MWSKZ'],
                    'Plant'                 => $a['WERKS_2'],
                    'SLoc'                  => $a['LGORT'],
                    'UoM'                   => $a['MEINS'],

                    //Tambahan 12 Juni 2023
                    'DocumentNumberRef'     => $a['LFBNR'],
                    'DocumentNumberItemRef' => $a['LFPOS']
                ];

                $updatePO = [
                    'Number'                => $a['ebeln'],
                    'Type'                  => $a['bsart'],
                    'Date'                  => $a['aedat'],
                    'ReleaseDate'           => $a['podat'],
                    'PRNumber'              => $a['banfn'],
                    'PRCreateDate'          => $a['badat'],
                    'PRReleaseDate'         => $a['frgdt'],
                    'VendorCode'            => $a['lifnr'],
                    'PurchaseOrderCreator'  => $a['name2'],
                    'CreatedBy'             => $a['ernam'],
                    'LastModifiedBy'        => "SyncDataSAP",

                    //Tambahan kolom SAP HANA
                    'TermPayment'           => $a['TEXT'],
                    'ApproveBy'             => $a['CHANGENR'],
                    'ApproveName'           => $a['NAME_FIRST'],
                    'VendorAddress'         => $a['STRAS'],
                    'VendorCity'            => $a['ORT01'],
                    'VendorZip'             => $a['PSTLZ'],
                    'VendorPhone'           => $a['TELF1'],
                    'VendorFax'             => $a['TELFX'],
                    'BillOfLading'          => $a['BOLNR'],
                    'ShippingMethod'        => $a['INCO2'],
                    'CompanyCode'           => $a['BUKRS'],
                    
                ];

                $arr_updatePO[] = $updatePO;
                $arr_updatePDI[] = $updatePDI;

            }
            unset($getPTS);

            /* Update Table po */
            $POnumber = array();
            foreach($arr_updatePO as $val){
                if(!in_array($val['Number'], $POnumber)){
                    $POnumber[] = $val['Number'];
                    $POrecord[] = $val;
                }
            }

            $arr_updatePO = array_chunk($POrecord,50);
            foreach($arr_updatePO as $aa){
                foreach($aa as $aau){
                    Po::updateOrCreate(['Number' => $aau['Number']],$aau);
                }
            }
            unset($arr_updatePO);
            Po::Where('Number','')->delete();
            /* END */

            /* Update or Insert data */
                $arr_updatePDI = array_chunk($arr_updatePDI,100);
                $prevPOID = '';
                foreach($arr_updatePDI as $aac){
                    foreach($aac as $ac){
                        $getPOID = Po::where('Number',$ac['Number'])->first();
                        if(empty($getPOID)){
                            $ac['POID'] = $prevPOID;
                        }else{
                            $ac['POID'] = $getPOID['ID'];
                            $prevPOID = $getPOID['ID'];
                        }
                        $arr_fixPDI[] = $ac;
                        unset($ac['Number']);
                    }
                }
                $arr_chunk_fixPDI = array_chunk($arr_fixPDI,100);

                foreach($arr_chunk_fixPDI as $var){
                    foreach($var as $varu){

                        $getPDIH_1 = PdiHistory::where('POID',$varu['POID'])
                        ->where('ItemNumber',$varu['ItemNumber'])
                        ->first();

                        if(!empty($getPDIH_1)){
                            $getDocNumPDIH = PdiHistory::where('POID',$varu['POID'])
                            ->where('ItemNumber',$varu['ItemNumber'])
                            ->where('MovementType',$varu['MovementType'])
                            ->where('DocumentNumber',$varu['DocumentNumber'])
                            ->where('DocumentNumberItem',$varu['DocumentNumberItem'])
                            ->first();

                            if(!empty($getDocNumPDIH)){ //jika ada langsung update di table purchasingdocumentitemhistory
                                PdiHistory::where('ID',$getDocNumPDIH->ID)
                                ->update([
                                    'POID'                  => $varu['POID'],
                                    'Number'                => $varu['Number'],
                                    'ItemNumber'            => $varu['ItemNumber'],
                                    'IsClosed'              => $varu['IsClosed'],
                                    'Quantity'              => $varu['Quantity'],
                                    'Currency'              => $varu['Currency'],
                                    'PRNumber'              => $varu['PRNumber'],
                                    'PRReleaseDate'         => $varu['PRReleaseDate'],
                                    'Material'              => $varu['Material'],
                                    'NetPrice'              => $varu['NetPrice'],
                                    'MaterialVendor'        => $varu['MaterialVendor'],
                                    'PRCreateDate'          => $varu['PRCreateDate'],
                                    'Description'           => $varu['Description'],
                                    'DeliveryDate'          => $varu['DeliveryDate'],
                                    'GoodsReceiptQuantity'  => $varu['GoodsReceiptQuantity'],
                                    'GoodsReceiptDate'      => $varu['GoodsReceiptDate'],
                                    'POCategory'            => $varu['POCategory'],
                                    'MovementType'          => $varu['MovementType'],
                                    'DocumentNumber'        => $varu['DocumentNumber'],
                                    'DocumentNumberItem'    => $varu['DocumentNumberItem'],
                                    'PayTerms'              => $varu['PayTerms'],
                                    'WorkTime'              => $varu['WorkTime'],
                                    'PRItem'                => $varu['PRItem'],
                                    'InboundNumber'         => $varu['InboundNumber'],
                                    'GoodsReceiptDate2'     => $varu['GoodsReceiptDate2'],
                                    'GoodsReceiptTime2'     => $varu['GoodsReceiptTime2'],
                                    'PurchasingGroup'       => $varu['PurchasingGroup'],
                                    'RefDocumentNumber'     => $varu['RefDocumentNumber'],
                                    'IRDebetCredit'         => $varu['IRDebetCredit'],
                                    'VAT'                   => $varu['VAT'],
                                    'Plant'                 => $varu['Plant'],
                                    'SLoc'                  => $varu['SLoc'],
                                    'UoM'                   => $varu['UoM'],
                                    'DocumentNumberRef'     => $varu['DocumentNumberRef'],
                                    'DocumentNumberItemRef' => $varu['DocumentNumberItemRef']
                                ]);
                            }
                            else{
                                PdiHistory::insert([
                                    'POID'                  => $varu['POID'],
                                    'Number'                => $varu['Number'],
                                    'ItemNumber'            => $varu['ItemNumber'],
                                    'IsClosed'              => $varu['IsClosed'],
                                    'Quantity'              => $varu['Quantity'],
                                    'Currency'              => $varu['Currency'],
                                    'PRNumber'              => $varu['PRNumber'],
                                    'PRReleaseDate'         => $varu['PRReleaseDate'],
                                    'Material'              => $varu['Material'],
                                    'NetPrice'              => $varu['NetPrice'],
                                    'MaterialVendor'        => $varu['MaterialVendor'],
                                    'PRCreateDate'          => $varu['PRCreateDate'],
                                    'Description'           => $varu['Description'],
                                    'DeliveryDate'          => $varu['DeliveryDate'],
                                    'GoodsReceiptQuantity'  => $varu['GoodsReceiptQuantity'],
                                    'GoodsReceiptDate'      => $varu['GoodsReceiptDate'],
                                    'POCategory'            => $varu['POCategory'],
                                    'MovementType'          => $varu['MovementType'],
                                    'DocumentNumber'        => $varu['DocumentNumber'],
                                    'DocumentNumberItem'    => $varu['DocumentNumberItem'],
                                    'PayTerms'              => $varu['PayTerms'],
                                    'WorkTime'              => $varu['WorkTime'],
                                    'PRItem'                => $varu['PRItem'],
                                    'InboundNumber'         => $varu['InboundNumber'],
                                    'GoodsReceiptDate2'     => $varu['GoodsReceiptDate2'],
                                    'GoodsReceiptTime2'     => $varu['GoodsReceiptTime2'],
                                    'PurchasingGroup'       => $varu['PurchasingGroup'],
                                    'RefDocumentNumber'     => $varu['RefDocumentNumber'],
                                    'IRDebetCredit'         => $varu['IRDebetCredit'],
                                    'VAT'                   => $varu['VAT'],
                                    'Plant'                 => $varu['Plant'],
                                    'SLoc'                  => $varu['SLoc'],
                                    'UoM'                   => $varu['UoM'],
                                    'DocumentNumberRef'     => $varu['DocumentNumberRef'],
                                    'DocumentNumberItemRef' => $varu['DocumentNumberItemRef'],
                                    'CreatedBy'             => "SyncDataSAP"
                                ]);
                            }
                        }
                        else{
                            //check di table purchasingdocumentitemhistory
                            $getDocNumPDIH = PdiHistory::where('POID',$varu['POID'])
                            ->where('ItemNumber',$varu['ItemNumber'])
                            ->where('MovementType',$varu['MovementType'])
                            ->where('DocumentNumber',$varu['DocumentNumber'])
                            ->where('DocumentNumberItem',$varu['DocumentNumberItem'])
                            ->first();

                            if(!empty($getDocNumPDIH)){ //jika ada langsung update di table purchasingdocumentitemhistory
                                PdiHistory::where('ID',$getDocNumPDIH->ID)
                                ->update([
                                    'POID'                  => $varu['POID'],
                                    'Number'                => $varu['Number'],
                                    'ItemNumber'            => $varu['ItemNumber'],
                                    'IsClosed'              => $varu['IsClosed'],
                                    'Quantity'              => $varu['Quantity'],
                                    'Currency'              => $varu['Currency'],
                                    'PRNumber'              => $varu['PRNumber'],
                                    'PRReleaseDate'         => $varu['PRReleaseDate'],
                                    'Material'              => $varu['Material'],
                                    'NetPrice'              => $varu['NetPrice'],
                                    'MaterialVendor'        => $varu['MaterialVendor'],
                                    'PRCreateDate'          => $varu['PRCreateDate'],
                                    'Description'           => $varu['Description'],
                                    'DeliveryDate'          => $varu['DeliveryDate'],
                                    'GoodsReceiptQuantity'  => $varu['GoodsReceiptQuantity'],
                                    'GoodsReceiptDate'      => $varu['GoodsReceiptDate'],
                                    'POCategory'            => $varu['POCategory'],
                                    'MovementType'          => $varu['MovementType'],
                                    'DocumentNumber'        => $varu['DocumentNumber'],
                                    'DocumentNumberItem'    => $varu['DocumentNumberItem'],
                                    'PayTerms'              => $varu['PayTerms'],
                                    'WorkTime'              => $varu['WorkTime'],
                                    'PRItem'                => $varu['PRItem'],
                                    'InboundNumber'         => $varu['InboundNumber'],
                                    'GoodsReceiptDate2'     => $varu['GoodsReceiptDate2'],
                                    'GoodsReceiptTime2'     => $varu['GoodsReceiptTime2'],
                                    'PurchasingGroup'       => $varu['PurchasingGroup'],
                                    'RefDocumentNumber'     => $varu['RefDocumentNumber'],
                                    'IRDebetCredit'         => $varu['IRDebetCredit'],
                                    'VAT'                   => $varu['VAT'],
                                    'Plant'                 => $varu['Plant'],
                                    'SLoc'                  => $varu['SLoc'],
                                    'UoM'                   => $varu['UoM'],
                                    'DocumentNumberRef'     => $varu['DocumentNumberRef'],
                                    'DocumentNumberItemRef' => $varu['DocumentNumberItemRef']
                                ]);
                            }
                            else{ //jika tidak ada, check di table purchasingdocumentitem
                                $getDocNumPDI = Pdi::where('POID',$varu['POID'])
                                ->where('ItemNumber',$varu['ItemNumber'])
                                ->where('MovementType',$varu['MovementType'])
                                ->where('DocumentNumber',$varu['DocumentNumber'])
                                ->where('DocumentNumberItem',$varu['DocumentNumberItem'])
                                ->first();

                                if(!empty($getDocNumPDI)){ //jika ketemu, update data table purchasingdocumentitem
                                    Pdi::where('ID',$getDocNumPDI->ID)
                                    ->update([
                                        'POID'                  => $varu['POID'],
                                        'ItemNumber'            => $varu['ItemNumber'],
                                        'IsClosed'              => $varu['IsClosed'],
                                        'Quantity'              => $varu['Quantity'],
                                        'Currency'              => $varu['Currency'],
                                        'PRNumber'              => $varu['PRNumber'],
                                        'PRReleaseDate'         => $varu['PRReleaseDate'],
                                        'Material'              => $varu['Material'],
                                        'NetPrice'              => $varu['NetPrice'],
                                        'MaterialVendor'        => $varu['MaterialVendor'],
                                        'PRCreateDate'          => $varu['PRCreateDate'],
                                        'Description'           => $varu['Description'],
                                        'DeliveryDate'          => $varu['DeliveryDate'],
                                        'GoodsReceiptQuantity'  => $varu['GoodsReceiptQuantity'],
                                        'GoodsReceiptDate'      => $varu['GoodsReceiptDate'],
                                        'POCategory'            => $varu['POCategory'],
                                        'MovementType'          => $varu['MovementType'],
                                        'DocumentNumber'        => $varu['DocumentNumber'],
                                        'DocumentNumberItem'    => $varu['DocumentNumberItem'],
                                        'PayTerms'              => $varu['PayTerms'],
                                        'WorkTime'              => $varu['WorkTime'],
                                        'PRItem'                => $varu['PRItem'],
                                        'InboundNumber'         => $varu['InboundNumber'],
                                        'GoodsReceiptDate2'     => $varu['GoodsReceiptDate2'],
                                        'GoodsReceiptTime2'     => $varu['GoodsReceiptTime2'],
                                        'PurchasingGroup'       => $varu['PurchasingGroup'],
                                        'RefDocumentNumber'     => $varu['RefDocumentNumber'],
                                        'IRDebetCredit'         => $varu['IRDebetCredit'],
                                        'VAT'                   => $varu['VAT'],
                                        'Plant'                 => $varu['Plant'],
                                        'SLoc'                  => $varu['SLoc'],
                                        'UoM'                   => $varu['UoM'],
                                        'DocumentNumberRef'     => $varu['DocumentNumberRef'],
                                        'DocumentNumberItemRef' => $varu['DocumentNumberItemRef']
                                    ]);
                                }
                                else{ // jika di table purchasingdocumentitem tidak ada juga, maka insert ke table purchasingdocumentitem
                                    Pdi::insert([
                                        'POID'                  => $varu['POID'],
                                        'ItemNumber'            => $varu['ItemNumber'],
                                        'IsClosed'              => $varu['IsClosed'],
                                        'Quantity'              => $varu['Quantity'],
                                        'Currency'              => $varu['Currency'],
                                        'PRNumber'              => $varu['PRNumber'],
                                        'PRReleaseDate'         => $varu['PRReleaseDate'],
                                        'Material'              => $varu['Material'],
                                        'NetPrice'              => $varu['NetPrice'],
                                        'MaterialVendor'        => $varu['MaterialVendor'],
                                        'PRCreateDate'          => $varu['PRCreateDate'],
                                        'Description'           => $varu['Description'],
                                        'DeliveryDate'          => $varu['DeliveryDate'],
                                        'GoodsReceiptQuantity'  => $varu['GoodsReceiptQuantity'],
                                        'GoodsReceiptDate'      => $varu['GoodsReceiptDate'],
                                        'POCategory'            => $varu['POCategory'],
                                        'MovementType'          => $varu['MovementType'],
                                        'DocumentNumber'        => $varu['DocumentNumber'],
                                        'DocumentNumberItem'    => $varu['DocumentNumberItem'],
                                        'PayTerms'              => $varu['PayTerms'],
                                        'WorkTime'              => $varu['WorkTime'],
                                        'PRItem'                => $varu['PRItem'],
                                        'InboundNumber'         => $varu['InboundNumber'],
                                        'GoodsReceiptDate2'     => $varu['GoodsReceiptDate2'],
                                        'GoodsReceiptTime2'     => $varu['GoodsReceiptTime2'],
                                        'PurchasingGroup'       => $varu['PurchasingGroup'],
                                        'RefDocumentNumber'     => $varu['RefDocumentNumber'],
                                        'IRDebetCredit'         => $varu['IRDebetCredit'],
                                        'VAT'                   => $varu['VAT'],
                                        'Plant'                 => $varu['Plant'],
                                        'SLoc'                  => $varu['SLoc'],
                                        'UoM'                   => $varu['UoM'],
                                        'DocumentNumberRef'     => $varu['DocumentNumberRef'],
                                        'DocumentNumberItemRef' => $varu['DocumentNumberItemRef'],
                                        'CreatedBy'             => "SyncDataSAP"
                                    ]);
                                }

                            }
                        }
                        //untuk tampungan data POID dan ItemNumber dari table purchasingdocumentitem
                        $arr_po_pdi[$varu['POID']][$varu['ItemNumber']] = $varu['ItemNumber'];
                    }
                }
            /* END */

            /* Update table PDI untuk OpenQuantity */
                foreach($arr_po_pdi as $key => $data){
                    $openQuantity[] = VwOpenQuantity::where('POID', $key)->whereIn('ItemNumber',array_values($data))->distinct()->get()->toArray();
                    // dd($key,$data, array_values($data),$openQuantity);
                }

                foreach($openQuantity as $var){
                    foreach($var as $var1){
                        if($var1['OpenQuantity'] == 0 && $var1['OpenQuantityIR'] == 0){
                            Pdi::where('POID',$var1['POID'])->where('ItemNumber', $var1['ItemNumber'])
                            ->update([
                                'OpenQuantity' => $var1['OpenQuantity'],
                                'OpenQuantityIR' => $var1['OpenQuantityIR']
                            ]);

                            $getdatapdi = Pdi::where('POID',$var1['POID'])->where('ItemNumber', $var1['ItemNumber'])->get()->makeHidden(['ID'])->toArray();
                            PdiHistory::insert($getdatapdi);
                            Pdi::where('POID',$var1['POID'])->where('ItemNumber', $var1['ItemNumber'])->delete();
                        }
                        else{
                            Pdi::where('POID',$var1['POID'])->where('ItemNumber', $var1['ItemNumber'])
                            ->update([
                                'OpenQuantity' => $var1['OpenQuantity'],
                                'OpenQuantityIR' => $var1['OpenQuantityIR']
                            ]);
                        }
                    }
                }
            /* END */

            //update newpo ketika sudah ada GR
            foreach($arr_po_pdi as $key => $data){
                foreach(array_values($data) as $val){
                    $tampung1 = Pdi::where('POID', $key)->where('ItemNumber',$val)->whereNotNull('DocumentNumber')->get()->toArray();
                    if(count($tampung1) > 0){
                        Pdi::where('POID', $key)->where('ItemNumber',$val)->whereNull('DocumentNumber')->update(['ActiveStage' => 4]);
                    }
                }
            }

            $getNullOpenQuantity = Pdi::select('POID','ItemNumber')->whereNull('OpenQuantity')->distinct()->get();
            foreach($getNullOpenQuantity as $item){
                $selectOpenQuantity = VwOpenQuantity::where('POID',$item->POID)->where('ItemNumber',$item->ItemNumber)->first();
                if($selectOpenQuantity != ''){
                    Pdi::where('POID',$selectOpenQuantity->POID)->where('ItemNumber',$selectOpenQuantity->ItemNumber)->update([
                        'OpenQuantity' => $selectOpenQuantity->OpenQuantity,
                        'OpenQuantityIR' => $selectOpenQuantity->OpenQuantityIR
                    ]);
                }
            }

            //SP Open Quantity POTracking
            DB::connection('mysql6')->statement('CALL sp_pdiOpenQuantity');

        }catch (Exception $e) {
            // $this->ErrorLog($e);
            $message    = $e->getMessage();
            $code       = $e->getCode();
            $string     = $e->__toString();
            $create     = LogErrorCSV::create([
                'action'        => url()->current(),
                'filename'      => $filename,
                'code'          => $code,
                'message'       => $message,
                'ex_string'     => $string,
                'created_by'    => 'System',
            ]);
        }
    }


    public function allFunctionCSV(){
        // try{
            $now = Carbon::now();
            $allFiles   = File::Files(public_path('potracking/sap'));
            if($allFiles != false){
                foreach($allFiles as $item){
                    $this->uploadCSVtoDB();
                    // $this->updateApapun();
                }
                $akhir = Carbon::now();
                echo "<br>";
                echo $akhir;
                echo "<br>";
                echo $now;
            } else {
                echo "File not found";
            }
        // }
        // catch (Exception $e){
        //     $this->ErrorLog($e);
        // }
    }
}