<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Middleware;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\apiReqDate;
use App\Models\Table\CompletenessComponent\Inventory;
use App\Models\Table\CompletenessComponent\logCSV;
use App\Models\Table\CompletenessComponent\Material;
use App\Models\Table\CompletenessComponent\MaterialHasilOlah;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\Table\CompletenessComponent\ProductionOrder;
use App\Models\Table\CompletenessComponent\ReportGI;
use App\Models\View\CompletenessComponent\VwMaterialListHistory;
use App\Models\View\CompletenessComponent\VwMaterialMentah;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class SAP_Update_v2 extends Controller
{
    public $stat_ongoing = ['CRTD', 'REL', 'DLV', 'PDLV'];
    public $stat_history = ['TECO', 'CLSD'];

    public $filenameStock         = 'RECORDSTOCKLIST.csv';
    public $filenamePRO           = 'RECORDPRO.csv';
    public $filenameMaterial      = 'RECORDMATLIST.csv';

    // Fungsi Insert Inventory
    public function insertStock()
    {
           $start       = Carbon::now();
           $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // lokasi file ada di folder public/completeness-component
            $location   = public_path('completeness-component/' . $this->filenameStock);
            
            if (file_exists($location)) {
                $dataCSVStocklist = [];
                // membuka file dan membaca data csv
                $worksheet  = fopen("{$location}", "r");
                // $flag = true;
                $row = 0;
                // mengubah data csv menjadi array
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $row++;
                    $dataCSVStocklist[]= $worksheet_data;
                }
                fclose($worksheet);
                unset($worksheet);
                // unset($flag);
                unset($worksheet_data);
                
                if(!empty($dataCSVStocklist)){
                    foreach ($dataCSVStocklist as $csv_stock) {
                         // MATNR
                        $csv_stock[0] = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $csv_stock[0]);
                        
                        // PLANTS / WERKS
                        if($csv_stock[1] == ''){
                            $csv_stock[1] = NULL;
                        }
                        
                        // SLOG / LGORT
                        if($csv_stock[2] == ''){
                            $csv_stock[2] = NULL;
                        }

                        // STOCK / LABST
                        $csv_stock[3] = str_replace('.', '', $csv_stock[3]);
                        $csv_stock[3] = str_replace(',000', '', $csv_stock[3]);
                        $csv_stock[3] = str_replace(',00', '', $csv_stock[3]);
                        $csv_stock[3] = str_replace(',', '.', $csv_stock[3]);
                        if ($csv_stock[3] == '') {
                            $csv_stock[3] = NULL;
                        }
                        
                        // IN QC / INSME
                        $csv_stock[4] = str_replace('.', '', $csv_stock[4]);
                        $csv_stock[4] = str_replace(',000', '', $csv_stock[4]);
                        $csv_stock[4] = str_replace(',00', '', $csv_stock[4]);
                        $csv_stock[4] = str_replace(',', '.', $csv_stock[4]);
                        if ($csv_stock[4] == '') {
                            $csv_stock[4] = NULL;
                        }
                        
                        // DESC / MAKTX
                        $csv_stock[8] = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $csv_stock[8]);
                        
                        $dataInsertInventory[] = [
                            //  MATERIAL NUMBER
                            'material_number' => $csv_stock[0],
                            // MATERIAL DESCRIPTION
                            'material_description' => $csv_stock[8],
                            // MATERIAL TYPE
                            'material_type' => $csv_stock[6],
                            // MATERIAL GROUP
                            'material_group' => $csv_stock[7],
                            // BASE UNIT / SATUAN
                            'base_unit' => $csv_stock[5],
                            // PLANTS
                            'plant' => $csv_stock[1],
                            // STORAGE LOCATION
                            'storage_location' => $csv_stock[2],
                            // STOCK / UNRESTRICTED
                            'stock' => $csv_stock[3],
                            //  IN QC
                            'in_qc' => $csv_stock[4]
                         ];
                    }
                    unset($dataCSVStocklist);
                    // memecah $dataInsertInventory menjadi beberapa array
                    $chunks_stock = array_chunk($dataInsertInventory, 1000);
                    unset($dataInsertInventory);
                    // mengosongkan tabel inventory
                    Inventory::truncate();

                    try {
                        // insert data ke dalam CSV
                        foreach ($chunks_stock as $insertStock) {
                            Inventory::insert($insertStock);
                        }
                    } catch (Exception $qe) {
                        $stop         = Carbon::now();
                        logCSV::insert([
                            'proses'    => 'Insert Inventory',
                            'start'     => $start,
                            'stop'      => $stop,
                            'status'    => 'Error',
                            'message'   => $qe->errorInfo[2],
                            'upload_by' => $upload_by
                        ]);
                    }

                    unset($chunks_stock);
                    unset($insertStock);
                }
                // nama file baru
                $fileStock = date('dmY') .'-'.date('his').'-'. $this->filenameStock;
                // memindahkan file csv ke dalam tabel inventory dengan nama baru
                $move = File::move(public_path('completeness-component/' . $this->filenameStock), public_path('completeness-component/history/'. $fileStock));
                if($move){
                    $stop = Carbon::now();
                    logCSV::insert([
                        'proses'    => 'Insert Inventory',
                        'filename'  => $fileStock,
                        'total_row' => $row,
                        'start'     => $start,
                        'stop'      => $stop,
                        'status'    => 'Success',
                        'upload_by' => $upload_by
                    ]);
                }

                unset($fileStock);
                unset($row);
                unset($start);
                unset($stop);
            }else {
                echo 'File '.$this->filenameStock.' Not Found!';
                
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Insert Inventory',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => 'File Not Found!',
                    'upload_by' => $upload_by
                ]);
            }
    }

    // Fungsi Update Inventory
    public function updateStock()
    {
        try {
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // lokasi file ada di folder public/completeness-component
            $location   = public_path('completeness-component/' . $this->filenameStock);
            
            if (file_exists($location)) {
                $dataCSVStocklist = [];
                // membuka file CSV dan membaca data
                $worksheet  = fopen("{$location}", "r");
                // $flag = true;
                $row = 0;

                // menjadikan data csv menjadi array
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $row++;
                    $dataCSVStocklist[]= $worksheet_data;
                }
                fclose($worksheet);
                
                unset($worksheet);
                // unset($flag);
                unset($worksheet_data);
                
                if (!empty($dataCSVStocklist)) {
                    foreach ($dataCSVStocklist as $csv_stock) {
                        // MATNR
                        $csv_stock[0] = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $csv_stock[0]);
                        
                        // PLANTS / WERKS
                        if($csv_stock[1] == ''){
                            $csv_stock[1] = NULL;
                        }
                        
                        // SLOG / LGORT
                        if($csv_stock[2] == ''){
                            $csv_stock[2] = NULL;
                        }

                        // STOCK / LABST
                        $csv_stock[3] = str_replace('.', '', $csv_stock[3]);
                        $csv_stock[3] = str_replace(',000', '', $csv_stock[3]);
                        $csv_stock[3] = str_replace(',00', '', $csv_stock[3]);
                        $csv_stock[3] = str_replace(',', '.', $csv_stock[3]);
                        if ($csv_stock[3] == '') {
                            $csv_stock[3] = NULL;
                        }
                        
                        // IN QC / INSME
                        $csv_stock[4] = str_replace('.', '', $csv_stock[4]);
                        $csv_stock[4] = str_replace(',000', '', $csv_stock[4]);
                        $csv_stock[4] = str_replace(',00', '', $csv_stock[4]);
                        $csv_stock[4] = str_replace(',', '.', $csv_stock[4]);
                        if ($csv_stock[4] == '') {
                            $csv_stock[4] = NULL;
                        }
                        
                        // DESC / MAKTX
                        $csv_stock[8] = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $csv_stock[8]);
                        
                        $dataInsertInventory[] = [
                            'MATNR' => $csv_stock[0],
                            'MAKTX' => $csv_stock[8],
                            'MTART' => $csv_stock[6],
                            'MATKL' => $csv_stock[7],
                            'MEINS' => $csv_stock[5],
                            'WERKS' => $csv_stock[1],
                            'LGORT' => $csv_stock[2],
                            'LABST' => $csv_stock[3],
                            'INSME' => $csv_stock[4]
                        ];
                    }
                    unset($dataCSVStocklist);
                    $chunks_stock = array_chunk($dataInsertInventory, 1000);
                    unset($dataInsertInventory);
                    try {
                        foreach ($chunks_stock as $a) {
                            foreach ($a as $item) {
                                if ($item['MATNR'] != '') {
                                    Inventory::updateOrCreate([
                                        // MATERIAL NUMBER
                                        'material_number' => $item['MATNR'],
                                        // PLANTS
                                        'plant' => $item['WERKS'],
                                        // STORAGE LOCATION
                                        'storage_location' => $item['LGORT']
                                    ],[
                                        // MATERIAL DESCRIPTION
                                        'material_description' => $item['MAKTX'],
                                        // MATERIAL TYPE
                                        'material_type' => $item['MTART'],
                                        // BASE UNIT / SATUAN
                                        'base_unit' => $item['MEINS'],
                                        // MATERIAL GROUP
                                        'material_group' => $item['MATKL'],
                                        // STOCK
                                        'stock' => $item['LABST'],
                                        // IN QC
                                        'in_qc' => $item['INSME']
                                    ]);
                                }
                            }
                        }
                    } catch (Exception $qe) {
                        $stop         = Carbon::now();
                        
                        logCSV::insert([
                            'proses'    => 'Update Inventory',
                            'start'     => $start,
                            'stop'      => $stop,
                            'status'    => 'Error',
                            'message'   => $qe->errorInfo[2],
                            'upload_by' => $upload_by
                        ]);
                        die;
                    }
                    unset($chunks_stock);
                }

                // nama file CSV
                $fileStock = date('dmY') .'-'.date('his').'-'. $this->filenameStock;
                // memindahkan file csv ke folder history
                $move = File::move(public_path('completeness-component/' . $this->filenameStock), public_path('completeness-component/history/'. $fileStock));
                
                if($move){
                    $stop         = Carbon::now();
                    logCSV::insert([
                        'proses'    => 'Update Inventory',
                        'filename'  => $fileStock,
                        'total_row' => $row,
                        'start'     => $start,
                        'stop'      => $stop,
                        'status'    => 'Success',
                        'upload_by' => $upload_by
                    ]);
                }
                
                unset($fileStock);
                unset($row);
                unset($start);
                unset($stop);
            }else {
                echo 'File '.$this->filenameStock.' Not Found!';
                $stop         = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Inventory',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => 'File Not Found!',
                    'upload_by' => $upload_by
                ]);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // Fungsi Update Data Production Order
    public function updatePRO()
    {
        try {
            $start       = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // lokasi file ada di folder public/completeness-component
            $location     = public_path('completeness-component/' . $this->filenamePRO);

            if (file_exists($location)) {
                $dataCSVProductionOrder = [];
                // membuka file csv dan membaca data 
                $worksheet  = fopen("{$location}", "r");
                // $flag = true;
                $row = 0;
                // memasukan data csv menjadi array
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $row++;
                    $dataCSVProductionOrder[]= $worksheet_data;
                }
                fclose($worksheet);
                
                unset($worksheet);
                // unset($flag);
                unset($worksheet_data);
                
                if (!empty($dataCSVProductionOrder)) {
                    // Mengambil data PRO dari CSV untuk get API
                    foreach ($dataCSVProductionOrder as $csv_pro) {
                        $aufnr      = ltrim($csv_pro[0], 0);
                        $pro[] = $aufnr;
                    }
                    unset($csv_pro);
                    unset($aufnr);

                    // Mengambil PRO yang unik untuk API PRO dari IMA 
                    $unik_pro = array_unique($pro);
                    unset($pro);

                    // Delete PRO dari DB jika ada PRO yang sama di CSV
                    ProductionOrder::whereIn('AUFNR', $unik_pro)->delete();

                    $chunks_pro = array_chunk($unik_pro, 50);
                    $array_unik_pro = array_map(fn ($item) => implode('_', $item), $chunks_pro);

                    unset($unique_aufnr);
                    unset($chunks_pro);

                    // mengambil data PRO dari IMA berdasarkan PRO yang ada di CSV
                    foreach ($array_unik_pro as $a) {
                        $apiPRO         = 'http://10.48.10.43/imaapi/api/CCR_DataPRO?pronum=' . $a;
                        $empDataPRO     = file_get_contents($apiPRO);
                        $dataAPIPRO[]   = json_decode($empDataPRO, true);
                    }

                    // Mengambil Date Required Material dari IMA
                    foreach ($array_unik_pro as $b) {
                        $apiMaterial         = 'http://10.48.10.43/imaapi/api/CCR_DateMaterial?pronum=' . $b;
                        $empDataMaterial     = file_get_contents($apiMaterial);
                        $dataMaterialAPI[]   = json_decode($empDataMaterial, true);
                    }

                    unset($array_unik_pro);

                    foreach ($dataCSVProductionOrder as $dataPRO) {
                        $groupProduct       = 'Others';
                        $finish_date_api    = NULL;
                        $start_date_api     = NULL;

                        // AUFNR / PRODUCTION ORDER
                        if ($dataPRO[0]) {
                            $dataPRO[0] = ltrim($dataPRO[0], 0);
                        }

                        // OBKNR / OBJECT LIST
                        if ($dataPRO[5] == "") {
                            $dataPRO[5] = NULL;
                        }
                        
                        // PPOSNR / ITEM NUMBER
                        if ($dataPRO[6]== '' || $dataPRO[6] == NULL) {
                            $dataPRO[6]=0;
                        }else{
                            $dataPRO[6] = ltrim($dataPRO[6], 0);
                            if ($dataPRO[6]== '') {
                                $dataPRO[6] = 0;
                            }
                        }

                        // DATUM PRO / CREATE DATE PRO
                        if ($dataPRO[7] == '' || $dataPRO[7] == '00.00.0000') {
                            $dataPRO[7]  = NULL;
                        } else {
                            $dataPRO[7]  = Carbon::createFromFormat('d/m/Y', $dataPRO[7])->format('Y-m-d');
                        }

                        // UZEIT / CREATE TIME PRO
                        if ($dataPRO[8] == "") {
                            $dataPRO[8] = NULL;
                        }

                        // ANZSN / NUMBER OF SERIAL NUMBER
                        if ($dataPRO[9] == "") {
                            $dataPRO[9] = NULL;
                        }

                        // OBZAE / OBJECT COUNTERS
                        if ($dataPRO[10] == "") {
                            $dataPRO[10] = NULL;
                        }

                        // EQUNR / EQUIPMENT
                        if ($dataPRO[11] == "") {
                            $dataPRO[11] = NULL;
                        }

                        // SERNR / SERIAL NUMBER
                        if ($dataPRO[12]== '' || $dataPRO[12] == NULL) {
                            $dataPRO[12]='-';
                        }else{
                            $dataPRO[12] = ltrim($dataPRO[12], 0);
                            if ($dataPRO[12]== '') {
                                $dataPRO[12] = '-';
                            }
                        }

                        // DATUM SN / CREATE DATE SERIAL NUMBER
                        if ($dataPRO[14] == '' || $dataPRO[14] == '00.00.0000') {
                            $dataPRO[14]  = NULL;
                        } else {
                            $dataPRO[14]  = Carbon::createFromFormat('d/m/Y', $dataPRO[14])->format('Y-m-d');
                        }

                        // RSNUM / RESERVATION NUMBER
                        if ($dataPRO[15]== '' || $dataPRO[15] == NULL) {
                            $dataPRO[15]=null;
                        }else{
                            $dataPRO[15] = ltrim($dataPRO[15], 0);
                            if ($dataPRO[15]== '') {
                                $dataPRO[15] = null;
                            }
                        }

                        // GLTRP / BASIC FINISH DATE
                        if ($dataPRO[16] == '' || $dataPRO[16] == '00.00.0000') {
                            $dataPRO[16]  = NULL;
                        } else {
                            $dataPRO[16]  = Carbon::createFromFormat('d/m/Y', $dataPRO[16])->format('Y-m-d');
                        }

                        // GSTRP / BASIC START DATE
                        if ($dataPRO[17] == '' || $dataPRO[17] == '00.00.0000') {
                            $dataPRO[17]  = NULL;
                        } else {
                            $dataPRO[17]  = Carbon::createFromFormat('d/m/Y', $dataPRO[17])->format('Y-m-d');
                        }

                        // FTRMS / RELEASE DATE
                        if ($dataPRO[18] == '' || $dataPRO[18] == '00.00.0000') {
                            $dataPRO[18]  = NULL;
                        } else {
                            $dataPRO[18]  = Carbon::createFromFormat('d/m/Y', $dataPRO[18])->format('Y-m-d');
                        }
                        
                        // GLTRS / SCHEDULED FINISH DATE
                        if ($dataPRO[19] == '' || $dataPRO[19] == '00.00.0000') {
                            $dataPRO[19]  = NULL;
                        } else {
                            $dataPRO[19]  = Carbon::createFromFormat('d/m/Y', $dataPRO[19])->format('Y-m-d');
                        }
                        
                        // GSTRS / SCHEDULED START DATE
                        if ($dataPRO[20] == '' || $dataPRO[20] == '00.00.0000') {
                            $dataPRO[20]  = NULL;
                        } else {
                            $dataPRO[20]  = Carbon::createFromFormat('d/m/Y', $dataPRO[20])->format('Y-m-d');
                        }
                        
                        // GSTRI / ACTUAL START DATE
                        if ($dataPRO[21] == '' || $dataPRO[21] == '00.00.0000') {
                            $dataPRO[21]  = NULL;
                        } else {
                            $dataPRO[21]  = Carbon::createFromFormat('d/m/Y', $dataPRO[21])->format('Y-m-d');
                        }
                        
                        // GLTRI / ACTUAL FINISH DATE
                        if ($dataPRO[22] == '' || $dataPRO[22] == '00.00.0000') {
                            $dataPRO[22]  = NULL;
                        } else {
                            $dataPRO[22]  = Carbon::createFromFormat('d/m/Y', $dataPRO[22])->format('Y-m-d');
                        }
                        
                        // FTRMI / ACTUAL RELEASE DATE
                        if ($dataPRO[23] == '' || $dataPRO[23] == '00.00.0000') {
                            $dataPRO[23]  = NULL;
                        } else {
                            $dataPRO[23]  = Carbon::createFromFormat('d/m/Y', $dataPRO[23])->format('Y-m-d');
                        }
                        
                        // PRUEFLOS / INSPECTION LOT
                        if ($dataPRO[24]== '' || $dataPRO[24] == NULL) {
                            $dataPRO[24]=0;
                        }else{
                            $dataPRO[24] = ltrim($dataPRO[24], 0);
                            if ($dataPRO[24]== '') {
                                $dataPRO[24] = 0;
                            }
                        }
                        
                        // PLNUM / PLANNED ORDER
                        if ($dataPRO[25]== '' || $dataPRO[25] == NULL) {
                            $dataPRO[25]=0;
                        }else{
                            $dataPRO[25] = ltrim($dataPRO[25], 0);
                            if ($dataPRO[25]== '') {
                                $dataPRO[25] = 0;
                            }
                        }
                        
                        // STRMP / PLANNED START DATE
                        if ($dataPRO[26] == '' || $dataPRO[26] == '00.00.0000') {
                            $dataPRO[26]  = NULL;
                        } else {
                            $dataPRO[26]  = Carbon::createFromFormat('d/m/Y', $dataPRO[26])->format('Y-m-d');
                        }
                        
                        // ETRMP / Open. date plnd
                        if ($dataPRO[27] == '' || $dataPRO[27] == '00.00.0000') {
                            $dataPRO[27]  = NULL;
                        } else {
                            $dataPRO[27]  = Carbon::createFromFormat('d/m/Y', $dataPRO[27])->format('Y-m-d');
                        }
                        
                        // KDPOS / Sales ord. item
                        if ($dataPRO[29]== '' || $dataPRO[29] == NULL) {
                            $dataPRO[29]=0;
                        }else{
                            $dataPRO[29] = ltrim($dataPRO[29], 0);
                            if ($dataPRO[29]== '') {
                                $dataPRO[29] = 0;
                            }
                        }
                        
                        // PSMNG / ORDER QUANTITY
                        if ($dataPRO[30]) {
                            $dataPRO[30] = trim(str_replace(',000', '', $dataPRO[30]));
                        }
                        
                        // LTRMI / Act.deliv.date
                        if ($dataPRO[32] == '' || $dataPRO[32] == '00.00.0000') {
                            $dataPRO[32]  = NULL;
                        } else {
                            $dataPRO[32]  = Carbon::createFromFormat('d/m/Y', $dataPRO[32])->format('Y-m-d');
                        }
                        
                        // LTRMP / PldOrd Del.date
                        if ($dataPRO[33] == '' || $dataPRO[33] == '00.00.0000') {
                            $dataPRO[33]  = NULL;
                        } else {
                            $dataPRO[33]  = Carbon::createFromFormat('d/m/Y', $dataPRO[33])->format('Y-m-d');
                        }
                        
                        // STlNR / BoM
                        if ($dataPRO[38] == "") {
                            $dataPRO[38] = NULL;
                        }else {
                            $dataPRO[38] = ltrim($dataPRO[38], 0);
                        }
                        
                        // STLAL / Alternative BoM
                        if ($dataPRO[39] == "") {
                            $dataPRO[39] = NULL;
                        }else {
                            $dataPRO[39] = ltrim($dataPRO[39], 0);
                        }
                        
                        // AUFPL / Plan no.f.oper.
                        if ($dataPRO[40] == "") {
                            $dataPRO[40] = NULL;
                        }else {
                            $dataPRO[40] = ltrim($dataPRO[40], 0);
                        }
                        
                        // PHAS1
                        if ($dataPRO[42] == '' || $dataPRO[42] == '00.00.0000' || $dataPRO[42] == '0') {
                            $dataPRO[42]  = NULL;
                        } else {
                            $dataPRO[42]  = Carbon::createFromFormat('d/m/Y', $dataPRO[42])->format('Y-m-d');
                        }
                        
                        // PHAS2
                        if ($dataPRO[43] == '00.00.0000' || $dataPRO[43] == '0' || $dataPRO[43] == '') {
                            $dataPRO[43]  = NULL;
                        } else {
                            $dataPRO[43]  = Carbon::createFromFormat('d/m/Y', $dataPRO[43])->format('Y-m-d');
                        }
                        
                        // PHAS3
                        if ($dataPRO[44] == '00.00.0000' || $dataPRO[44] == '0' || $dataPRO[44] == '') {
                            $dataPRO[44]  = NULL;
                        } else {
                            $dataPRO[44]  = Carbon::createFromFormat('d/m/Y', $dataPRO[44])->format('Y-m-d');
                        }
                        
                        // IDAT1
                        if ($dataPRO[45] == '00.00.0000' || $dataPRO[45] == '0' || $dataPRO[45] == '') {
                            $dataPRO[45]  = NULL;
                        } else {
                            $dataPRO[45]  = Carbon::createFromFormat('d/m/Y', $dataPRO[45])->format('Y-m-d');
                        }
                        
                        // IDAT2
                        if ($dataPRO[46] == '00.00.0000' || $dataPRO[46] == '0' || $dataPRO[46] == '') {
                            $dataPRO[46]  = NULL;
                        } else {
                            $dataPRO[46]  = Carbon::createFromFormat('d/m/Y', $dataPRO[46])->format('Y-m-d');
                        }
                        
                        // IDAT3
                        if ($dataPRO[47] == '00.00.0000' || $dataPRO[47] == '0' || $dataPRO[47] == '') {
                            $dataPRO[47]  = NULL;
                        } else {
                            $dataPRO[47]  = Carbon::createFromFormat('d/m/Y', $dataPRO[47])->format('Y-m-d');
                        }

                        // DATE_STAT_CREATED
                        if ($dataPRO[50] == '00.00.0000' || $dataPRO[50] == '0' || $dataPRO[50] == '') {
                            $dataPRO[50]  = NULL;
                        } else {
                            $dataPRO[50]  = Carbon::createFromFormat('d/m/Y', $dataPRO[50])->format('Y-m-d');
                        }

                        // Data API pro IMA
                        foreach ($dataAPIPRO as $api) {
                            if (!empty($api)) {
                                foreach ($api as $a) {
                                    if ($dataPRO[0] == $a['ProductionOrder'] && $dataPRO[12] == $a['SerialNumbers']) {
                                        // finish date api IMA
                                        $finish_date        = $a['finishdate'];
                                        if ($finish_date == NULL) {
                                            $finish_date_api      = NULL;
                                        } else {
                                            $finish_date_api      = substr($finish_date, 0, 10);
                                        }

                                        // start date api IMA
                                        $start_date         = $a['planstartdate'];
                                        if ($start_date == NULL) {
                                            $start_date_api      = NULL;
                                        } else {
                                            $start_date_api      = substr($start_date, 0, 10);
                                        }

                                        // group product dari IMA
                                        $groupProduct   = $a['GroupProduct'];
                                        break;
                                    }
                                    // apabila product number IMA sama dengan product number di CSV maka ambil data group product
                                    if ($dataPRO[13] == $a['ProductNumber']) {
                                        $groupProduct   = $a['GroupProduct'];
                                    }
                                }
                            }
                        }

                        if ($dataPRO[0] != '') {
                            $dataInsertPRO = [
                                // Order
                                'AUFNR'             => $dataPRO[0],
                                // Order Type	
                                'AUART'             => $dataPRO[1],
                                // Order category	
                                'AUTYP'             => $dataPRO[2],
                                // Entered by	
                                'ERNAM'             => $dataPRO[3],
                                // PRO Creator	
                                'CREATOR'           => $dataPRO[4],
                                // Object list	
                                'OBKNR'             => $dataPRO[5],
                                // Item Number	
                                'PPOSNR'            => $dataPRO[6],
                                // Create Date PRO	
                                'DATUM_PRO'         => $dataPRO[7],
                                // Time	
                                'UZEIT'             => $dataPRO[8],
                                // No.serial no.	
                                'ANZSN'             => $dataPRO[9],
                                // Object counters	
                                'OBZAE'             => $dataPRO[10],
                                // Equipment	
                                'EQUNR'             => $dataPRO[11],
                                // Serial number	
                                'SERNR'             => $dataPRO[12],
                                // Material	
                                'MATNR'             => $dataPRO[13],
                                // Create Date SN	
                                'DATUM_SN'          => $dataPRO[14],
                                // Reservation	
                                'RSNUM'             => $dataPRO[15],
                                // Basic fin. date	
                                'GLTRP_SAP'         => $dataPRO[16],
                                // Bas. Start Date	
                                'GSTRP'             => $dataPRO[17],
                                // Release	
                                'FTRMS'             => $dataPRO[18],
                                // Sched. finish	
                                'GLTRS'             => $dataPRO[19],
                                // Sched. start	
                                'GSTRS'             => $dataPRO[20],
                                // Actual start	
                                'GSTRI'             => $dataPRO[21],
                                // Actual finish	
                                'GLTRI'             => $dataPRO[22],
                                // Actual release	
                                'FTRMI'             => $dataPRO[23],
                                // Inspection Lot	
                                'PRUEFLOS'          => $dataPRO[24],
                                // Planned order	
                                'PLNUM'             => $dataPRO[25],
                                // Pld start date	
                                'STRMP_SAP'         => $dataPRO[26],
                                // Open. date plnd	
                                'ETRMP'             => $dataPRO[27],
                                // Sales order	
                                'KDAUF'             => $dataPRO[28],
                                // Sales ord. item	
                                'KDPOS'             => $dataPRO[29],
                                // Order quantity	
                                'PSMNG'             => $dataPRO[30],
                                // Base Unit	
                                'MEINS'             => $dataPRO[31],
                                // Act.deliv.date	
                                'LTRMI'             => $dataPRO[32],
                                // PldOrd Del.date	
                                'LTRMP'             => $dataPRO[33],
                                // Deletion Flag	
                                'XLOEK'             => $dataPRO[34],
                                // Object number	
                                'OBJNR'             => $dataPRO[35],
                                // Status	
                                'STAT'              => $dataPRO[36],
                                // Plant	
                                'DWERK'             => $dataPRO[37],
                                // BoM	
                                'STLNR'             => $dataPRO[38],
                                // Alternative BoM	
                                'STLAL'             => $dataPRO[39],
                                // Plan no.f.oper.	
                                'AUFPL'             => $dataPRO[40],
                                // Description	
                                'KTEXT'             => $dataPRO[41],
                                // Released	
                                'PHAS1'             => $dataPRO[42],
                                // Completed	
                                'PHAS2'             => $dataPRO[43],
                                // Closed	
                                'PHAS3'             => $dataPRO[44],
                                // Release	
                                'IDAT1'             => $dataPRO[45],
                                // Tech.completion
                                'IDAT2'             => $dataPRO[46],
                                // Close	
                                'IDAT3'             => $dataPRO[47],
                                // Description
                                'MAKTX'             => $dataPRO[48],
                                // Status Description
                                'STAT_DESC'         => $dataPRO[49],
                                // Date Status Created
                                'DATE_STAT_CREATED' => $dataPRO[50],
                                // Group Product
                                'GroupProduct'      => $groupProduct,
                                // Pld start date API IMA	
                                'start_date_api'    => $start_date_api,
                                // Basic fin. date	API IMA
                                'finish_date_api'   => $finish_date_api,
                            ];
                        }

                        $dataProductionOrder[] = $dataInsertPRO;
                    }
                    
                    unset($dataCSVProductionOrder);
                    unset($dataPRO);
                    unset($dataAPIPRO);
                    unset($api);
                    unset($dataInsertPRO);
                    
                    // mengubah array data production order csv menjadi beberapa array
                    $chunksPRO = array_chunk($dataProductionOrder, 1000);

                    // insert data production order
                    try {
                        foreach ($chunksPRO as $PROInsert) {
                            ProductionOrder::insert($PROInsert);
                        }
                    } catch (Exception $qe) {
                        $stop = Carbon::now();
                        logCSV::insert([
                            'proses'    => 'Update Production Order',
                            'start'     => $start,
                            'stop'      => $stop,
                            'status'    => 'Error',
                            'message'   => $qe->errorInfo[2],
                            'upload_by' => $upload_by
                        ]);
                        die;
                    }

                    unset($dataProductionOrder);
                    unset($chunksPRO);
                    unset($PROInsert);
                }
                // nama file baru
                $fileName = date('dmY') .'-'.date('his').'-'. $this->filenamePRO;
                // memindahkan file csv ke dalam folder history
                $move = File::move(public_path('completeness-component/' . $this->filenamePRO), public_path('completeness-component/history/'. $fileName));
                if($move){
                    $stop = Carbon::now();
                    logCSV::insert([
                        'proses'    => 'Update Production Order',
                        'filename'  => $fileName,
                        'total_row' => $row,
                        'start'     => $start,
                        'stop'      => $stop,
                        'status'    => 'Success',
                        'upload_by' => $upload_by
                    ]);

                    unset($move);
                    unset($fileName);
                    unset($row);
                    unset($start);
                    unset($stop);
                }
            }else {
                echo 'File '.$this->filenamePRO.' Not Found!';
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Production Order',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => 'File Not Found!',
                    'upload_by' => $upload_by
                ]);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // Update data API Production Order
    public function updateAPIPRO()
    {
        try {
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // DATA PRO UNTUK GET DATA IMA
            $pro_DB       = ProductionOrder::select('AUFNR')->distinct()->orderBy('AUFNR', 'ASC')->get();
            foreach ($pro_DB as $proDB) {
                $pro[] = $proDB['AUFNR'];
            }
            unset($pro_DB);
            unset($proDB);
            
            $chunk_aufnr    = array_chunk($pro, 50);
            $array          = array_map(fn ($item) => implode('_', $item), $chunk_aufnr);
            unset($pro);
            unset($chunk_aufnr);
            
            // GET DATA API DARI IMA
            foreach ($array as $a) {
                $apiPRO         = 'http://10.48.10.43/imaapi/api/CCR_DataPRO?pronum=' . $a;
                $empDataPRO     = file_get_contents($apiPRO);
                $dataAPIPRO[]   = json_decode($empDataPRO, true);
            }
            unset($array);
            unset($a);
            
            foreach ($dataAPIPRO as $dataAPI) {
                if ($dataAPI) {
                    foreach ($dataAPI as $api) {
                        $hasil_pro[] = [
                            'AUFNR'         => $api['ProductionOrder'],
                            'MATNR'         => $api['ProductNumber'],
                            'GroupProduct'  => $api['GroupProduct'],
                            'SerialNumber'  => $api['SerialNumbers'],
                            'finishdate'    => substr($api['finishdate'], 0, 10),
                            'startdate'     => substr($api['planstartdate'], 0, 10),
                        ];
                    }
                }
            }
            unset($dataAPIPRO);
            unset($dataAPI);
            unset($api);

            $proDB = ProductionOrder::all();

            // UPDATE DATA start_date_api, finish_date_api, dan group product dari IMA
            try {
                foreach ($proDB as $proDB) {
                    foreach ($hasil_pro as $hasil) {
                        if (($proDB['AUFNR'] == $hasil['AUFNR']) && ($proDB['SERNR'] == $hasil['SerialNumber'])) {
                            if ($hasil['finishdate'] != null) {
                                $proDB['finish_date_api'] = $hasil['finishdate'];
                            }
                            if ($hasil['startdate'] != null) {
                                $proDB['start_date_api'] = $hasil['startdate'];
                            }
                            $proDB->save();
                        }
                        if ($proDB['MATNR'] == $hasil['MATNR']) {
                            if ($hasil['GroupProduct'] != null) {
                                $proDB['GroupProduct'] = $hasil['GroupProduct'];
                            }
                            $proDB->save();
                        }
                    }
                }
            } catch (Exception $qe) {
                $stop         = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Req. Date PRO IMA',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }

            unset($proDB);
            unset($hasil_pro);
            unset($hasil);
            unset($proDB);
            
            $stop         = Carbon::now();
            logCSV::insert([
                'proses'    => 'Update Req. Date PRO IMA',
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // Get API Req. Date dari IMA
    public function updateAPIReqDateMaterial()
    {
        try{
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // get data pro dari DB untuk get data IMA
            $pro_DB       = ProductionOrder::select('AUFNR')->distinct()->whereIn('STAT', $this->stat_ongoing)->orderBy('AUFNR', 'ASC')->get();
            foreach ($pro_DB as $proDB) {
                $pro[] = $proDB['AUFNR'];
            }
            unset($pro_DB);
            unset($proDB);
            // mengubah array chunk menjadi beberapa array dengan isi 50 data
            $chunk_aufnr = array_chunk($pro, 50);
            // implode array menggunakan pemisah _
            $array = array_map(fn ($item) => implode('_', $item), $chunk_aufnr);

            unset($pro);
            unset($chunk_aufnr);
            unset($item);

            //  get data material dari IMA
            foreach ($array as $b) {
                $apiMaterial         = 'http://10.48.10.43/imaapi/api/CCR_DateMaterial?pronum=' . $b;
                $empDataMaterial     = file_get_contents($apiMaterial);
                $dataMaterialAPI[]   = json_decode($empDataMaterial, true);
            }

            unset($array);

            // truncate tabel api reqdate
            apiReqDate::truncate();

            //  insert data material dari IMA ke tabel apireqdate
            try {
                foreach ($dataMaterialAPI as $dataAPI) {
                    apiReqDate::insert($dataAPI);
                }
            } catch (Exception $qe) {
                $stop         = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Get Req. Date Material IMA',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }
            
            unset($dataMaterialAPI);
            unset($dataAPI);

            $stop         = Carbon::now();
            logCSV::insert([
                'proses'    => 'Get Req. Date Material IMA',
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // Update Material dari CSV
    public function updateMaterial()
    {
        try {
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // lokasi file ada di folder public/completeness-component
            $location     = public_path('completeness-component/' . $this->filenameMaterial);
            if (file_exists($location)) {
                $dataCSVMaterial    = [];
                // membuka file csv lalu membaca data csv
                $worksheet          = fopen("{$location}", "r");
                // $flag               = true;
                $row                = 0;
                // mengubah data csv menjadi array
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $row++;
                    $dataCSVMaterial[] = $worksheet_data;
                }
                fclose($worksheet);

                unset($worksheet);
                unset($worksheet_data);
                // unset($flag);
                
                if(!empty($dataCSVMaterial)){
                    // mengubah data csv menjadi beberapa array (1 array berisi 1000 data)
                    $chunks_dataCSVMaterial = array_chunk($dataCSVMaterial, 1000);
                    unset($dataCSVMaterial);
                    foreach ($chunks_dataCSVMaterial as $a) {
                        foreach ($a as $dataMaterial) {
                            // MAKTX / MATERIAL DESCRIPTION
                            $maktx      = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $dataMaterial[3]);
                            
                            // RSNUM / RESERVATION NUMBER
                            if ($dataMaterial[0]== '') {
                                $dataMaterial[0]=NULL;
                            }else{
                                $dataMaterial[0] = ltrim($dataMaterial[0], 0);
                                if ($dataMaterial[0]== '') {
                                    $dataMaterial[0] = NULL;
                                }
                            }
                            
                            // RSPOS / ITEM NUMBER
                            if ($dataMaterial[1]== '') {
                                $dataMaterial[1]=NULL;
                            }else{
                                $dataMaterial[1] = ltrim($dataMaterial[1], 0);
                                if ($dataMaterial[1]== '') {
                                    $dataMaterial[1] = NULL;
                                }
                            }
                            
                            // MATNR / MATERIAL NUMBER
                            if ($dataMaterial[2]== '') {
                                $dataMaterial[2] = NULL;
                            }else {
                                $dataMaterial[2] = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $dataMaterial[2]);
                            }
                            
                            // WERKS / PLANTS
                            if ($dataMaterial[4] == '') {
                                $dataMaterial[4] = NULL;
                            }
                            
                            // LGORT / STORAGE LOCATION
                            if ($dataMaterial[5] == '') {
                                $dataMaterial[5] = NULL;
                            }
                            
                            // BDTER SAP / REQUIREMENT DATE SAP
                            if ($dataMaterial[6] == '' || $dataMaterial[6] == '00.00.0000') {
                                $dataMaterial[6]  = NULL;
                            } else {
                                $dataMaterial[6]  = Carbon::createFromFormat('d/m/Y', $dataMaterial[6])->format('Y-m-d');
                            }
                            
                            // BDMNG / REQUIREMENT DATE
                            $dataMaterial[7] = str_replace(',000', '', $dataMaterial[7]);
                            if ($dataMaterial[7] == '') {
                                $dataMaterial[7] = 0;
                            } else {
                                $dataMaterial[7]      = str_replace(",", ".", $dataMaterial[7]);
                            }
                            
                            // ENMNG / GOOD ISSUE / WITHDRAWAL QUANTITY
                            $dataMaterial[11] = str_replace(',000', '', $dataMaterial[11]);
                            if ($dataMaterial[11] == '') {
                                $dataMaterial[11] = 0;
                            } else {
                                $dataMaterial[11]      = str_replace(",", ".", $dataMaterial[11]);
                            }
                            
                            // STLNR / BoM
                            $dataMaterial[12] = ltrim($dataMaterial[12], 0);
                            if ($dataMaterial[12] == '' || $dataMaterial[12] == '') {
                                $dataMaterial[12] = NULL;
                            }
                            
                            // STLAL / Alternative Plan
                            $dataMaterial[13] = ltrim($dataMaterial[13], 0);
                            if ($dataMaterial[13] == '' || $dataMaterial[13] == '') {
                                $dataMaterial[13] = NULL;
                            }
                            
                            // AUFPL / No. F. Oper
                            $dataMaterial[14] = ltrim($dataMaterial[14], 0);
                            if ($dataMaterial[14] == '') {
                                $dataMaterial[14] = NULL;
                            }
                            
                            // VORNR / Operation
                            $dataMaterial[15] = ltrim($dataMaterial[15], 0);
                            if ($dataMaterial[15] == '') {
                                $dataMaterial[15] = NULL;
                            }
                            
                            // AUFNR / PRODUCTION ORDER
                            $dataMaterial[18] = ltrim($dataMaterial[18], 0);
                            
                            // MATKL / Material Group
                            if ($dataMaterial[20] == '') {
                                $dataMaterial[20] = NULL;
                            }

                            // APLZL / COUNTER
                            $dataMaterial[22] = ltrim($dataMaterial[22], 0);

                            // RUECK / CONFIRMATION
                            $dataMaterial[24] = ltrim($dataMaterial[24], 0);

                            if ($dataMaterial[2] != '') {
                                $dataInsertMaterial = [
                                    // Reservation	
                                    'RSNUM'     => $dataMaterial[0],
                                    // Item	
                                    'RSPOS'     => $dataMaterial[1],
                                    // Material	
                                    'MATNR'     => $dataMaterial[2],
                                    // Description	
                                    'MAKTX'     => $maktx,
                                    // Plant	
                                    'WERKS'     => $dataMaterial[4],
                                    // Stor. Location	
                                    'LGORT'     => $dataMaterial[5],
                                    // Reqmt Date	
                                    'BDTER_SAP' => $dataMaterial[6],
                                    // Reqmt Qty	
                                    'BDMNG'     => $dataMaterial[7],
                                    // Base Unit	
                                    'MEINS'     => $dataMaterial[10],
                                    // Withdrawal Qty	
                                    'ENMNG'     => $dataMaterial[11],
                                    // BOM	
                                    'STLNR'     => $dataMaterial[12],
                                    // Alternative	Plan 
                                    'STLAL'     => $dataMaterial[13],
                                    // no.f.oper.	
                                    'AUFPL'     => $dataMaterial[14],
                                    // Oper./Act.	
                                    'VORNR'     => $dataMaterial[15],
                                    // Object number	
                                    'OBJNR'     => $dataMaterial[16],
                                    // Opr. short text	
                                    'LTXA1'     => $dataMaterial[17],
                                    // Order	
                                    'AUFNR'     => $dataMaterial[18],
                                    // Material Type	
                                    'MTART'     => $dataMaterial[19],
                                    // Material Group	
                                    'MATKL'     => $dataMaterial[20],
                                    // Pegged reqmt	
                                    'BAUGR'     => $dataMaterial[21],
                                    // Counter	
                                    'APLZL'     => $dataMaterial[22],
                                    // Control key	
                                    'STEUS'     => $dataMaterial[23],
                                    // Confirmation
                                    'RUECK'     => $dataMaterial[24]
                                ];
                            }

                            $pro[] = $dataMaterial[18];
                            if ($dataMaterial[2] != '') {
                                $dataMaterialDB[] = $dataInsertMaterial;
                            }
                        }
                    }
                                        
                    unset($chunks_dataCSVMaterial);
                    unset($a);
                    unset($dataMaterial);
                    unset($dataInsertMaterial);

                    // get data pro (unik) dari CSV
                    $edit_pro = array_unique($pro);

                    // delete data PRO yang ada di CSV
                    Material::whereIn('AUFNR', $edit_pro)->delete();
                    
                    unset($pro);
                    unset($edit_pro);
                    
                    $chunksMaterial = array_chunk($dataMaterialDB, 1000);
                    unset($dataMaterialDB);
                    
                    // Insert data material ke DB
                    try {
                        foreach ($chunksMaterial as $z) {
                            Material::insert($z);
                        }
                    } catch (Exception $qe) {
                        $stop         = Carbon::now();
                        logCSV::insert([
                            'proses'    => 'Update Material',
                            'start'     => $start,
                            'stop'      => $stop,
                            'status'    => 'Error',
                            'message'   => $qe->errorInfo[2],
                            'upload_by' => $upload_by
                        ]);
                        die;
                    }

                    unset($chunksMaterial);
                    unset($z);
                }
                // file name baru
                $fileName = date('dmY') .'-'.date('his').'-'. $this->filenameMaterial;
                // memindahkan file csv ke dalam folder history dengan nama baru
                $move = File::move(public_path('completeness-component/' . $this->filenameMaterial), public_path('completeness-component/history/'. $fileName));
                if ($move) {
                    $stop         = Carbon::now();
                    logCSV::insert([
                        'proses'    => 'Update Material',
                        'filename'  => $fileName,
                        'total_row' => $row,
                        'start'     => $start,
                        'stop'      => $stop,
                        'status'    => 'Success',
                        'upload_by' => $upload_by
                    ]);
                }
            } else {
                echo 'File '.$this->filenameMaterial.' Not Found!';
                $stop         = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Material',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => 'File Not Found!',
                    'upload_by' => $upload_by
                ]);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // tabel tampungan untuk menampung data sebelum diplotting
    public function olahDataMaterial()
    {
        try {
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // Mengosongkan tabel Material Hasil Olah
            MaterialHasilOlah::truncate();
        
            // Mengambil data Material dan Stock
            $data =  VwMaterialMentah::leftJoin('vw_total_stock', 'vw_material_mentah.MATNR', '=', 'vw_total_stock.material_number')
                ->select("AUFNR", "PLNBEZ", "DESC_PLNBEZ", "GroupProduct", "GAMNG", "sch_start_date", "sch_finish_date", "STAT", "DATE_STAT_CREATED","vw_material_mentah.MATNR", "MAKTX", "LTXA1", "WERKS", "MEINS", "LGORT", "MTART", "MATKL", DB::raw('SUM(BDMNG) as BDMNG'), DB::raw('SUM(ENMNG) as ENMNG'), DB::raw('IFNULL(BDTER_API, BDTER_SAP) as BDTER'), 'vw_total_stock.stock', DB::raw('vw_total_stock.in_qc as INSME'))
                ->groupBy('vw_material_mentah.MATNR', 'AUFNR')
                ->orderBy('BDTER', 'ASC')
                ->get()
                ->toArray();

            // menghitung jumlah data
            $row = count($data);
            
            $chunks_DB = array_chunk($data, 1000);
            
            //  Memasukan data ke DB
            try {
                foreach ($chunks_DB as $value) {
                    MaterialHasilOlah::insert($value);
                }
            } catch (Exception $qe) {
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Material Hasil Olah',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }
            
            unset($data);
            unset($chunks_DB);
            unset($value);
            
            $stop = Carbon::now();
            logCSV::insert([
                'proses'    => 'Update Material Hasil Olah',
                'total_row' => $row,
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // tabel untuk menampung hasil plotting stock
    public function olahDataTemporaryStock()
    {
        try{
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // mengambil data hasil olahan material
            $data_materialList = MaterialHasilOlah::distinct()
                    ->whereIn('STAT', $this->stat_ongoing)
                    ->groupBy('MATNR', 'AUFNR')
                    ->orderBy('MATNR', 'ASC')
                    ->orderBy('BDTER', 'ASC')
                    ->orderBy('sch_start_date', 'ASC')
                    ->orderBy('AUFNR', 'ASC')
                    ->get();
            $row = count($data_materialList);
            /*Inisialisasi Variable */
            $prevMATNR      = NULL;
            $prevRESTOCK    = NULL;
            $data_to_insert = [];
            // proses plotting stock
            foreach ($data_materialList as $dml) {
                if ($dml[0]) //Array ke [0]
                {
                    // kekurangan quantity
                    $reserve = $dml->BDMNG - $dml->ENMNG;
                    // stok sisa
                    $restock = $dml->STOCK - $reserve;
                    // kondisi ketika stock = 0
                    if ($dml->STOCK == 0) {
                        $reserve        = 0;
                        $restock        = 0;
                        $prevMATNR      = $dml->MATNR;
                        $prevRESTOCK    = $restock;
                    } 
                    // kondisi ketika stock - kekurangan quantity masih kurang dari 0
                    elseif ($dml->STOCK - $reserve < 0) {
                        $restock        = 0;
                        $reserve        = $dml->STOCK;
                        $prevMATNR      = $dml->MATNR;
                        $prevRESTOCK    = $restock;
                    } 
                    // kondisi ketika stock mencukupi
                    else {
                        $prevMATNR      = $dml->MATNR;
                        $prevRESTOCK    = $restock;
                    }
                } else //setelah Array ke [0]
                {
                    if ($dml->MATNR == $prevMATNR) //untuk data kedua dan seterusnya dalam MATNR yg sama
                    {
                        $reserve = $dml->BDMNG - $dml->ENMNG; // kekurangan quantity
                        // kondisi ketika requirement quantity - gi sudah <= 0
                        if($reserve <= 0){
                            $reserve        = 0;
                        }
                        // kondisi ketika stock sisa sebelumnya dikurangi kekurangan quantity masih kurang dari 0
                        if (($prevRESTOCK - $reserve) <= 0) //
                        {
                            $restock        = 0;
                            $reserve        = $prevRESTOCK;
                            $prevRESTOCK    = $restock;
                        } 
                        // kondisi ketika sisa stock masih mencukupi
                        else {
                            $restock        = $prevRESTOCK - $reserve;
                            $prevRESTOCK    = $restock;
                        }
                        $prevMATNR = $dml->MATNR;
                    } else //untuk data pertama MATNR
                    {
                        // kekurangan quantity material
                        $reserve = $dml->BDMNG - $dml->ENMNG;
                        // sisa stock
                        $restock = $dml->STOCK - $reserve;
                        // kondisi ketika stock = 0
                        if ($dml->STOCK == 0) {
                            $reserve        = 0;
                            $restock        = 0;
                            $prevMATNR      = $dml->MATNR;
                            $prevRESTOCK    = $restock;
                        } 
                        // kondisi ketika sisa stock masih kurang dari 0
                        elseif ($dml->STOCK - $reserve < 0) {
                            $restock        = 0;
                            $reserve        = $dml->STOCK;
                            $prevMATNR      = $dml->MATNR;
                            $prevRESTOCK    = $restock;
                        } 
                        // kondisi ketika sisa stock mencukupi
                        else {
                            $prevMATNR      = $dml->MATNR;
                            $prevRESTOCK    = $restock;
                        }
                    }
                    $prevMATNR = $dml->MATNR;
                }
                // minus = jumlah kekurangan material setelah dilakukan alokasi stock terhadap material tersebut
                $minus = ($dml->ENMNG + $reserve) - $dml->BDMNG;
                if($minus >= 0){
                    $minus = 0;
                }
                $data = [
                    'AUFNR'             => $dml->AUFNR,
                    'PLNBEZ'            => $dml->PLNBEZ,
                    'DESC_PLNBEZ'       => $dml->DESC_PLNBEZ,
                    'STAT'              => $dml->STAT,
                    'GroupProduct'      => $dml->GroupProduct,
                    'DATE_STAT_CREATED' => $dml->DATE_STAT_CREATED,
                    'GAMNG'             => $dml->GAMNG,
                    'sch_start_date'    => $dml->sch_start_date,
                    'MATNR'             => $dml->MATNR,
                    'MAKTX'             => $dml->MAKTX,
                    'MEINS'             => $dml->MEINS,
                    'MTART'             => $dml->MTART,
                    'MATKL'             => $dml->MATKL,
                    'WERKS'             => $dml->WERKS,
                    'LGORT'             => $dml->LGORT,
                    'BDTER'             => $dml->BDTER,
                    'BDMNG'             => $dml->BDMNG,
                    'ENMNG'             => $dml->ENMNG,
                    'STOCK'             => $dml->STOCK,
                    'RESERVE'           => $reserve,
                    'MINUS_PLOTTING'    => $minus,
                    'RESTOCK'           => $restock,
                    'INSME'             => $dml->INSME,
                    'LTXA1'             => $dml->LTXA1
                ];
                $data_to_insert[] = $data;
            }
    
            unset($data_materialList);
            unset($data);

            // mengosongkan tabel material temporary
            MaterialTemporary::truncate();

            /*Insert data dengan mengubah array menjadi beberapa chunk */
            $chunks = array_chunk($data_to_insert, 1000);
            
            try {
                foreach ($chunks as $chunk) {
                    MaterialTemporary::insert($chunk);
                }
            } catch (Exception $qe) {
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Tabel Material Temporary',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }
                
            unset($data_to_insert);
            unset($chunks);

            $stop = Carbon::now();
            logCSV::insert([
                'proses'    => 'Update Tabel Material Temporary',
                'total_row' => $row,
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function InsertPercentageOngoing()
    {
        try{
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // mengambil data SN yang masih dalam proses on going
            $sn_ongoing   = VwProWithSN::select('production_order', 'serial_number', 'quantity')
                ->whereIn('status', $this->stat_ongoing)
                ->orderBy('sch_start_date', 'asc')
                ->orderBy('production_order', 'asc')
                ->orderBy('serial_number', 'asc')->get();
            $i            = 0;
            //  menambahkan index ke dalam data SN
            foreach ($sn_ongoing as $test) {
                $current                = $test->toArray();
                $current['sn_index']    = $i++;
                $new_ongoing[]          = $current;
            }
            unset($sn_ongoing);
            unset($current);
    
            foreach ($new_ongoing as $ongoing) {
                // inisiasi variabel
                $total_req_qty            = 0;
                $total_ongoing            = 0;
                $total_gi                 = 0;
                $total_alokasi            = 0;
                $persen_ongoing           = 0;
                $persen_gi                = 0;
                $persen_alokasi           = 0;
                // production order
                $production_order   = $ongoing['production_order'];
                // mengambil data material pro
                $material_ongoing         = MaterialTemporary::select('AUFNR', 'MATNR', 'BDMNG', 'ENMNG', 'RESERVE')->where('AUFNR', $production_order)->orderBy('RESERVE', 'DESC')->get();
                // menghitung jumlah material
                $count_material_ongoing   = MaterialTemporary::where('AUFNR', $production_order)->where('BDMNG', '!=', 0)->count();
                // jumlah quantity / SN dalam 1 PRO
                $qty_pro                  = $ongoing['quantity'];
                // menghitung jumlah PRO berdasarkan index
                $prevUsage = array_filter($new_ongoing, function ($item) use ($production_order, $ongoing) {
                    return $item['production_order'] == $production_order && $item['sn_index'] < $ongoing['sn_index'];
                });

                // looping material pro
                foreach ($material_ongoing as $m_on) {
                    // kondisi ketika requirement quantity > 0
                    if ($m_on->BDMNG != 0) {
                        // menghitung requirement quantity untuk tiap SN
                        $req_qty_sn         = $m_on->BDMNG / $qty_pro;
                        $plot               = $m_on->ENMNG + $m_on->RESERVE;
                        $gi                 = $m_on->ENMNG;
                        $alokasi            = $m_on->RESERVE;
        
                        // menghitung hasil plotting secara keseluruhan (alokasi + gi)
                        $plot               -= $req_qty_sn * count($prevUsage);
                        $plotting           = $plot >= $req_qty_sn ? $req_qty_sn : $plot;
                        $hasil_plot         = $plotting > 0 ? $plotting : 0;
                        
                        // menghitung hasil plotting good issue (gi)
                        $gi                 -= $req_qty_sn * count($prevUsage);
                        $plotting_gi        = $gi >= $req_qty_sn ? $req_qty_sn : $gi;
                        $hasil_gi           = $plotting_gi > 0 ? $plotting_gi : 0;
                        
                        // menghitung hasil ploting yang hanya alokasi saja (reserve)
                        $alokasi            -= $req_qty_sn * count($prevUsage);
                        $plotting_alokasi   = $alokasi >= $req_qty_sn ? $req_qty_sn : $alokasi;
                        $hasil_alokasi      = $plotting_alokasi > 0 ? $plotting_alokasi : 0;
        
                        // menghitung hasil plotting tiap sn untuk tiap material
                        $hasil_bagi_total   = $hasil_plot/$req_qty_sn;
                        $hasil_bagi_alokasi = $hasil_alokasi/$req_qty_sn;
                        $hasil_bagi_gi      = $hasil_gi/$req_qty_sn;

                        // menjumlahkan hasil perhitungan semua material
                        $total_ongoing      += $hasil_bagi_total;
                        $total_gi           += $hasil_bagi_gi;
                        $total_alokasi      += $hasil_bagi_alokasi;
                    }
                }
                // cek jumlah material apakah lebih dari 0
                $total_req_qty    = $count_material_ongoing == 0 ? 1 : $count_material_ongoing;
                // menghitung persentase total (alokasi + gi)
                $persen_ongoing   = round(($total_ongoing / $total_req_qty) * 100, 1);
                // menghitung persentasi gi
                $persen_gi        = round(($total_gi / $total_req_qty) * 100, 1);
                //  menghitung persentase alokasi ( baru ploting belum di gi)
                $persen_alokasi   = round(($total_alokasi / $total_req_qty) * 100, 1);
                
                $data_persen = [
                    'AUFNR'             => $ongoing['production_order'],
                    'SN'                => $ongoing['serial_number'],
                    'persen'            => $persen_ongoing,
                    'persen_gi'         => $persen_gi,
                    'persen_alokasi'    => $persen_alokasi
                ];
                $data_DB[] = $data_persen;
            }
    
            unset($new_ongoing);
            unset($material_ongoing);
            unset($data_persen);
            // mengambil data production order dengan status on going
            $pro = ProductionOrder::whereIn('STAT', $this->stat_ongoing)->get();
            $row = 0;
            // update data persen pada tabel production order
            try {
                foreach ($pro as $prod) {
                    foreach ($data_DB as $db) {
                        if (($prod['AUFNR'] == $db['AUFNR']) && ($prod['SERNR'] == $db['SN'])) {
                            $row++;
                            $prod['persen']           = $db['persen'];
                            $prod['persen_gi']        = $db['persen_gi'];
                            $prod['persen_alokasi']   = $db['persen_alokasi'];
                            $prod->save();
                        }
                    }
                }
            } catch (Exception $qe) {
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Percentage PRO On Going',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }


            $stop = Carbon::now();
            logCSV::insert([
                'proses'    => 'Update Percentage PRO On Going',
                'total_row' => $row,
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
            unset($pro);
            unset($data_DB);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function InsertPercentageHistory()
    {
        try{
            $start        = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);

            $i            = 0;
            // mengambil data PRO yang sudah masuk ke history
            $sn_history   = VwProWithSN::select('production_order', 'serial_number', 'quantity')
                ->whereIn('status', $this->stat_history)
                ->orderBy('sch_start_date', 'asc')
                ->orderBy('production_order', 'asc')
                ->orderBy('serial_number', 'asc')
                ->get();
            // menambah index pada data PRO
            foreach ($sn_history as $test) {
                $current                = $test->toArray();
                $current['sn_index']    = $i++;
                $new_history[]          = $current;
            }
            unset($sn_history);
            unset($current);
    
            foreach ($new_history as $ongoing) {
                // inisiasi variabel
                $total_req_qty    = 0;
                $total_history    = 0;
                $persen_history   = 0;
                // jumlah SN dalam 1 pro
                $qty_pro          = $ongoing['quantity'];
                // production order
                $aufnr            = $ongoing['production_order'];
                // mengambil data material dari view material history
                $mat_history      = VwMaterialListHistory::select('production_order', 'material_number', 'requirement_quantity', 'good_issue')
                    ->where('production_order', $ongoing['production_order'])
                    ->get();
                // menghitung jumlah material
                $count_material_history   = VwMaterialListHistory::where('production_order', $ongoing['production_order'])->where('requirement_quantity', '!=', 0)->count();

                $prevUsage = array_filter($new_history, function ($item) use ($aufnr, $ongoing) {
                    return $item['production_order'] == $aufnr && $item['sn_index'] < $ongoing['sn_index'];
                });
                
                foreach ($mat_history as $m_on) {
                    // kondisi ketika requirement quantity > 0
                    if ($m_on->requirement_quantity != 0) {
                        // requirement quantity tiap SN
                        $req_qty_sn       = $m_on->requirement_quantity / $qty_pro;
                        // jumlah gi
                        $plot             = $m_on->good_issue;
                        // menghitung jumlah gi tiap sn
                        $plot             -= $req_qty_sn * count($prevUsage);

                        // hasil plotting untuk tiap sn
                        $plotting         = $plot >= $req_qty_sn ? $req_qty_sn : $plot;
                        $hasil_plot       = $plotting >= 0 ? $plotting : 0;
                        $hasil_bagi       = $hasil_plot/$req_qty_sn;
                        // mengjumlahkan hasil bagi dan requirement quantity semua material dalam satu PRO
                        $total_history    += $hasil_bagi;
                        $total_req_qty    += $req_qty_sn;
                    }
                }
                // cek apakah jumlah material lebih dari 0
                $total_req_qty    = $count_material_history == 0 ? 1 : $count_material_history;
                // menghitung persentase history
                $persen_history = round(($total_history / $total_req_qty) * 100, 1);
                $data_persen = [
                    'AUFNR'     => $ongoing['production_order'],
                    'SN'        => $ongoing['serial_number'],
                    'persen'    => $persen_history
                ];
                $data_DB[] = $data_persen;
            }
            unset($new_history);
            unset($mat_history);
            unset($prevUsage);
            unset($data_persen);
            // get data PRO History
            $pro = ProductionOrder::whereIn('STAT', $this->stat_history)->get();
            $row = 0;
            // update data persen history pada tabel ProductionOrder
            try {
                foreach ($pro as $prod) {
                    foreach ($data_DB as $db) {
                        if (($prod['AUFNR'] == $db['AUFNR']) && ($prod['SERNR'] == $db['SN'])) {
                            $row++;
                            $prod['persen']       = $db['persen'];
                            $prod['persen_gi']    = $db['persen'];
                            $prod->save();
                        }
                    }
                }
            } catch (Exception $qe) {
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Percentage PRO History',
                    'start'     => $start,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }

            $stop = Carbon::now();
            logCSV::insert([
                'proses'    => 'Update Percentage PRO History',
                'total_row' => $row,
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
            unset($pro);
            unset($data_DB);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }
    
    public function UpdatePercentageGIComponent()
    {
        $start        = Carbon::now();
        $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);

        // Mengosongkan tabel Report GI Component
        ReportGI::truncate();
        
        try {
            $pro_all = ProductionOrder::select('AUFNR')->distinct()->whereIn('STAT', $this->stat_ongoing)->orderBy('AUFNR')->get();
            foreach ($pro_all as $pro) {
                $zcom = MaterialTemporary::select( 
                        DB::raw('SUM(BDMNG) as req_qty_zcom, SUM(ENMNG) as gi_zcom, SUM(RESERVE) as allocated_zcom'))
                        ->where('MTART', 'ZCOM')
                        ->where('AUFNR', $pro->AUFNR)
                        ->first();
                $zbup = MaterialTemporary::select( 
                        DB::raw('SUM(BDMNG) as req_qty_zbup, SUM(ENMNG) as gi_zbup, SUM(RESERVE) as allocated_zbup'))
                        ->where('MTART', 'ZBUP')
                        ->where('AUFNR', $pro->AUFNR)
                        ->first();
                $zcns = MaterialTemporary::select( 
                        DB::raw('SUM(BDMNG) as req_qty_zcns, SUM(ENMNG) as gi_zcns, SUM(RESERVE) as allocated_zcns'))
                        ->where('MTART', 'ZCNS')
                        ->where('AUFNR', $pro->AUFNR)
                        ->first();
                $zraw = MaterialTemporary::select( 
                        DB::raw('SUM(BDMNG) as req_qty_zraw, SUM(ENMNG) as gi_zraw, SUM(RESERVE) as allocated_zraw'))
                        ->where('MTART', 'ZRAW')
                        ->where('AUFNR', $pro->AUFNR)
                        ->first();

                $data[] = [
                    'production_order'  => $pro->AUFNR,
                    'req_qty_zcom'      => $zcom->req_qty_zcom ?? 0,
                    'gi_zcom'           => $zcom->gi_zcom ?? 0,
                    'allocated_zcom'    => $zcom->allocated_zcom ?? 0,
                    'req_qty_zbup'      => $zbup->req_qty_zbup ?? 0,
                    'gi_zbup'           => $zbup->gi_zbup ?? 0,
                    'allocated_zbup'    => $zbup->allocated_zbup ?? 0,
                    'req_qty_zcns'      => $zcns->req_qty_zcns ?? 0,
                    'gi_zcns'           => $zcns->gi_zcns ?? 0,
                    'allocated_zcns'    => $zcns->allocated_zcns ?? 0,
                    'req_qty_zraw'      => $zraw->req_qty_zraw ?? 0,
                    'gi_zraw'           => $zraw->gi_zraw ?? 0,
                    'allocated_zraw'    => $zraw->allocated_zraw ?? 0
                ];
            }
            
            $chunks = array_chunk($data, 500);
            foreach ($chunks as $chunk) {
                ReportGI::insert($chunk);
            }
                       
            $end = Carbon::now();

            logCSV::insert([
                'proses'    => 'Report GI Component',
                'start'     => $start,
                'stop'      => $end,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
        } catch (Exception $qe) {
            $stop = Carbon::now();
            logCSV::insert([
                'proses'    => 'Report GI Component',
                'start'     => $start,
                'stop'      => $stop,
                'status'    => 'Error',
                'message'   => $qe->errorInfo[2],
                'upload_by' => $upload_by
            ]);
            die;
        }
    }

    public function UpdateMatListManual()
    {
        $location   = public_path('completeness-component/MatListManual.csv');
        if (file_exists($location)) {
            $dataCSVMaterialManual = [];

            $worksheet  = fopen("{$location}", "r");
            // $flag = true;
            $row = 0;
            while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                // if ($flag) {
                //     $flag = false;
                //     continue;
                // }
                $row++;
                $dataCSVMaterialManual[]= $worksheet_data;
            }
            fclose($worksheet);
            
            unset($worksheet);
            // unset($flag);
            unset($worksheet_data);
            
            if (!empty($dataCSVMaterialManual)) {
                foreach ($dataCSVMaterialManual as $mat_manual) {
                    // reservation number
                    if ($mat_manual[0]== '') {
                        $mat_manual[0]=NULL;
                    }else{
                        $mat_manual[0] = ltrim($mat_manual[0], 0);
                        if ($mat_manual[0]== '') {
                            $mat_manual[0] = NULL;
                        }
                    }
                    
                    // item number
                    if ($mat_manual[1]== '') {
                        $mat_manual[1]=NULL;
                    }else{
                        $mat_manual[1] = ltrim($mat_manual[1], 0);
                        if ($mat_manual[1]== '') {
                            $mat_manual[1] = NULL;
                        }
                    }
                    
                    // MATNR
                    if ($mat_manual[2]== '') {
                        $mat_manual[2] = NULL;
                    }
                    
                    // plant (WERKS)
                    if ($mat_manual[3] == '') {
                        $mat_manual[3] = NULL;
                    }
                    
                    // LGORT(Storage Location)
                    if ($mat_manual[4] == '') {
                        $mat_manual[4] = NULL;
                    }
                    
                    // BDTER SAP
                    if ($mat_manual[5] == '' || $mat_manual[5] == '00.00.0000') {
                        $mat_manual[5]  = NULL;
                    } else {
                        $mat_manual[5]  = Carbon::createFromFormat('d/m/Y', $mat_manual[5])->format('Y-m-d');
                    }
                    
                    // Req. QTY (BDMNG)
                    $mat_manual[6] = str_replace(',000', '', $mat_manual[6]);
                    if ($mat_manual[6] == '') {
                        $mat_manual[6] = 0;
                    } else {
                        $mat_manual[6]      = str_replace(",", ".", $mat_manual[6]);
                    }
                    
                    // Withdraw QTY (ENMNG)
                    $mat_manual[8] = str_replace(',000', '', $mat_manual[8]);
                    if ($mat_manual[8] == '') {
                        $mat_manual[8] = 0;
                    } else {
                        $mat_manual[8]      = str_replace(",", ".", $mat_manual[8]);
                    }

                    // AUFNR
                    $mat_manual[9] = ltrim($mat_manual[9], 0);

                    // RSNUM	RSPOS	MATNR	WERKS	LGORT	BDTER	BDMNG	MEINS	ENMNG	AUFNR

                    if ($mat_manual[2] != '') {
                        $dataInsertMaterialManual[] = [
                            'RSNUM' => $mat_manual[0],
                            'RSPOS' => $mat_manual[1],
                            'MATNR' => $mat_manual[2],
                            'WERKS' => $mat_manual[3],
                            'LGORT' => $mat_manual[4],
                            'BDTER' => $mat_manual[5],
                            'BDMNG' => $mat_manual[6],
                            'MEINS' => $mat_manual[7],
                            'ENMNG' => $mat_manual[8],
                            'AUFNR' => $mat_manual[9]
                        ];
                    }
                }
                unset($dataCSVMaterialManual);
                $chunk_material_manual = array_chunk($dataInsertMaterialManual, 1000);
                unset($dataInsertMaterialManual);
                foreach ($chunk_material_manual as $a) {
                    foreach ($a as $item) {
                        Material::updateOrCreate([
                            'RSNUM' => $item['RSNUM'],
                            'RSPOS' => $item['RSPOS'],
                            'MATNR' => $item['MATNR'],
                            'LGORT' => $item['LGORT']
                        ],[
                            'ENMNG'     => $item['ENMNG']
                        ]);
                    }
                }
                unset($chunk_material_manual);
                $fileName = date('dmY') .'-'.date('his').'-MatListManual.csv';
                File::move(public_path('completeness-component/MatListManual.csv'), public_path('completeness-component/history/'. $fileName));
            }
        }
    }

    
    // FUNGSI YANG DIPANGGIL DI ROUTE
    
    // Update BDTER API Material
    public function updateDateAPIMaterial()
    {
        try{
            $now          = Carbon::now();
            $upload_by   = request()->name == null ? 'System' : str_replace("_", " ", request()->name);
            // get data material
            $material = Material::select('AUFNR', 'LTXA1', 'BDTER_API')->distinct()->get()->chunk(2000);
            foreach ($material as $a) {
                foreach ($a as $m1) {
                    $data1 = [
                        'AUFNR' => $m1['AUFNR'],
                        'LTXA1' => $m1['LTXA1'],
                        'BDTER_API' => $m1['BDTER_API'],
                    ];
                    $data_to_insert1[] = $data1;
                }
            }
            unset($material);
            // get data api requirement date 
            $get_apireqdate = apiReqDate::select('ProductionOrder', 'ReqDate', 'description')->distinct()->get()->chunk(200);
            foreach ($get_apireqdate as $b) {
                foreach ($b as $apireqdate) {
                    $dataZ = [
                        'AUFNR'     => $apireqdate['ProductionOrder'],
                        'LTXA1'    => $apireqdate['description'],
                        'BDTER_API'     => $apireqdate['ReqDate']
                    ];
                    $dataZX[] = $dataZ;
                }
            }
            unset($get_apireqdate);
            // merge data material dan requirement date
            foreach ($dataZX as $var1) {
                foreach ($data_to_insert1 as $var2) {
                    if (($var1['AUFNR'] == $var2['AUFNR']) && ($var1['LTXA1'] == $var2['LTXA1']) && ($var1['BDTER_API'] != $var2['BDTER_API'])) {
                        $dataMerge = [
                            'AUFNR'     => $var2['AUFNR'],
                            'LTXA1'     => $var2['LTXA1'],
                            'BDTER_API' => $var1['BDTER_API']
                        ];
                        $hasil[] = $dataMerge;
                    }
                }
            }
            unset($dataZX);
            unset($data_to_insert1);
    
            foreach ($hasil as $a) {
                $varAUFNR[] = $a['AUFNR'];
            }
            // get array unique
            $aufnr = array_unique($varAUFNR);
            unset($varAUFNR);
            // get data material berdasarkan pro
            $get_material = Material::whereIn('AUFNR', $aufnr)->get();
            unset($aufnr);
            $row = 0;

            // update data bdter api pada tabel material
            try {
                foreach ($get_material as $b) {
                    foreach ($hasil as $a) {
                        if (($b['AUFNR'] == $a['AUFNR']) && ($b['LTXA1'] == $a['LTXA1'])) {
                            $row++;
                            $b['BDTER_API'] = $a['BDTER_API'];
                            $b->save();
                        }
                    }
                }
            } catch (Exception $qe) {
                $stop = Carbon::now();
                logCSV::insert([
                    'proses'    => 'Update Req. Date Material IMA',
                    'start'     => $now,
                    'stop'      => $stop,
                    'status'    => 'Error',
                    'message'   => $qe->errorInfo[2],
                    'upload_by' => $upload_by
                ]);
                die;
            }

            unset($get_material);
            unset($hasil);
    
            $akhir = Carbon::now();
            logCSV::insert([
                'proses'    => 'Update Req. Date Material IMA',
                'total_row' => $row,
                'start'     => $now,
                'stop'      => $akhir,
                'status'    => 'Success',
                'upload_by' => $upload_by
            ]);
            echo $akhir;
            echo "<br>";
            echo $now;
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function inventory()
    {
        $start    = Carbon::now();

        // $this->insertStock();
        $this->updateStock();
        
        $stop = Carbon::now();
        echo "<br>";
        echo $start;
        echo "<br>";
        echo $stop;
    }

    public function production_order()
    {
        $start    = Carbon::now();

        $this->updatePRO();
        $this->updateAPIPRO();

        $stop = Carbon::now();

        echo "<br>";
        echo $start;
        echo "<br>";
        echo $stop;
    }

    public function material()
    {
        $start    = Carbon::now();

        $this->updateAPIReqDateMaterial();
        $this->updateMaterial();

        $stop = Carbon::now();

        echo "<br>";
        echo $start;
        echo "<br>";
        echo $stop;
    }

    public function dataOlahanMaterial()
    {
        $start    = Carbon::now();

        DB::connection('mysql7')->statement('CALL sp_reset_id');

        $this->olahDataMaterial();

        $vars = array_keys(get_defined_vars());
        for ($i = 0; $i < sizeOf($vars); $i++) {
            unset($vars[$i]);
        }
        unset($vars,$i);

        $this->olahDataTemporaryStock();
        
        $vars = array_keys(get_defined_vars());
        for ($i = 0; $i < sizeOf($vars); $i++) {
            unset($vars[$i]);
        }
        unset($vars,$i);

        DB::connection('mysql7')->statement('CALL sp_calculation_stock_inventory');
        
        DB::connection('mysql7')->statement('CALL sp_update_ticket_pro');

        $stop = Carbon::now();

        echo "<br>";
        echo $start;
        echo "<br>";
        echo $stop;
    }

    public function persen()
    {
        $start    = Carbon::now();

        $this->InsertPercentageOngoing();
        $this->InsertPercentageHistory();
        $this->UpdatePercentageGIComponent();

        $stop = Carbon::now();

        echo "<br>";
        echo $start;
        echo "<br>";
        echo $stop;
    }    
}
