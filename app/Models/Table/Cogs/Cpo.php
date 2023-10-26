<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class Cpo extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cpo';
  protected $fillable = [
      'OrderID', 
      'SAPID', 
      'POUT', 
      'PurchaseOrder', 
      'PODate', 
      'CreatedOn', 
      'Name', 
      'Owner', 
      'MarketingRepresentative', 
      'Customer', 
      'TotalAmount', 
      'ReadyToExport',
    ];
}
