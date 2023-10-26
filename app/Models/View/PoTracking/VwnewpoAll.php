<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwnewpoAll extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_newpoall';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
