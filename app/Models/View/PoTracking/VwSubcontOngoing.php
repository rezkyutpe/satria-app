<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwSubcontOngoing extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_ongoingposubcont';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
