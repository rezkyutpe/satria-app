<?php

namespace App\Exports\CompletenessComponent\MaterialOpenShortlist;

use App\Models\Table\CompletenessComponent\MaterialTemporary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PRO implements FromCollection, WithHeadings, WithStrictNullComparison
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
            "Production Order", 
            "Product Number", 
            'Product Description', 
            "Req. Date",
            "Stock", 
            "Minus", 
            "Total Minus", 
            "on Order", 
            "Vendor Name", 
            "PO No.", 
            "Item Number", 
            "Open Quantity", 
            "Delivery Date Agreed", 
            "Release Date", 
            "Security Date" 
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function collection()
    {
        $date       = Carbon::now();
        $end        = $date->modify('+14 days')->toDateString();
     
        $data_material   = MaterialTemporary::LeftJoin('satria_potracking.vw_datapo_ccr', function($join)
            {
                $join->on('material_temporary.MATNR', '=', 'satria_potracking.vw_datapo_ccr.Material');
            })
            ->where('MINUS_PLOTTING', '<', 0)
            ->groupBy('MATNR', 'AUFNR', 'Number', 'ItemNumber')
            ->orderBy('BDTER', 'ASC')
            ->orderBy('MATNR', 'ASC')
            ->orderBy('PLNBEZ', 'ASC')
            ->orderBy('AUFNR', 'ASC')
            ->orderBy('MINUS_PLOTTING', 'ASC');
                
        if ($this->date == null && $this->kolom == null) {    
            $material_date = $data_material
                ->select(
                    'MATNR',
                    'MAKTX',
                    'MEINS',
                    'MTART',
                    'MATKL',
                    'AUFNR',
                    'PLNBEZ',
                    'DESC_PLNBEZ',
                    'BDTER',
                    'STOCK',
                    'MINUS_PLOTTING',
                    DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER <= '$end' GROUP BY MATNR) AS SUM_MINUS_PLOTTING"),
                    DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as onOrder"),
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
            if ($this->date == null) {
                $material_date = $data_material
                    ->select(
                        'MATNR',
                        'MAKTX',
                        'MEINS',
                        'MTART',
                        'MATKL',
                        'AUFNR',
                        'PLNBEZ',
                        'DESC_PLNBEZ',
                        'BDTER',
                        'STOCK',
                        'MINUS_PLOTTING',
                        DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR GROUP BY MATNR) AS SUM_MINUS_PLOTTING"),
                        DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as onOrder"),
                        'Name',
                        'Number',
                        'ItemNumber',
                        'OpenQuantity',
                        'ConfirmedDate',
                        'ReleaseDate',
                        'SecurityDate'
                    );
            } else {
                $tgl         = explode(" - ", $this->date);
                
                $awal         = Carbon::createFromFormat('d/m/Y', trim($tgl[0]));
                $akhir        = Carbon::createFromFormat('d/m/Y', trim($tgl[1]));
                
                $start_date   = $awal->modify('+14 days')->toDateString();
                $end_date     = $akhir->modify('+14 days')->toDateString();
    
                $material_date = $data_material
                    ->select(
                        'MATNR',
                        'MAKTX',
                        'MEINS',
                        'MTART',
                        'MATKL',
                        'AUFNR',
                        'PLNBEZ',
                        'DESC_PLNBEZ',
                        'BDTER',
                        'STOCK',
                        'MINUS_PLOTTING',
                        DB::raw("(SELECT SUM(MINUS_PLOTTING) FROM material_temporary A WHERE A.MATNR = material_temporary.MATNR AND BDTER between '$start_date' AND '$end_date' GROUP BY MATNR) AS SUM_MINUS_PLOTTING"),
                        DB::raw("(SELECT SUM(OpenQuantity) FROM satria_potracking.vw_datapo_ccr b WHERE b.Material = material_temporary.MATNR GROUP BY Material) as onOrder"),
                        'Name',
                        'Number',
                        'ItemNumber',
                        'OpenQuantity',
                        'ConfirmedDate',
                        'ReleaseDate',
                        'SecurityDate'
                    )
                    ->whereBetween('BDTER', [$start_date, $end_date]);
            }
            
        }

        $material = [];

        if ($this->kolom == null) {
            $material = $material_date->get()->toArray();
        } else {
            if ($this->kolom != null && $this->filter != null) {
                $material_filter = $material_date->whereIn($this->kolom, $this->filter);
            }
            if ($this->kolom1 != null && $this->filter1 != null) {
                $material_filter = $material_date->whereIn($this->kolom1, $this->filter1);
            }
            $material = $material_filter->get()->toArray();
        }
        
        
        foreach ($material as $item) {
            if ($item['onOrder'] == null) {
                $item['onOrder'] = 0;
            }
            if ($item['SUM_MINUS_PLOTTING'] == null) {
                $item['SUM_MINUS_PLOTTING'] = 0;
            }
            if ($item['Name'] == null) {
                $item['Name'] = '-';
            }
            if ($item['Number'] == null) {
                $item['Number'] = '-';
            }
            if ($item['Number'] == null) {
                $item['Number'] = '-';
            }
            if ($item['ItemNumber'] == null) {
                $item['ItemNumber'] = '-';
            }
            if ($item['OpenQuantity'] == null) {
                $item['OpenQuantity'] = '-';
            }
            if ($item['ConfirmedDate'] == null) {
                $item['ConfirmedDate'] = '-';
            }
            if ($item['ReleaseDate'] == null) {
                $item['ReleaseDate'] = '-';
            }
            if ($item['SecurityDate'] == null) {
                $item['SecurityDate'] = '-';
            }
            $data[] = [
                "MATNR"                 => $item['MATNR'],
                "MAKTX"                 => $item['MAKTX'],
                "MEINS"                 => $item['MEINS'],
                "MTART"                 => $item['MTART'],
                "MATKL"                 => $item['MATKL'],
                "AUFNR"                 => $item['AUFNR'],
                "PLNBEZ"                => $item['PLNBEZ'],
                "DESC_PLNBEZ"           => $item['DESC_PLNBEZ'],
                "BDTER"                 => $item['BDTER'],
                "STOCK"                 => $item['STOCK'],
                "MINUS_PLOTTING"        => $item['MINUS_PLOTTING'],
                "SUM_MINUS_PLOTTING"    => $item['SUM_MINUS_PLOTTING'],
                "onOrder"               => $item['onOrder'],
                "Vendor"                => $item['Name'],
                "PoNumber"              => $item['Number'],
                "ItemNumber"            => $item['ItemNumber'],
                "OpenQuantity"          => $item['OpenQuantity'],
                "ConfirmedDate"         => $item['ConfirmedDate'],
                "ReleaseDate"           => $item['ReleaseDate'],
                "SecurityDate"          => $item['SecurityDate']
            ];
        }
        // dd($material->get()->toArray()); 
        $sql = $data;
        return collect($sql);
    }
}
