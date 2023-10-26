<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class Pir extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'pir';
  protected $fillable = [
      'PurchasingInfoRecord',
      'PIRDate',
      'MaterialDescription',
      'Vendor',
      'VendorName',
      'UoM',
      'NetPrice',
      'Currency',
      'Description'
    ];
}
