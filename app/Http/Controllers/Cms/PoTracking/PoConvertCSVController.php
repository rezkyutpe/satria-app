<?php

namespace App\Http\Controllers\Cms\PoTracking;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\Table\PoTracking\Pdi;
use App\Models\Table\PoTracking\PdiHistory;
class PoConvertCSVController extends Controller
{


    public function insertCSV()
    {
        $now = Carbon::now();

        $dataProductionOrder    = [];

        $location   = public_path('potracking/test.csv');
        $worksheet  = fopen("{$location}", "r");

        if ($worksheet !== FALSE) {
            while (($worksheet_data = fgetcsv($worksheet, 0, ";")) !== FALSE) {
                $dataCSVProductionOrder[] = $worksheet_data;
            }
            fclose($worksheet);
        } else {
            echo 'File Production Order Not Found!';
        }

        dd($dataCSVProductionOrder);
        // Mengambil data PRO dari CSV
        // dd(count($dataCSVProductionOrder));

        foreach ($dataCSVProductionOrder as $dataPRO) {

            $GroupProduct   = NULL;
            $gltrp_api      = NULL;
            $strmp_api      = NULL;
            // bsart potype
            if ($dataPRO[0] == "#N/A" || $dataPRO[0] == '') {
                $dataPRO[0] = NULL;
            }
              // ebeln PO [Number]
            if ($dataPRO[1] == "#N/A" || $dataPRO[1] == '') {
                $dataPRO[1] = NULL;
            }
              // ebelp [ItemNumber]
            if ($dataPRO[2] == "#N/A" || $dataPRO[2] == '') {
                $dataPRO[2] = NULL;
            }
            // $t = (int)$dataPRO[2];
             // loekz Isclosed
            if ($dataPRO[3] == "#N/A" || $dataPRO[3] == '') {
                $dataPRO[3] = NULL;
            }
              // udate
            if ($dataPRO[4] == '#N/A' || $dataPRO[4] == '00.00.0000') {
                $dataPRO[4]  = NULL;
            } else {
                $dataPRO[4]  = Carbon::createFromFormat('d/m/Y', $dataPRO[4])->format('Y-m-d');
            }
              // aedat PO[Date]]
            if ($dataPRO[5] == '#N/A' || $dataPRO[5] == '00.00.0000') {
                $dataPRO[5]  = NULL;
            } else {
                $dataPRO[5]  = Carbon::createFromFormat('d/m/Y', $dataPRO[5])->format('Y-m-d');
            }
              // ernam PO [CreatedBy]
            if ($dataPRO[6] == "#N/A" || $dataPRO[5] == '') {
                $dataPRO[6] = NULL;
            }
              // name2 PO [PurchaseOrderCreator]
            if ($dataPRO[7] == "#N/A" || $dataPRO[6] == '') {
                $dataPRO[7] = NULL;
            }
              // menge [Quantity]
            if ($dataPRO[8] == "#N/A" || $dataPRO[7] == '') {
                $dataPRO[8] = NULL;
            }
              // lifnr Vendors [code]
            if ($dataPRO[9] == "#N/A" || $dataPRO[8] == '') {
                $dataPRO[9] = NULL;
            }
              // loekz PurchasingDocumentItem [ItemNumber]
            if ($dataPRO[10] == "#N/A" || $dataPRO[9] == '') {
                $dataPRO[10] = NULL;
            }
            if ($dataPRO[0] == "#N/A" || $dataPRO[10] == '') {
                $dataPRO[0] = NULL;
            }
            // PHAS1
            if ($dataPRO[19] == '#N/A' || $dataPRO[19] == '00.00.0000' || $dataPRO[19] == '') {
                $dataPRO[19]  = NULL;
            } else {
                $dataPRO[19]  = Carbon::createFromFormat('d/m/Y', $dataPRO[42])->format('Y-m-d');
            }


            $dataInsertPRO = [
                'AUFNR'         => $dataPRO[0],
                'AUART'         => $dataPRO[1],
                'AUTYP'         => $dataPRO[2],

            ];

            if (!in_array($dataInsertPRO, $dataProductionOrder)) {
                $dataProductionOrder[] = $dataInsertPRO;
            }
        }

        // Mengosongkan data dari DB
        Pdi::truncate();

        // Insert data ke DB
        $chunksPRO = array_chunk($dataProductionOrder, 1);
        foreach ($chunksPRO as $PROInsert) {
            Pdi::insert($PROInsert);
        }

        $akhir = Carbon::now();
        echo $akhir;
        echo "<br>";
        echo $now;
    }

}




