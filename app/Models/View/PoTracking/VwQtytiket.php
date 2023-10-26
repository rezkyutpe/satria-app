<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwQtytiket extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_ticketdelivery';

  protected $fillable = [
    'POID', 'qty'
  ];
}
