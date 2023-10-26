<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Middleware;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\apiReqDate;
use App\Models\Table\CompletenessComponent\Material;
use App\Models\Table\CompletenessComponent\ProductionOrder;
use App\Models\View\CompletenessComponent\VwProductionOrder;
use Carbon\Carbon;
use File;
use Exception;

class SAP_Insert extends Controller
{
    public $filenamePRO         = 'RECORDPRO.csv';
    public $filenameMaterial    = 'RECORDMATLIST.csv';

    public function InsertPRO()
    {
        try{
            $location   = public_path('completeness-component/' . $this->filenamePRO);
            if (file_exists($location)) {
                $worksheet  = fopen("{$location}", "r");
                // $flag = true;
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $dataCSVProductionOrder[] = $worksheet_data;
                }
                fclose($worksheet);
                
                // unset($flag);
                unset($worksheet_data);
                unset($worksheet);
    
                // Mengambil data PRO dari CSV
                foreach ($dataCSVProductionOrder as $dataPRO) {
                    $aufnr      = ltrim($dataPRO[0], 0);
                    $unik_aufnr[] = $aufnr;
                }
                unset($aufnr);
    
                // Mengambil semua unique AUFNR dari array CSV 
                $unique_aufnr = array_unique($unik_aufnr);
                $chunk_aufnr = array_chunk($unique_aufnr, 50);
                $array = array_map(fn ($item) => implode('_', $item), $chunk_aufnr);
    
                unset($unik_aufnr);
                unset($unique_aufnr);
                unset($chunk_aufnr);
    
                // mengambil data API START DATE, FINISH DATE, GROUP PRODUCT berdasarkan PRO di CSV
                foreach ($array as $a) {
                    $apiPRO         = 'http://10.48.10.43/imaapi/api/CCR_DataPRO?pronum=' . $a;
                    $empDataPRO     = file_get_contents($apiPRO);
                    $dataAPIPRO[]   = json_decode($empDataPRO, true);
                }
    
                // mengambil data material berdasarkan PRO di CSV
                foreach ($array as $b) {
                    $apiMaterial         = 'http://10.48.10.43/imaapi/api/CCR_DateMaterial?pronum=' . $b;
                    $empDataMaterial     = file_get_contents($apiMaterial);
                    $dataMaterialAPI[]   = json_decode($empDataMaterial, true);
                }
    
                unset($array);
    
                foreach ($dataCSVProductionOrder as $dataPRO) {
                    $GroupProduct   = 'Others';
                    $finish_date_api      = NULL;
                    $start_date_api      = NULL;
                    // AUFNR
                    if ($dataPRO[0]) {
                        $dataPRO[0] = ltrim($dataPRO[0], 0);
                    }
                    // OBKNR
                    if ($dataPRO[5] == "") {
                        $dataPRO[5] = NULL;
                    }
                    // PPOSNR
                    if ($dataPRO[6]== '' || $dataPRO[6] == NULL) {
                        $dataPRO[6]=0;
                    }else{
                        $dataPRO[6] = ltrim($dataPRO[6], 0);
                        if ($dataPRO[6]== '') {
                            $dataPRO[6] = 0;
                        }
                    }
                    // DATUM PRO
                    if ($dataPRO[7] == '' || $dataPRO[7] == '00.00.0000') {
                        $dataPRO[7]  = NULL;
                    } else {
                        $dataPRO[7]  = Carbon::createFromFormat('d/m/Y', $dataPRO[7])->format('Y-m-d');
                    }
                    // UZEIT
                    if ($dataPRO[8] == "") {
                        $dataPRO[8] = NULL;
                    }
                    // ANZSN
                    if ($dataPRO[9] == "") {
                        $dataPRO[9] = NULL;
                    }
                    // OBZAE
                    if ($dataPRO[10] == "") {
                        $dataPRO[10] = NULL;
                    }
                    // EQUNR
                    if ($dataPRO[11] == "") {
                        $dataPRO[11] = NULL;
                    }
                    // SERNR
                    if ($dataPRO[12]== '' || $dataPRO[12] == NULL) {
                        $dataPRO[12]='-';
                    }else{
                        $dataPRO[12] = ltrim($dataPRO[12], 0);
                        if ($dataPRO[12]== '') {
                            $dataPRO[12] = '-';
                        }
                    }
                    // DATUM SN
                    if ($dataPRO[14] == '' || $dataPRO[14] == '00.00.0000') {
                        $dataPRO[14]  = NULL;
                    } else {
                        $dataPRO[14]  = Carbon::createFromFormat('d/m/Y', $dataPRO[14])->format('Y-m-d');
                    }
                    // RSNUM
                    if ($dataPRO[15]== '' || $dataPRO[15] == NULL) {
                        $dataPRO[15]=null;
                    }else{
                        $dataPRO[15] = ltrim($dataPRO[15], 0);
                        if ($dataPRO[15]== '') {
                            $dataPRO[15] = null;
                        }
                    }
                    // GLTRP
                    if ($dataPRO[16] == '' || $dataPRO[16] == '00.00.0000') {
                        $dataPRO[16]  = NULL;
                    } else {
                        $dataPRO[16]  = Carbon::createFromFormat('d/m/Y', $dataPRO[16])->format('Y-m-d');
                    }
                    // GSTRP
                    if ($dataPRO[17] == '' || $dataPRO[17] == '00.00.0000') {
                        $dataPRO[17]  = NULL;
                    } else {
                        $dataPRO[17]  = Carbon::createFromFormat('d/m/Y', $dataPRO[17])->format('Y-m-d');
                    }
                    // FTRMS
                    if ($dataPRO[18] == '' || $dataPRO[18] == '00.00.0000') {
                        $dataPRO[18]  = NULL;
                    } else {
                        $dataPRO[18]  = Carbon::createFromFormat('d/m/Y', $dataPRO[18])->format('Y-m-d');
                    }
                    // GLTRS
                    if ($dataPRO[19] == '' || $dataPRO[19] == '00.00.0000') {
                        $dataPRO[19]  = NULL;
                    } else {
                        $dataPRO[19]  = Carbon::createFromFormat('d/m/Y', $dataPRO[19])->format('Y-m-d');
                    }
                    // GSTRS
                    if ($dataPRO[20] == '' || $dataPRO[20] == '00.00.0000') {
                        $dataPRO[20]  = NULL;
                    } else {
                        $dataPRO[20]  = Carbon::createFromFormat('d/m/Y', $dataPRO[20])->format('Y-m-d');
                    }
                    // GSTRI
                    if ($dataPRO[21] == '' || $dataPRO[21] == '00.00.0000') {
                        $dataPRO[21]  = NULL;
                    } else {
                        $dataPRO[21]  = Carbon::createFromFormat('d/m/Y', $dataPRO[21])->format('Y-m-d');
                    }
                    // GLTRI
                    if ($dataPRO[22] == '' || $dataPRO[22] == '00.00.0000') {
                        $dataPRO[22]  = NULL;
                    } else {
                        $dataPRO[22]  = Carbon::createFromFormat('d/m/Y', $dataPRO[22])->format('Y-m-d');
                    }
                    // FTRMI
                    if ($dataPRO[23] == '' || $dataPRO[23] == '00.00.0000') {
                        $dataPRO[23]  = NULL;
                    } else {
                        $dataPRO[23]  = Carbon::createFromFormat('d/m/Y', $dataPRO[23])->format('Y-m-d');
                    }
                    // PRUEFLOS
                    if ($dataPRO[24]== '' || $dataPRO[24] == NULL) {
                        $dataPRO[24]=0;
                    }else{
                        $dataPRO[24] = ltrim($dataPRO[24], 0);
                        if ($dataPRO[24]== '') {
                            $dataPRO[24] = 0;
                        }
                    }
                    // STRMP
                    if ($dataPRO[26] == '' || $dataPRO[26] == '00.00.0000') {
                        $dataPRO[26]  = NULL;
                    } else {
                        $dataPRO[26]  = Carbon::createFromFormat('d/m/Y', $dataPRO[26])->format('Y-m-d');
                    }
                    // ETRMP
                    if ($dataPRO[27] == '' || $dataPRO[27] == '00.00.0000') {
                        $dataPRO[27]  = NULL;
                    } else {
                        $dataPRO[27]  = Carbon::createFromFormat('d/m/Y', $dataPRO[27])->format('Y-m-d');
                    }
                    // KDPOS
                    if ($dataPRO[29]== '' || $dataPRO[29] == NULL) {
                        $dataPRO[29]=0;
                    }else{
                        $dataPRO[29] = ltrim($dataPRO[29], 0);
                        if ($dataPRO[29]== '') {
                            $dataPRO[29] = 0;
                        }
                    }
                    // PSMNG
                    if ($dataPRO[30]) {
                        $dataPRO[30] = trim(str_replace(',000', '', $dataPRO[30]));
                    }
                    // LTRMI
                    if ($dataPRO[32] == '' || $dataPRO[32] == '00.00.0000') {
                        $dataPRO[32]  = NULL;
                    } else {
                        $dataPRO[32]  = Carbon::createFromFormat('d/m/Y', $dataPRO[32])->format('Y-m-d');
                    }
                    // LTRMP
                    if ($dataPRO[33] == '' || $dataPRO[33] == '00.00.0000') {
                        $dataPRO[33]  = NULL;
                    } else {
                        $dataPRO[33]  = Carbon::createFromFormat('d/m/Y', $dataPRO[33])->format('Y-m-d');
                    }
                    // STlNR
                    if ($dataPRO[38] == "") {
                        $dataPRO[38] = NULL;
                    }else {
                        $dataPRO[38] = ltrim($dataPRO[38], 0);
                    }
                    // STLAL
                    if ($dataPRO[39] == "") {
                        $dataPRO[39] = NULL;
                    }else {
                        $dataPRO[39] = ltrim($dataPRO[39], 0);
                    }
                    // AUFPL
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
    
                    // DATA API START DATE (STRMP), FINISH DATE (GLTRP), GROUP PRODUCT
                    foreach ($dataAPIPRO as $api) {
                        if (!empty($api)) {
                            foreach ($api as $a) {
                                if ($dataPRO[0] == $a['ProductionOrder']) {
                                    $finish_date        = $a['finishdate'];
                                    $start_date         = $a['planstartdate'];
                                    if ($finish_date == NULL) {
                                        $finish_date_api      = NULL;
                                    } else {
                                        $finish_date_api      = substr($finish_date, 0, 10);
                                    }
                                    if ($start_date == NULL) {
                                        $start_date_api      = NULL;
                                    } else {
                                        $start_date_api      = substr($start_date, 0, 10);
                                    }
                                    $GroupProduct   = $a['GroupProduct'];
                                    break;
                                }
                                if ($dataPRO[13] == $a['ProductNumber']) {
                                    $GroupProduct   = $a['GroupProduct'];
                                }
                            }
                        }
                    }
    
                    $dataInsertPRO = [
                        // Order
                        'AUFNR'         => $dataPRO[0],
                        // Order Type	
                        'AUART'         => $dataPRO[1],
                        // Order category	
                        'AUTYP'         => $dataPRO[2],
                        // Entered by	
                        'ERNAM'         => $dataPRO[3],
                        // PRO Creator	
                        'CREATOR'       => $dataPRO[4],
                        // Object list	
                        'OBKNR'         => $dataPRO[5],
                        // Item Number	
                        'PPOSNR'        => $dataPRO[6],
                        // Create Date PRO	
                        'DATUM_PRO'     => $dataPRO[7],
                        // Time	
                        'UZEIT'         => $dataPRO[8],
                        // No.serial no.	
                        'ANZSN'         => $dataPRO[9],
                        // Object counters	
                        'OBZAE'         => $dataPRO[10],
                        // Equipment	
                        'EQUNR'         => $dataPRO[11],
                        // Serial number	
                        'SERNR'         => $dataPRO[12],
                        // Material	
                        'MATNR'         => $dataPRO[13],
                        // Create Date SN	
                        'DATUM_SN'      => $dataPRO[14],
                        // Reservation	
                        'RSNUM'         => $dataPRO[15],
                        // Basic fin. date	
                        'GLTRP_SAP'     => $dataPRO[16],
                        // Basic fin. date	API IMA
                        'finish_date_api'     => $finish_date_api,
                        // Bas. Start Date	
                        'GSTRP'         => $dataPRO[17],
                        // Release	
                        'FTRMS'         => $dataPRO[18],
                        // Sched. finish	
                        'GLTRS'         => $dataPRO[19],
                        // Sched. start	
                        'GSTRS'         => $dataPRO[20],
                        // Actual start	
                        'GSTRI'         => $dataPRO[21],
                        // Actual finish	
                        'GLTRI'         => $dataPRO[22],
                        // Actual release	
                        'FTRMI'         => $dataPRO[23],
                        // Inspection Lot	
                        'PRUEFLOS'      => $dataPRO[24],
                        // Planned order	
                        'PLNUM'         => $dataPRO[25],
                        // Pld start date	
                        'STRMP_SAP'     => $dataPRO[26],
                        // Pld start date API IMA	
                        'start_date_api'     => $start_date_api,
                        // Open. date plnd	
                        'ETRMP'         => $dataPRO[27],
                        // Sales order	
                        'KDAUF'         => $dataPRO[28],
                        // Sales ord. item	
                        'KDPOS'         => $dataPRO[29],
                        // Order quantity	
                        'PSMNG'         => $dataPRO[30],
                        // Base Unit	
                        'MEINS'         => $dataPRO[31],
                        // Act.deliv.date	
                        'LTRMI'         => $dataPRO[32],
                        // PldOrd Del.date	
                        'LTRMP'         => $dataPRO[33],
                        // Deletion Flag	
                        'XLOEK'         => $dataPRO[34],
                        // Object number	
                        'OBJNR'         => $dataPRO[35],
                        // Status	
                        'STAT'          => $dataPRO[36],
                        // Plant	
                        'DWERK'         => $dataPRO[37],
                        // BoM	
                        'STLNR'         => $dataPRO[38],
                        // Alternative BoM	
                        'STLAL'         => $dataPRO[39],
                        // Plan no.f.oper.	
                        'AUFPL'         => $dataPRO[40],
                        // Description	
                        'KTEXT'         => $dataPRO[41],
                        // Released	
                        'PHAS1'         => $dataPRO[42],
                        // Completed	
                        'PHAS2'         => $dataPRO[43],
                        // Closed	
                        'PHAS3'         => $dataPRO[44],
                        // Release	
                        'IDAT1'         => $dataPRO[45],
                        // Tech.completion
                        'IDAT2'         => $dataPRO[46],
                        // Close	
                        'IDAT3'         => $dataPRO[47],
                        // Description
                        'MAKTX'         => $dataPRO[48],
                        // Group Product
                        'GroupProduct'  => $GroupProduct
                    ];
    
                    $dataProductionOrder[] = $dataInsertPRO;
                }
                // dd($dataProductionOrder);
    
                unset($dataCSVProductionOrder);
                unset($dataAPIPRO);
                unset($api);
                unset($dataInsertPRO);
    
                // Mengosongkan data dari DB
                ProductionOrder::truncate();
    
                // Insert data ke DB
                $chunksPRO = array_chunk($dataProductionOrder, 500);
                foreach ($chunksPRO as $PROInsert) {
                    ProductionOrder::insert($PROInsert);
                }
    
                unset($dataProductionOrder);
                unset($chunksPRO);
                unset($PROInsert);
    
                // Memasukan data API ReqDate untuk Material ke Database
                apiReqDate::truncate();
    
                foreach ($dataMaterialAPI as $PROInsert) {
                    apiReqDate::insert($PROInsert);
                }
                unset($dataMaterialAPI);
                unset($PROInsert);
    
                File::move(public_path('completeness-component/' . $this->filenamePRO), public_path('completeness-component/history/'. date('dmY') .'-'.date('his').'-'. $this->filenamePRO));
            } else {
                echo 'File Production Order Not Found!';
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    // untuk insert data tabel material dan inventory
    public function InsertMaterial()
    {
        try{
            $location   = public_path('completeness-component/' . $this->filenameMaterial);
    
            if (file_exists($location)) {
                // Mengosongkan data dari DB
                Material::truncate();
    
                $worksheet  = fopen("{$location}", "r");
                // $flag = true;
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $dataCSVMaterial[] = $worksheet_data;
                }
                fclose($worksheet);
                // unset($flag);
                unset($worksheet);
                unset($worksheet_data);
                $chunks_dataCSVMaterial = array_chunk($dataCSVMaterial, 1000);
                unset($dataCSVMaterial);
                foreach ($chunks_dataCSVMaterial as $a) {
                    foreach ($a as $dataMaterial) {
                        // deskripsi komponen
                        $maktx      = preg_replace('/[^A-Za-z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\;\:\"\'\,\<\.\>\/\?\s]/', '', $dataMaterial[3]);
                        // reservation number
                        if ($dataMaterial[0]== '') {
                            $dataMaterial[0]=NULL;
                        }else{
                            $dataMaterial[0] = ltrim($dataMaterial[0], 0);
                            if ($dataMaterial[0]== '') {
                                $dataMaterial[0] = NULL;
                            }
                        }
                        // item number
                        if ($dataMaterial[1]== '') {
                            $dataMaterial[1]=NULL;
                        }else{
                            $dataMaterial[1] = ltrim($dataMaterial[1], 0);
                            if ($dataMaterial[1]== '') {
                                $dataMaterial[1] = NULL;
                            }
                        }
                        // plant (WERKS)
                        if ($dataMaterial[4] == '') {
                            $dataMaterial[4] = NULL;
                        }
                        // LGORT(Storage Location)
                        if ($dataMaterial[5] == '') {
                            $dataMaterial[5] = NULL;
                        }
                        // BDTER SAP
                        if ($dataMaterial[6] == '' || $dataMaterial[6] == '00.00.0000') {
                            $dataMaterial[6]  = NULL;
                        } else {
                            $dataMaterial[6]  = Carbon::createFromFormat('d/m/Y', $dataMaterial[6])->format('Y-m-d');
                        }
                        // Req. QTY (BDMNG)
                        $dataMaterial[7] = str_replace(',000', '', $dataMaterial[7]);
                        if ($dataMaterial[7] == '') {
                            $dataMaterial[7] = 0;
                        } else {
                            $dataMaterial[7]      = str_replace(",", ".", $dataMaterial[7]);
                        }
                        // Stock (LBAST)
                        $dataMaterial[8] = str_replace(',000', '', $dataMaterial[8]);
                        if ($dataMaterial[8] == '' || $dataMaterial[8] == NULL) {
                            $dataMaterial[8] = 0;
                        } else {
                            $dataMaterial[8]      = str_replace(",", ".", $dataMaterial[8]);
                        }
                        // In QC (INSME)
                        $dataMaterial[9] = str_replace(',000', '', $dataMaterial[9]);
                        if ($dataMaterial[9] == '' || $dataMaterial[9] == NULL) {
                            $dataMaterial[9] = 0;
                        } else {
                            $dataMaterial[9]      = str_replace(",", ".", $dataMaterial[9]);
                        }
                        // Withdraw QTY (ENMNG)
                        $dataMaterial[11] = str_replace(',000', '', $dataMaterial[11]);
                        if ($dataMaterial[11] == '') {
                            $dataMaterial[11] = 0;
                        } else {
                            $dataMaterial[11]      = str_replace(",", ".", $dataMaterial[11]);
                        }
                        // BOM
                        $dataMaterial[12] = ltrim($dataMaterial[12], 0);
                        if ($dataMaterial[12] == '' || $dataMaterial[12] == '') {
                            $dataMaterial[12] = NULL;
                        }
                        // Alternative Plan
                        $dataMaterial[13] = ltrim($dataMaterial[13], 0);
                        if ($dataMaterial[13] == '' || $dataMaterial[13] == '') {
                            $dataMaterial[13] = NULL;
                        }
                        // No. F. Oper
                        $dataMaterial[14] = ltrim($dataMaterial[14], 0);
                        if ($dataMaterial[14] == '') {
                            $dataMaterial[14] = NULL;
                        }
                        // Operation
                        $dataMaterial[15] = ltrim($dataMaterial[15], 0);
                        if ($dataMaterial[15] == '') {
                            $dataMaterial[15] = NULL;
                        }
                        // AUFNR
                        $dataMaterial[18] = ltrim($dataMaterial[18], 0);
                        // Material Group
                        if ($dataMaterial[20] == '') {
                            $dataMaterial[20] = NULL;
                        }
                        $dataMaterial[22] = ltrim($dataMaterial[22], 0);
                        $dataMaterial[24] = ltrim($dataMaterial[24], 0);
    
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
                            // Unrestricted	
                            'LABST'     => $dataMaterial[8],
                            // In Qual. Insp.	
                            'INSME'     => $dataMaterial[9],
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
                        if ($dataMaterial[2] != '') {
                            $dataMaterialDB[] = $dataInsertMaterial;
                        }
                    }
                }
                // dd($dataInsertMaterial);
                unset($chunks_dataCSVMaterial);
                unset($a);
                unset($dataMaterial);
                unset($dataInsertMaterial);
    
                $productionOrder = VwProductionOrder::select('AUFNR', 'MATNR', 'MAKTX', 'GroupProduct', 'PSMNG', 'sch_start_date', 'sch_finish_date', 'STAT')->distinct()->orderBy('AUFNR', 'ASC')->get()->chunk(200);
                foreach ($productionOrder as $b) {
                    foreach ($b as $pro) {
                        $dataZ = [
                            'AUFNR'     => $pro['AUFNR'],
                            'PLNBEZ'    => $pro['MATNR'],
                            'DESC_PLNBEZ'     => $pro['MAKTX'],
                            'GroupProduct'      => $pro['GroupProduct'],
                            'GAMNG'     => $pro['PSMNG'],
                            'sch_start_date'     => $pro['sch_start_date'],
                            'sch_finish_date'     => $pro['sch_finish_date'],
                            'STAT'     => $pro['STAT']
                        ];
                        $dataZX[] = $dataZ;
                    }
                }
                unset($productionOrder);
                unset($b);
                unset($pro);
                unset($dataZ);
    
                /*Kondisi ketika Array 1 dan Array 2 mempunyai PRO yang sama lalu di Merge*/
                foreach ($dataMaterialDB as $var1) {
                    foreach ($dataZX as $var2) {
                        if ($var1['AUFNR'] == $var2['AUFNR']) {
                            $hasil[] = $var1 + $var2;
                        }
                    }
                }
                unset($dataMaterialDB);
                unset($var1);
                unset($dataZX);
                unset($var2);
    
                $chunksMaterial = array_chunk($hasil, 500);
                unset($hasil);
                // // Insert data material ke DB
                foreach ($chunksMaterial as $z) {
                    Material::insert($z);
                }
                unset($chunksMaterial);
                unset($z);
                File::move(public_path('completeness-component/' . $this->filenameMaterial), public_path('completeness-component/history/'. date('dmY') .'-'.date('his').'-'. $this->filenameMaterial));
            } else {
                echo 'File Material Not Found!';
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }
}
