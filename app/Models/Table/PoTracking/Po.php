<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Po extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'po';
  protected $primaryKey = 'ID';

  protected $fillable = [
    'Number','Type','Date','ReleaseDate','PRNumber','PRCreateDate','PRReleaseDate',
    'ProgressDay','VendorCode','KompoCategoryID','EstimateDeliveryFromSubcont1Date',
    'EstimateDeliveryFromSubcont1Quantity','EstimateDeliveryFromSubcont2Date',
    'EstimateDeliveryFromSubcont2Quantity','EstimateDeliveryFromSubcont3Date',
    'EstimateDeliveryFromSubcont3Quantity','Information','Weight','ProductGroup',
    'CanHavePI','DocumentPI','IsApprovedPI','IsApprovedByVendor','IsApprovedByUser',
    'DatePaymentReceived','DocumentInvoice','DocumentPostedInvoice','DatePostedInvoice',
    'NumberPostedInvoice','PurchaseOrderCreator','Status','Reference',
    'TermPayment','ApproveBy','ApproveName','VendorAddress','VendorCity','VendorZip',
    'VendorPhone','VendorFax','BillOfLading','ShippingMethod','CompanyCode',
    'created_at','CreatedBy','updated_at','LastModifiedBy','LastModified'
];
}
