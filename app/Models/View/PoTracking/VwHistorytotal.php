<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwHistorytotal extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_historytotal';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
