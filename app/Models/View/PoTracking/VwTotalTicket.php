<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwTotalTicket extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_totalticket';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
