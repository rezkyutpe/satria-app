<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwPo extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_po';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
