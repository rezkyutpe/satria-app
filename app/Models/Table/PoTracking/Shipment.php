<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'shipment';

  protected $fillable = [
      'ID', 'PurchasingDocumentItemID', 'BookingDate', 'ATDDate', 'CopyBLDate', 'CopyBLDocument', 'PackingListDocument', 'InvoiceDocument', 'AWB', 'CourierName', 'ETADate', 'ATADate', 'Created', 'CreatedBy', 'LastModified', 'LastModifiedBy'
  ];
}
