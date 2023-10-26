<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwOngoinglocal extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_ongoinglocal';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
