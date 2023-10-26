<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwHistoryall extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_historyall';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
