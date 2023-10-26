<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwLocalnewpo extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_newpolocal';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
