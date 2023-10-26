<?php

namespace App\Models\View\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VwImportnewpo extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'vw_newpoimport';

  protected $fillable = [
    'VendorCode', 'Material', 'Description'
  ];
}
