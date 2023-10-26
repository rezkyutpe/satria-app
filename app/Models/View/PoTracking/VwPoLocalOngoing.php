<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwPoLocalOngoing extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_ongoing';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
