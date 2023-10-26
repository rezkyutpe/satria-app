<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwSubcontNewpo extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_newposubcont';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
