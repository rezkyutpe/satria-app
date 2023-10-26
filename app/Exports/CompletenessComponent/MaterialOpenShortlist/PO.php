<?php

namespace App\Exports\CompletenessComponent\MaterialOpenShortlist;

use App\Models\Table\CompletenessComponent\MaterialTemporary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PO implements FromCollection, WithHeadings, WithStrictNullComparison
{
    protected $kolom;
    protected $filter;
    protected $kolom1;
    protected $filter1;
    protected $date;

    function __construct($kolom, $filter, $date, $kolom1, $filter1) {
            $this->kolom    = $kolom;
            $this->filter   = $filter;
            $this->kolom1   = $kolom1;
            $this->filter1  = $filter1;
            $this->date     = $date;
    }

    public function headings(): array
    {
        return [
            "Material Number", 
            "Material Description", 
            "Base Unit",
            "Material Type", 
            "Material Group", 
            "Stock", 
            "Total Minus", 
            "on Order", 
            "Vendor Name", 
            "PO. No.", 
            'PO Item', 
            "Open Qty",
            "Delivery Date Agreed",
            "PO Rel Date",
            "Security Date"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function collection()
    {
        
        $data_material   = MaterialTemporary::Join('satria_potracking.vw_datapo_ccr', function($join)
        {
            $join->on('material_temporary.MATNR', '=', 'satria_potracking.vw_datapo_ccr.Material');
        })
        ->where('MINUS_PLOTTING', '<', 0)
        ->groupBy('MATNR', 'Number', 'ItemNumber')
        ->orderBy('MATNR', 'ASC')
        ->orderBy('ConfirmedDate', 'ASC')
        ->orderBy('Number', 'ASC')
        ->orderBy('ItemNumber', 'ASC');
        
        if ($this->kolom == null && $this->date == null) {
            $date       = Carbon::now();
            $end        = $date->modify('+14 days')->toDateString();
            $material   = $data_material
                ->select(
                    'MATNR as material_number',
                    'MAKTX as material_description',
                    'MEINS as base_unit',
                    'MTART as material_type',
                    'MATKL as material_group',
                    'STOCK as stock',
                    DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER <= '$end' GROUP BY MATNR) AS sum_kekurangan_stock"),
                    DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order"),
                    'Name',
                    'Number',
                    'ItemNumber',
                    'OpenQuantity',
                    'ConfirmedDate',
                    'ReleaseDate',
                    'SecurityDate'
                )
                ->where('BDTER', '<=', $end);
        } else {
            if ($this->kolom != null &&$this->filter != null) {
                $material = $data_material->whereIn($this->kolom,$this->filter);
            }
            if ($this->kolom1 != null && $this->filter1 != null) {
                $material = $data_material->whereIn($this->kolom1, $this->filter1);
            }

            if ($this->date != null) {
                $date         = explode(" - ", $this->date);
                $awal         = Carbon::createFromFormat('d/m/Y', trim($date[0]));
                $akhir        = Carbon::createFromFormat('d/m/Y', trim($date[1]));
                $start_date   = $awal->modify('+14 days')->toDateString();
                $end_date     = $akhir->modify('+14 days')->toDateString();


                $material = $data_material
                    ->select(
                        'MATNR as material_number',
                        'MAKTX as material_description',
                        'MEINS as base_unit',
                        'MTART as material_type',
                        'MATKL as material_group',
                        'STOCK as stock',
                        DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER between '$start_date' AND '$end_date' GROUP BY MATNR) AS sum_kekurangan_stock"),
                        DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order"),
                        'Name',
                        'Number',
                        'ItemNumber',
                        'OpenQuantity',
                        'ConfirmedDate',
                        'ReleaseDate',
                        'SecurityDate'
                    )
                    ->whereBetween('BDTER', [$start_date, $end_date]);
            } else {
                $material = $data_material
                    ->select(
                        'MATNR as material_number',
                        'MAKTX as material_description',
                        'MEINS as base_unit',
                        'MTART as material_type',
                        'MATKL as material_group',
                        'STOCK as stock',
                        DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR GROUP BY MATNR) AS sum_kekurangan_stock"),
                        DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as on_order"),
                        'Name',
                        'Number',
                        'ItemNumber',
                        'OpenQuantity',
                        'ConfirmedDate',
                        'ReleaseDate',
                        'SecurityDate'
                    );
            }
        }
        
        // foreach ($material->get()->toArray() as $item) {
        //     if ($item['on_order'] == null) {
        //         $item['on_order'] = 0;
        //     }
        //     if ($item['SUM_MINUS_PLOTTING'] == null) {
        //         $item['SUM_MINUS_PLOTTING'] = 0;
        //     }
        //     $data[] = [
        //         "MATNR"                 => $item['MATNR'],
        //         "MAKTX"                 => $item['MAKTX'],
        //         "MTART"                 => $item['MTART'],
        //         "MATKL"                 => $item['MATKL'],
        //         "STOCK"                 => $item['STOCK'],
        //         "MINUS_PLOTTING"        => $item['MINUS_PLOTTING'],
        //         "SUM_MINUS_PLOTTING"    => $item['SUM_MINUS_PLOTTING'],
        //         "onOrder"               => $item['onOrder'],
        //         "MEINS"                 => $item['MEINS'],
        //         "AUFNR"                 => $item['AUFNR'],
        //         "PLNBEZ"                => $item['PLNBEZ'],
        //         "DESC_PLNBEZ"           => $item['DESC_PLNBEZ'],
        //         "BDTER"                 => $item['BDTER']
        //     ];
        // }
        // dd($material->get()->toArray()); 
        $sql = $material->get();
        return collect($sql);
    }
}
