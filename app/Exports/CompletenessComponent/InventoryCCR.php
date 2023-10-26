<?php

namespace App\Exports\CompletenessComponent;

use App\Models\Table\CompletenessComponent\Inventory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InventoryCCR implements FromCollection, WithHeadings,WithStrictNullComparison
{
    public function headings(): array
    {
        return ["Material Number", "Description", "Material Type", "Material Group", "Base Unit", "Plant", 'Storage Location', "Stock", "Allocated Stock", "Free Stock", "in QC", "on Order"];
    }
    public function collection()
    {
        $sql = Inventory::leftJoin('satria_potracking.vw_sum_data_po_ccr', 'inventory.material_number', '=', 'satria_potracking.vw_sum_data_po_ccr.material')
            ->select('inventory.material_number', 'material_description', 'material_type', 'material_group', 'base_unit', 'plant', 'storage_location', 'stock', 'alokasi_stock', 'free_stock', 'in_qc', DB::raw('IFNULL(total_open_qty, 0)'))
            ->distinct()
            ->where('storage_location', '<', 2000)
            ->orderBy('material_number', 'ASC')
            ->orderBy('stock', 'DESC')
            ->get();
        return collect($sql);
    }
}
