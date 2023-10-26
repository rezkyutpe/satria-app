<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class PdiHistory extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'purchasingdocumentitemhistory';
  protected $primaryKey = 'ID';
  
  public $timestamps = false;

  protected $fillable = [
    'POID','PRNumber','PRItem','PRCreateDate','PRReleaseDate','DeliveryDate','ParentID','Number','ItemNumber',
    'Material','MaterialVendor','Description','NetPrice','Currency','Quantity','GoodsReceiptQuantity',
    'GoodsReceiptDate','GoodsReceiptDate2','GoodsReceiptTime2','PayTerms','PurchasingGroup','POCategory',
    'MovementType','DocumentNumber','DocumentNumberItem','InboundNumber','ConfirmedDate','ConfirmedQuantity',
    'ConfirmedItem','ConfirmReceivedPaymentDate','InvoiceDocument','InvoiceMethod','TaxInvoice','ProformaInvoiceDocument',
    'ApproveProformaInvoiceDocument','NetValue','WorkTime','LeadTimeItem','OpenQuantity','OpenQuantityIR',
    'ATA','ActiveStage','IsClosed','PB','Setting','Fullweld','Primer','PBActualDate','PBLateReasonID',
    'SettingActualDate','SettingLateReasonID','FullweldActualDate','FullweldLateReasonID','PrimerActualDate',
    'RefDocumentNumber','IRDebetCredit','VAT','Plant','SLoc','UoM','DocumentNumberRef','DocumentNumberItemRef',
    'PrimerLateReasonID','created_at','CreatedBy','updated_at','LastModifiedBy'
  ];
}
