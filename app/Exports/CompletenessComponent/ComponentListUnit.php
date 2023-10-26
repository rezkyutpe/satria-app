<?php

namespace App\Exports\CompletenessComponent;

use App\Models\Table\CompletenessComponent\MaterialHasilOlah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ComponentListUnit implements FromCollection, WithHeadings, WithStrictNullComparison
{
    protected $kolom;
    protected $hasil;

    function __construct($kolom, $hasil) {
            $this->kolom    = $kolom;
            $this->hasil    = $hasil;
    }

    public function headings(): array
    {
        return ["Product Number", "Product Description", "Group Product", "Material Number", "Material Description", "Material Type", 'Material Group'];
    }

    public function collection()
    {
        $data_list = MaterialHasilOlah::select('PLNBEZ', 'DESC_PLNBEZ', 'GroupProduct', 'MATNR', 'MAKTX', 'MTART', 'MATKL')->distinct()->orderBy('MATNR', 'ASC')->orderBy('GroupProduct', 'ASC');
        if ($this->hasil != null) {
            $db         = $data_list->whereIn($this->kolom, $this->hasil);
        } else {
            $db         = $data_list;
        }
        
        $sql = $db->get();
        return collect($sql);
    }
}
