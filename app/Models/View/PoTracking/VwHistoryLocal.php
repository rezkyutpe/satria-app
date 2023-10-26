<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwHistoryLocal extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_historylocal';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
