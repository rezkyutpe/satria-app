<?php

namespace App\Exports;

use App\Models\View\PoNonSAP\VwPoPro;
use App\Models\View\PoNonSAP\VwKomponenPro;
use App\Models\View\PoNonSAP\VwKomponenTrx;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class PickingExport implements FromView,WithColumnFormatting
{
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function view(): View
    {
        
        $po = VwPoPro::where('nopo',  $this->id)->first();
        $komponen = VwKomponenTrx::where('po', $this->id)->get();
        return view('po-non-sap/po-transaksi/export', [
            'po' => $po,
            'komponen' => $komponen
        ]);
      
    }
}