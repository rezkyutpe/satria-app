<?php

namespace App\Http\Controllers\Cms\CompletenessComponent\Middleware;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\apiReqDate;
use App\Models\Table\CompletenessComponent\Inventory;
use App\Models\Table\CompletenessComponent\Material;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\Table\CompletenessComponent\ProductionOrder;
use App\Models\Table\CompletenessComponent\MaterialHasilOlah;
use App\Models\View\CompletenessComponent\VwMaterialListHistory;
use App\Models\View\CompletenessComponent\VwProductionOrder;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Exception;

class SAP_Update extends Controller
{
    public $stat_ongoing = ['CRTD', 'REL', 'DLV', 'PDLV'];
    public $stat_history = ['TECO', 'CLSD'];

    public $filenamePRO         = 'RECORDPRO.csv';
    public $filenameMaterial    = 'RECORDMATLIST.csv';

    public function updatePRO()
    {
        try{
            $location   = public_path('completeness-component/' . $this->filenamePRO);
            if (file_exists($location)) {
                $dataCSVProductionOrder = [];
    
                $worksheet  = fopen("{$location}", "r");
                // $flag = true;
                while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                    // if ($flag) {
                    //     $flag = false;
                    //     continue;
                    // }
                    $dataCSVProductionOrder[]= $worksheet_data;
                }
                fclose($worksheet);
                
                unset($worksheet);
                // unset($flag);
                unset($worksheet_data);
    
                if (!empty($dataCSVProductionOrder)) {
                    // Mengambil data PRO dari CSV
                    foreach ($dataCSVProductionOrder as $dataPRO) {
                        $aufnr      = ltrim($dataPRO[0], 0);
                        $unik_aufnr[] = $aufnr;
                    }
                    unset($dataPRO);
                    unset($aufnr);
    
                    // Mengambil semua unique AUFNR dari array CSV 
                    $unique_aufnr = array_unique($unik_aufnr);
                    unset($unik_aufnr);
    
                    ProductionOrder::whereIn('AUFNR', $unique_aufnr)->delete();
    
                    $chunk_aufnr = array_chunk($unique_aufnr, 50);
                    $array = array_map(fn ($item) => implode('_', $item), $chunk_aufnr);
    
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
                        // PLNUM
                        if ($dataPRO[25]== '' || $dataPRO[25] == NULL) {
                            $dataPRO[25]=0;
                        }else{
                            $dataPRO[25] = ltrim($dataPRO[25], 0);
                            if ($dataPRO[25]== '') {
                                $dataPRO[25] = 0;
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

                        // DATE_STAT_CREATED
                        if ($dataPRO[50] == '00.00.0000' || $dataPRO[50] == '0' || $dataPRO[50] == '') {
                            $dataPRO[50]  = NULL;
                        } else {
                            $dataPRO[50]  = Carbon::createFromFormat('d/m/Y', $dataPRO[50])->format('Y-m-d');
                        }
    
                        // DATA API START DATE (STRMP), FINISH DATE (GLTRP), GROUP PRODUCT
                        foreach ($dataAPIPRO as $api) {
                            if (!empty($api)) {
                                foreach ($api as $a) {
                                    if ($dataPRO[0] == $a['ProductionOrder'] && $dataPRO[12] == $a['SerialNumbers']) {
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
                            // Status Description
                            'STAT_DESC'         => $dataPRO[49],
                            // Date Status Created
                            'DATE_STAT_CREATED'         => $dataPRO[50],
                            // Group Product
                            'GroupProduct'  => $GroupProduct,
                            // Pld start date API IMA	
                            'start_date_api'     => $start_date_api,
                            // Basic fin. date	API IMA
                            'finish_date_api'     => $finish_date_api,
                        ];
    
                        $dataProductionOrder[] = $dataInsertPRO;
                    }
    
                    unset($dataCSVProductionOrder);
                    unset($dataPRO);
                    unset($dataAPIPRO);
                    unset($api);
                    unset($a);
                    unset($dataInsertPRO);
                    
                    // Insert data ke DB
                    $chunksPRO = array_chunk($dataProductionOrder, 1000);
                    foreach ($chunksPRO as $PROInsert) {
                        ProductionOrder::insert($PROInsert);
                    }
                    DB::connection('mysql7')->statement('CALL sp_reset_id_pro');
                    unset($dataProductionOrder);
                    unset($chunksPRO);
                    unset($PROInsert);
                }    
    
                File::move(public_path('completeness-component/' . $this->filenamePRO), public_path('completeness-component/history/'. date('dmY') .'-'.date('his').'-'. $this->filenamePRO));
    
            } else {
                echo 'File Production Order Not Found!';
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function apiPRO()
    {
        try{
            // $pro_DB = ProductionOrder::select('AUFNR')->distinct()->orderBy('AUFNR', 'ASC')->whereIn('STAT', ['CRTD', 'REL', 'DLV', 'DEL'])->get();
            $pro_DB = ProductionOrder::select('AUFNR')->distinct()->orderBy('AUFNR', 'ASC')->get();
            foreach ($pro_DB as $proDB) {
                $pro[] = $proDB['AUFNR'];
            }
            unset($pro_DB);
            $chunk_aufnr = array_chunk($pro, 50);
            unset($pro);
            $array = array_map(fn ($item) => implode('_', $item), $chunk_aufnr);
            unset($chunk_aufnr);
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
                            'AUFNR' => $api['ProductionOrder'],
                            'MATNR' => $api['ProductNumber'],
                            'GroupProduct' => $api['GroupProduct'],
                            'SerialNumber' => $api['SerialNumbers'],
                            'finishdate' => substr($api['finishdate'], 0, 10),
                            'startdate' => substr($api['planstartdate'], 0, 10),
                        ];
                    }
                }
            }
            unset($dataAPIPRO);
            unset($dataAPI);
            unset($api);
    
            // $proDB = ProductionOrder::whereIn('STAT', ['CRTD', 'REL', 'DLV', 'DEL'])->get();
            $proDB = ProductionOrder::all();
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
                        $proDB['GroupProduct'] = 'Others';
                        if ($hasil['GroupProduct'] != null) {
                            $proDB['GroupProduct'] = $hasil['GroupProduct'];
                        }
                        $proDB->save();
                    }
                }
            }
            unset($proDB);
            unset($hasil_pro);
            unset($hasil);
            unset($proDB);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function apiMaterial()
    {
        try{
            $pro_DB = ProductionOrder::select('AUFNR')->distinct()->whereIn('STAT', $this->stat_ongoing)->orderBy('AUFNR', 'ASC')->get();
            foreach ($pro_DB as $proDB) {
                $pro[] = $proDB['AUFNR'];
            }
            unset($pro_DB);
            unset($proDB);
            $chunk_aufnr = array_chunk($pro, 50);
            unset($pro);
            $array = array_map(fn ($item) => implode('_', $item), $chunk_aufnr);
            unset($chunk_aufnr);
            unset($item);
            foreach ($array as $b) {
                $apiMaterial         = 'http://10.48.10.43/imaapi/api/CCR_DateMaterial?pronum=' . $b;
                $empDataMaterial     = file_get_contents($apiMaterial);
                $dataMaterialAPI[]   = json_decode($empDataMaterial, true);
            }
            unset($array);
            apiReqDate::truncate();
            foreach ($dataMaterialAPI as $dataAPI) {
                apiReqDate::insert($dataAPI);
            }
            unset($dataMaterialAPI);
            unset($dataAPI);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function Material()
    {
        try{
            $location   = public_path('completeness-component/' . $this->filenameMaterial);
            if (file_exists($location)) {
                $dataCSVMaterial = [];
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
    
                unset($worksheet);
                unset($worksheet_data);
                // unset($flag);
                
                if(!empty($dataCSVMaterial)){
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
                            // MATNR
                            if ($dataMaterial[2]== '') {
                                $dataMaterial[2] = NULL;
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
    
                    $edit_pro = array_unique($pro);
                    Material::whereIn('AUFNR', $edit_pro)->delete();
                    unset($pro);
                    unset($edit_pro);
    
    
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
                    unset($dataZX);
                    unset($var1);
                    unset($var2);
    
                    $chunksMaterial = array_chunk($hasil, 1000);
                    unset($hasil);
                    // // Insert data material ke DB
                    foreach ($chunksMaterial as $z) {
                        Material::insert($z);
                    }
                    unset($chunksMaterial);
                    unset($z);
                }
    
                File::move(public_path('completeness-component/' . $this->filenameMaterial), public_path('completeness-component/history/'. date('dmY') .'-'.date('his').'-'. $this->filenameMaterial));
    
            } else {
                echo 'File Material Not Found!';
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function dateMaterial()
    {
        try{
            $now = Carbon::now();
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
    
            $aufnr = array_unique($varAUFNR);
            unset($varAUFNR);
            $get_material = Material::whereIn('AUFNR', $aufnr)->get();
            unset($aufnr);
            foreach ($get_material as $b) {
                foreach ($hasil as $a) {
                    if (($b['AUFNR'] == $a['AUFNR']) && ($b['LTXA1'] == $a['LTXA1'])) {
                        $b['BDTER_API'] = $a['BDTER_API'];
                        $b->save();
                    }
                }
            }
            unset($get_material);
            unset($hasil);
    
            $akhir = Carbon::now();
    
            echo $akhir;
            echo "<br>";
            echo $now;
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function MaterialOlahData()
    {
        try{
            MaterialHasilOlah::truncate();
            
            $data =  Material::leftJoin('vw_total_stock', 'material.MATNR', '=', 'vw_total_stock.MATNR')->select("AUFNR", "PLNBEZ", "DESC_PLNBEZ", "GroupProduct", "GAMNG", "sch_start_date", "sch_finish_date", "STAT","material.MATNR", "MAKTX", "LTXA1", "WERKS", "MEINS", "LGORT", "MTART", "MATKL", DB::raw('SUM(BDMNG) as BDMNG'), DB::raw('SUM(ENMNG) as ENMNG'), DB::raw('SUM(INSME) as INSME'), DB::raw('IFNULL(BDTER_API, BDTER_SAP) as BDTER'), 'vw_total_stock.STOCK')
                    ->groupBy('material.MATNR', 'AUFNR')
                    ->orderBy('BDTER', 'ASC')
                    ->get()
                    ->toArray();
            $chunks_DB = array_chunk($data, 1000);
            
            foreach ($chunks_DB as $value) {
                MaterialHasilOlah::insert($value);
            }
            unset($data);
            unset($chunks_DB);
            unset($value);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function TemporaryStock()
    {
        try{
            $data_materialList = MaterialHasilOlah::distinct()
                    ->whereIn('STAT', $this->stat_ongoing)
                    ->groupBy('MATNR', 'AUFNR')
                    ->orderBy('MATNR', 'ASC')
                    ->orderBy('BDTER', 'ASC')
                    ->orderBy('sch_start_date', 'ASC')
                    ->orderBy('AUFNR', 'ASC')
                    ->get();
    
            /*Inisialisasi Variable */
            $prevMATNR      = NULL;
            $prevRESTOCK    = NULL;
            $data_to_insert = [];
    
            foreach ($data_materialList as $dml) {
                if ($dml[0]) //Array ke [0]
                {
                    $reserve = $dml->BDMNG - $dml->ENMNG;
                    $restock = $dml->STOCK - $reserve;
                    if ($dml->STOCK == 0) {
                        $reserve    = 0;
                        $restock    = 0;
                        $prevMATNR  = $dml->MATNR;
                        $prevRESTOCK = $restock;
                    } elseif ($dml->STOCK - $reserve < 0) {
                        $restock    = 0;
                        $reserve    = $dml->STOCK;
                        $prevMATNR  = $dml->MATNR;
                        $prevRESTOCK = $restock;
                    } else {
                        $prevMATNR  = $dml->MATNR;
                        $prevRESTOCK = $restock;
                    }
                } else //setelah Array ke [0]
                {
                    if ($dml->MATNR == $prevMATNR) //untuk data kedua dan seterusnya dalam MATNR yg sama
                    {
                        $reserve = $dml->BDMNG - $dml->ENMNG; //perhitungan reserve
                        if($reserve <= 0){
                            $reserve = 0;
                        }
                        if (($prevRESTOCK - $reserve) <= 0) //
                        {
                            $restock    = 0;
                            $reserve    = $prevRESTOCK;
                            $prevRESTOCK = $restock;
                        } else {
                            $restock    = $prevRESTOCK - $reserve;
                            $prevRESTOCK = $restock;
                        }
                        $prevMATNR = $dml->MATNR;
                    } else //untuk data pertama MATNR
                    {
                        $reserve = $dml->BDMNG - $dml->ENMNG;
                        $restock = $dml->STOCK - $reserve;
                        if ($dml->STOCK == 0) {
                            $reserve    = 0;
                            $restock    = 0;
                            $prevMATNR  = $dml->MATNR;
                            $prevRESTOCK = $restock;
                        } elseif ($dml->STOCK - $reserve < 0) {
                            $restock    = 0;
                            $reserve    = $dml->STOCK;
                            $prevMATNR  = $dml->MATNR;
                            $prevRESTOCK = $restock;
                        } else {
                            $prevMATNR  = $dml->MATNR;
                            $prevRESTOCK = $restock;
                        }
                    }
                    $prevMATNR = $dml->MATNR;
                }
                $minus = ($dml->ENMNG + $reserve) - $dml->BDMNG;

                if($minus >= 0){
                    $minus = 0;
                }
                $data = [
                    'AUFNR'     => $dml->AUFNR,
                    'PLNBEZ'    => $dml->PLNBEZ,
                    'DESC_PLNBEZ'    => $dml->DESC_PLNBEZ,
                    'STAT'      => $dml->STAT,
                    'GAMNG'     => $dml->GAMNG,
                    'sch_start_date'    => $dml->sch_start_date,
                    'MATNR'     => $dml->MATNR,
                    'MAKTX'     => $dml->MAKTX,
                    'MEINS'     => $dml->MEINS,
                    'MTART'     => $dml->MTART,
                    'MATKL'     => $dml->MATKL,
                    'WERKS'     => $dml->WERKS,
                    'LGORT'     => $dml->LGORT,
                    'BDTER'     => $dml->BDTER,
                    'BDMNG'     => $dml->BDMNG,
                    'ENMNG'     => $dml->ENMNG,
                    'STOCK'     => $dml->STOCK,
                    'RESERVE'   => $reserve,
                    'MINUS_PLOTTING'     => $minus,
                    'RESTOCK'   => $restock,
                    'INSME'     => $dml->INSME,
                    'LTXA1'     => $dml->LTXA1
                ];
                $data_to_insert[] = $data;
            }
    
            unset($data_materialList);
            unset($data);
    
            MaterialTemporary::truncate();
            /*Insert data dengan mengubah array menjadi beberapa chunk */
            $chunks = array_chunk($data_to_insert, 1000);
            foreach ($chunks as $chunk) {
                MaterialTemporary::insert($chunk);
            }
    
            unset($data_to_insert);
            unset($chunks);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function Inventory()
    {
        try{
            $inventory = Material::leftJoin('material_temporary', 'material.MATNR', 'material_temporary.MATNR')->select('material.MATNR', 'material.MAKTX', 'material.MTART', 'material.MATKL', 'material.MEINS', 'material.WERKS', 'material.LGORT', 'material.LABST', 'material.INSME', DB::raw('MIN(RESTOCK) as FREE_STOCK,  material.LABST-min(RESTOCK) as ALLOCATED_STOCK'))
                ->distinct()
                ->where('material.LGORT', '!=', null)
                ->where('material.LGORT', '<', 2000)
                ->whereIn('material.STAT', $this->stat_ongoing)
                ->groupBy('material.MATNR', 'material.WERKS', 'material.LGORT')
                ->get();
            Inventory::truncate();
            foreach ($inventory as $item) {
                if ($item->ALLOCATED_STOCK < 0) {
                    $allocated = $item->LABST ;
                    $free = 0;
                }else {
                    $allocated = $item->ALLOCATED_STOCK ;
                    $free = $item->FREE_STOCK;
                }
                $data[] = [
                    'MATNR' => $item->MATNR,
                    'MAKTX' => $item->MAKTX,
                    'MTART' => $item->MTART,
                    'MATKL' => $item->MATKL,
                    'MEINS' => $item->MEINS,
                    'WERKS' => $item->WERKS,
                    'LGORT' => $item->LGORT,
                    'LABST' => $item->LABST,
                    'INSME' => $item->INSME,
                    'FREE_STOCK' => $free,
                    'ALLOCATED_STOCK' => $allocated
                ];
            }
            unset($inventory);
            unset($item);
            $chunks_DB = array_chunk($data, 1000);
            unset($data);
            foreach ($chunks_DB as $value) {
                Inventory::insert($value);
            }
            unset($chunks_DB);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function InsertPercentageOngoing()
    {
        try{
            $sn_ongoing = VwProWithSN::select('AUFNR', 'SN', 'PSMNG')->whereIn('STAT', $this->stat_ongoing)->orderBy('sch_start_date', 'asc')->orderBy('AUFNR', 'asc')->orderBy('SN', 'asc')->get();
            $i = 0;
            foreach ($sn_ongoing as $test) {
                $current = $test->toArray();
                $current['sn_index'] = $i++;
                $new_ongoing[] = $current;
            }
            unset($sn_ongoing);
            unset($current);
    
            foreach ($new_ongoing as $ongoing) {
                $total_ongoing = 0;
                $total_gi = 0;
                $total_alokasi = 0;
                $persen_ongoing = 0;
                $persen_gi = 0;
                $persen_alokasi = 0;
                $aufnr = $ongoing['AUFNR'];
                $material_ongoing = MaterialTemporary::select('AUFNR', 'MATNR', 'BDMNG', 'ENMNG', 'RESERVE')->where('AUFNR', $aufnr)->orderBy('RESERVE', 'DESC')->get();
                $jumlah_material = $material_ongoing->count();
                $qty_pro = $ongoing['PSMNG'];
                $prevUsage = array_filter($new_ongoing, function ($item) use ($aufnr, $ongoing) {
                    return $item['AUFNR'] == $aufnr && $item['sn_index'] < $ongoing['sn_index'];
                });
                foreach ($material_ongoing as $m_on) {
                    $req_qty_sn         = $m_on->BDMNG > 0 ? $m_on->BDMNG / $qty_pro : 1;
                    $plot               = $m_on->ENMNG + $m_on->RESERVE;
                    $gi                 = $m_on->ENMNG;
                    $alokasi            = $m_on->RESERVE;
    
                    $plot               -= $req_qty_sn * count($prevUsage);
                    $plotting           = $plot >= $req_qty_sn ? $req_qty_sn : $plot;
                    $hasil_plot         = $plotting > 0 ? $plotting : 0;
                    
                    $gi                 -= $req_qty_sn * count($prevUsage);
                    $plotting_gi        = $gi >= $req_qty_sn ? $req_qty_sn : $gi;
                    $hasil_gi           = $plotting_gi > 0 ? $plotting_gi : 0;
                    
                    $alokasi            -= $req_qty_sn * count($prevUsage);
                    $plotting_alokasi   = $alokasi >= $req_qty_sn ? $req_qty_sn : $alokasi;
                    $hasil_alokasi      = $plotting_alokasi > 0 ? $plotting_alokasi : 0;
    
                    $total_ongoing    += ($hasil_plot / $req_qty_sn);
                    $total_gi         += ($hasil_gi / $req_qty_sn);
                    $total_alokasi    += ($hasil_alokasi / $req_qty_sn);
                }
    
                $persen_ongoing   = $jumlah_material == 0 ? 0 : round(($total_ongoing / $jumlah_material) * 100, 1);
                $persen_gi        = $jumlah_material == 0 ? 0 : round(($total_gi / $jumlah_material) * 100, 1);
                $persen_alokasi   = $jumlah_material == 0 ? 0 : round(($total_alokasi / $jumlah_material) * 100, 1);
                $data_persen = [
                    'AUFNR' => $ongoing['AUFNR'],
                    'SN' => $ongoing['SN'],
                    'persen' => $persen_ongoing,
                    'persen_gi' => $persen_gi,
                    'persen_alokasi' => $persen_alokasi
                ];
                $data_DB[] = $data_persen;
            }
    
            unset($new_ongoing);
            unset($material_ongoing);
            unset($data_persen);
    
            $pro = ProductionOrder::whereIn('STAT', $this->stat_ongoing)->get();
            foreach ($pro as $prod) {
                foreach ($data_DB as $db) {
                    if (($prod['AUFNR'] == $db['AUFNR']) && ($prod['SERNR'] == $db['SN'])) {
                        $prod['persen']           = $db['persen'];
                        $prod['persen_gi']        = $db['persen_gi'];
                        $prod['persen_alokasi']   = $db['persen_alokasi'];
                        $prod->save();
                    }
                }
            }
            unset($pro);
            unset($data_DB);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function InsertPercentageHistory()
    {
        try{
            $sn_history = VwProWithSN::select('AUFNR', 'SN', 'PSMNG')->whereIn('STAT', $this->stat_history)->orderBy('sch_start_date', 'asc')->orderBy('AUFNR', 'asc')->orderBy('SN', 'asc')->get();
            $i = 0;
            foreach ($sn_history as $test) {
                $current = $test->toArray();
                $current['sn_index'] = $i++;
                $new_history[] = $current;
            }
            unset($sn_history);
            unset($current);
    
            foreach ($new_history as $ongoing) {
                $total_history = 0;
                $persen_history = 0;
                $mat_history = VwMaterialListHistory::select('AUFNR', 'MATNR', 'BDMNG', 'ENMNG')->where('AUFNR', $ongoing['AUFNR'])->get();
                $jumlah_material = $mat_history->count();
                $qty_pro = $ongoing['PSMNG'];
                $aufnr = $ongoing['AUFNR'];
                $prevUsage = array_filter($new_history, function ($item) use ($aufnr, $ongoing) {
                    return $item['AUFNR'] == $aufnr && $item['sn_index'] < $ongoing['sn_index'];
                });
                foreach ($mat_history as $m_on) {
                    $req_qty_sn = $m_on->BDMNG == 0 ? 1 : $m_on->BDMNG / $qty_pro;
                    $plot = $m_on->ENMNG;
    
                    $plot   -= $req_qty_sn * count($prevUsage);
    
                    $plotting = $plot >= $req_qty_sn ? $req_qty_sn : $plot;
                    $hasil_plot = $plotting >= 0 ? $plotting : 0;
    
                    $total_history += ($hasil_plot / $req_qty_sn);
                }
    
                $persen_history = $jumlah_material == 0 ? 0 : round(($total_history / $jumlah_material) * 100, 1);
                $data_persen = [
                    'AUFNR' => $ongoing['AUFNR'],
                    'SN' => $ongoing['SN'],
                    'persen' => $persen_history
                ];
                $data_DB[] = $data_persen;
            }
            unset($new_history);
            unset($mat_history);
            unset($prevUsage);
            unset($data_persen);
            $pro = ProductionOrder::whereIn('STAT', $this->stat_history)->get();
            foreach ($pro as $prod) {
                foreach ($data_DB as $db) {
                    if (($prod['AUFNR'] == $db['AUFNR']) && ($prod['SERNR'] == $db['SN'])) {
                        $prod['persen'] = $db['persen'];
                        $prod['persen_gi'] = $db['persen'];
                        $prod->save();
                    }
                }
            }
            unset($pro);
            unset($data_DB);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
        }
    }

    public function updateDataPRO()
    {
        $now    = Carbon::now();

        $this->updatePRO();
        $this->apiPRO();
        $akhir = Carbon::now();

        echo $akhir;
        echo "<br>";
        echo $now;
    }

    public function updateDataMaterial()
    {
        $now    = Carbon::now();

        $this->apiMaterial();
        $this->Material();

        $akhir = Carbon::now();

        echo $akhir;
        echo "<br>";
        echo $now;
    }

    public function olahMaterial()
    {
        $now    = Carbon::now();

        $this->MaterialOlahData();
        $this->TemporaryStock();
        $this->Inventory();
        
        DB::connection('mysql7')->statement('CALL sp_update_ticket_pro');

        $akhir = Carbon::now();

        echo $akhir;
        echo "<br>";
        echo $now;
    }

    public function insertPercentage()
    {
        $now    = Carbon::now();

        $this->InsertPercentageOngoing();
        $this->InsertPercentageHistory();

        $akhir = Carbon::now();

        echo $akhir;
        echo "<br>";
        echo $now;
        
    }
}
