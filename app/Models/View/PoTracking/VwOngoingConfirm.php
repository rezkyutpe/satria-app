<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwOngoingConfirm extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_confirmqty';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
