<?php

namespace App\Models\View\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class VwPoPro extends Model
{

  protected $connection = 'mysql1';
  protected $table = 'vw_po_pro';

  protected $fillable = [
    'nopo', 'pro', 'created_at',  'updated_at','created_by',  'updated_by','ttd1','ttd2','flag','no_pro', 'pn', 'product',  'qty','cust'
  ];
}
