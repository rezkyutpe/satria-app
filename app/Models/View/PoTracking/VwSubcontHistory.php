<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwSubcontHistory extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_historysubcont';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
