<?php

namespace App\Exports\POTracking;

use App\Models\View\PoTracking\VwDataVendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\View\PoTracking\VwongoingAll;
use App\Models\View\PoTracking\VwnewpoAll;
use App\Models\View\PoTracking\VwHistoryall;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\PoTracking\VwVendorFavorite;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class VnDataDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{
    public function headings(): array
    {
        return [
            "ID","Vendor","Email","Country Code","Postal Code","Address","Vendor Code","Vendor Code New","Phone","Vendor Type","Status"
            ];
    }

    public function collection()
    {

        $getData = VwDataVendor::get();
        $sql = $getData;
        return collect($sql);
    }
}
