<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwPosubcont extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_posubcont';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
