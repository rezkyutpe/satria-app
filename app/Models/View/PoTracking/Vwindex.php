<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Vwindex extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_indexpo';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
