<?php

namespace App\Exports\CompletenessComponent;

use App\Models\View\CompletenessComponent\VwReqDateComponent;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ReqDateComponent implements FromCollection, WithHeadings, WithStrictNullComparison
{
    protected $date_request;

    function __construct($date_request) {
        $this->date_request    = $date_request;
    }

    public function headings(): array
    {
        return [
            "Reservation Number", 
            "Product Number", 
            "Production Order", 
            "Serial Number", 
            "Material Number", 
            "Material Description", 
            "Base Unit", 
            "Material Type", 
            "Material Group", 
            "Req. Date", 
            "Req. Component", 
            "Good Issue", 
            "Allocated Stock", 
            "Short. Qty"
        ];
    }

    public function collection()
    {
        if ($this->date_request != null) {
            $tanggal = htmlspecialchars($this->date_request);
            $date  = explode(" - ", $tanggal);
            $now   = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
            $end   = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
        }else {
            $date        = Carbon::now();
            $now      = $date->toDateString();
            $end      = $date->modify('+14 days')->toDateString();
        }
        $db = VwReqDateComponent::
            select('reservation_number', 'product_number', 'production_order', 'serial_number', 'material_number', 'material_description', 'base_unit', 'material_type', 'material_group', 'requirement_date', 'requirement_quantity', 'good_issue', 'alokasi_stock', 'kekurangan_stock')
            ->whereBetween('requirement_date', [$now, $end])
            ->orderBy('requirement_date', 'ASC')
            ->orderBy('kekurangan_stock', 'ASC')
            ->orderBy('reservation_number', 'ASC');
        
                
        $sql = $db->get();
        return collect($sql);
    }
}