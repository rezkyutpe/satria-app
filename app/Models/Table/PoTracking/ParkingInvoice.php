<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class ParkingInvoice extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'parkinginvoice';
  protected $primaryKey = 'ID';
  protected $fillable = [
    'Number','ItemNumber','Material','Description','VendorName','POCreator','Qty',
    'GRDate','InvoiceDate','InvoiceNumber','GrossAmount','PPN','PPH','AmountTotal',
    'ParkingNumber','Reference','DocumentNumber','DocumentNumberItem',
    'wi_tax_code','wi_tax_type','Price','Currency','TotalPrice',
    'RefDocumentNumber','VAT','UoM','CompanyCode','ValidateDocumentDate',
    'ReceiveDocumentDate','ApproveParkingDate','DocumentNumberSAP',
    'FiscalYear','Status','Remark','IsReject',
    'created_by','updated_by'
];
}
