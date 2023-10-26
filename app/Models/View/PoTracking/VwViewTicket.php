<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwViewTicket extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_qtytiket';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
