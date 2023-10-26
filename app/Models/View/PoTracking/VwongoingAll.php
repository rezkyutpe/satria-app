<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Vwongoingall extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_ongoingall';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
