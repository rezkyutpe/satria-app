<?php

namespace App\Exports\CompletenessComponent\Productionorder;

use App\Models\View\CompletenessComponent\VwProWithSN;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GroupProduct implements FromCollection, WithHeadings, WithStrictNullComparison
{
    protected $group_product;
    protected $stat_ongoing = ['CRTD', 'REL', 'DLV', 'PDLV'];

    function __construct($group_product) {
            $this->group_product    = $group_product;
    }

    public function headings(): array
    {
        return [
            "PRO Creator", 
            "Production Order", 
            "Serial Number", 
            "Product Number", 
            "Description", 
            "Product Group", 
            "Total Order", 
            "Status", 
            "Status Date", 
            "Create Date", 
            "Release Date", 
            "Start Date", 
            "Finish Date", 
            "Good Issue (%)",
            "Component Allocated (%)"
        ];
    }

    public function collection()
    {
        $sql = VwProWithSN::select(
                    'nama_creator', 
                    'production_order', 
                    'serial_number', 
                    'product_number', 
                    'product_description', 
                    'group_product', 
                    'quantity', 
                    'status', 
                    'date_status_created', 
                    'create_date_pro', 
                    'act_release', 
                    'sch_start_date', 
                    'sch_finish_date', 
                    'persen_gi',
                    'persen'
                )->where('group_product', $this->group_product)
                ->whereIn('status', $this->stat_ongoing)
                ->get();
        return collect($sql);
    }
}
