<?php

namespace App\Exports\POTracking;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\View\PoTracking\VwLeadtimeDeliveryGR;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Support\Facades\Auth;

class DsGrDownload implements FromCollection, WithHeadings, WithStrictNullComparison

{

    protected $month;
    protected $years;
    protected $vendor;
    protected $vendortype;
    protected $statusdelv;
    protected $ponumber;

    function __construct($month, $years, $vendor, $vendortype, $statusdelv, $ponumber)
    {
        $this->month    = $month;
        $this->years   = $years;
        $this->vendor    = $vendor;
        $this->vendortype    = $vendortype;
        $this->statusdelv   = $statusdelv;
        $this->ponumber     = $ponumber;
    }

    public function headings(): array
    {
        return [

            "Number", "Item Number", "Material", "Description", "Quantity", "Delivery Date", "PO Date", "Release Date", "Type", "PO Creator", "Name", "Vendor Code", "Vendor Type",
            "GR Date", "GR Quantity", "Movement Type", "Status Movement", "Status Receive", "Status Delivery"
        ];
    }

    public function collection()
    {

        $date = 'vw_leadtime_delivery_gr.DeliveryDate';


        if ($this->statusdelv == "Fullfill-Ontime") {
            $StatusReceive = 'Fullfill';
            $StatusDelivery = 'Ontime';
        } elseif ($this->statusdelv == "Fullfill-Early") {
            $StatusReceive = 'Fullfill';
            $StatusDelivery = 'Early';
        } elseif ($this->statusdelv == "Fullfill-Late") {
            $StatusReceive = 'Fullfill';
            $StatusDelivery = 'Late';
        } elseif ($this->statusdelv == "Partial-Early") {
            $StatusReceive = 'Partial';
            $StatusDelivery = 'Early';
        } elseif ($this->statusdelv == "Partial-Late") {
            $StatusReceive = 'Partial';
            $StatusDelivery = 'Late';
        }
        $getData =  VwLeadtimeDeliveryGR::where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->first();
        $Data = VwLeadtimeDeliveryGR::leftJoin('po', 'po.Number', '=', 'vw_leadtime_delivery_gr.Number')->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
            ->select(
                'vw_leadtime_delivery_gr.Number',
                'vw_leadtime_delivery_gr.ItemNumber',
                'vw_leadtime_delivery_gr.Material',
                'vw_leadtime_delivery_gr.Description',
                'vw_leadtime_delivery_gr.Quantity',
                'vw_leadtime_delivery_gr.DeliveryDate',
                'po.Date',
                'po.ReleaseDate',
                'po.Type',
                'po.PurchaseOrderCreator',
                'vw_leadtime_delivery_gr.VendorName',
                'vw_leadtime_delivery_gr.VendorCode',
                'vw_leadtime_delivery_gr.VendorType',
                'vw_leadtime_delivery_gr.GoodsReceiptDate',
                'vw_leadtime_delivery_gr.GoodsReceiptQuantity',
                'vw_leadtime_delivery_gr.MovementType',
                'vw_leadtime_delivery_gr.StatusMovement',
                'vw_leadtime_delivery_gr.StatusReceive',
                'vw_leadtime_delivery_gr.StatusDelivery'
            );

        if (isset($getData)) {
            if ($this->month != null  && $this->years != null && $this->statusdelv && $this->ponumber != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereIn('vw_leadtime_delivery_gr.Number', $this->ponumber)->whereMonth($date, $this->month)->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->get();
            } elseif ($this->month != null  && $this->years != null && $this->statusdelv != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereMonth($date, $this->month)->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->get();
            } elseif ($this->month != null && $this->ponumber != null && $this->years != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereMonth($date, $this->month)->whereYear($date, $this->years)->whereIn('vw_leadtime_delivery_gr.Number', $this->ponumber)->get();
            } elseif ($this->month != null && $this->years != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereMonth($date, $this->month)->whereYear($date, $this->years)->get();
            } elseif ($this->years != null && $this->ponumber != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereIn('vw_leadtime_delivery_gr.Number', $this->ponumber)->whereYear($date, $this->years)->get();
            } elseif ($this->years != null && $this->statusdelv != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->whereYear($date, $this->years)->get();
            } elseif ($this->ponumber != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereIn('vw_leadtime_delivery_gr.Number', $this->ponumber)->get();
            } elseif ($this->years != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->whereYear($date, $this->years)->get();
            } elseif ($this->statusdelv != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->get();
            } else {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorCode', Auth::user()->email)
                    ->get();
            }
        } else {
            if ($this->month != null  && $this->years != null && $this->statusdelv != null && $this->vendor && $this->vendortype != null && $this->ponumber != null) {
                $getData =  $Data->whereIn('vw_leadtime_delivery_gr.Number', $this->ponumber)->whereMonth($date, $this->month)->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->whereIn('vw_leadtime_delivery_gr.VendorName', $this->vendor)->where('vw_leadtime_delivery_gr.VendorType', $this->vendortype)->get();
            } elseif ($this->month != null && $this->years != null && $this->statusdelv != null && $this->vendortype != null) {
                $getData =  $Data->whereMonth($date, $this->month)->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->where('vw_leadtime_delivery_gr.VendorType', $this->vendortype)->get();
            } elseif ($this->month != null && $this->years != null && $this->statusdelv != null) {
                $getData =  $Data->whereMonth($date, $this->month)->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->get();
            } elseif ($this->month != null && $this->years != null && $this->vendortype != null) {
                $getData =  $Data->whereMonth($date, $this->month)->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.VendorType', $this->vendortype)->get();
            } elseif ($this->years != null && $this->statusdelv != null) {
                $getData =  $Data->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->get();
            } elseif ($this->years != null && $this->vendortype != null) {
                $getData =  $Data->whereYear($date, $this->years)->where('vw_leadtime_delivery_gr.VendorType', $this->vendortype)->get();
            } elseif ($this->statusdelv != null && $this->vendortype != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->where('vw_leadtime_delivery_gr.VendorType', $this->vendortype)->get();
            } elseif ($this->statusdelv != null && $this->vendor != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->whereIn('vw_leadtime_delivery_gr.VendorName', $this->vendor)->get();
            } elseif ($this->month != null  && $this->years != null) {
                $getData =  $Data->whereMonth($date, $this->month)->whereYear($date, $this->years)->get();
            } elseif ($this->years != null) {
                $getData =  $Data->whereYear($date, $this->years)->get();
            } elseif ($this->ponumber != null) {
                $getData =  $Data->whereIn('vw_leadtime_delivery_gr.Number', $this->ponumber)->get();
            } elseif ($this->statusdelv != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.StatusReceive', $StatusReceive)->where('vw_leadtime_delivery_gr.StatusDelivery', $StatusDelivery)->whereYear($date, $this->years)->get();
            } elseif ($this->vendor != null) {
                $getData =  $Data->whereIn('vw_leadtime_delivery_gr.VendorName', $this->vendor)->get();
            } elseif ($this->vendortype != null) {
                $getData =  $Data->where('vw_leadtime_delivery_gr.VendorType', $this->vendortype)->get();
            } else {
                $getData =  $Data
                    ->get();
            }
        }
        $sql = $getData;
        return collect($sql);
    }
}
