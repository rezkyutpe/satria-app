<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwDataVendor extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_data_vendor';

  protected $fillable = [
    'VendorCode', 'VendorName', 'VendorCode_new','status'
  ];

  
}