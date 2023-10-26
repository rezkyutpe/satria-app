<?php

namespace App\Models\View\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class VwKomponenPro extends Model
{

  protected $connection = 'mysql1';
  protected $table = 'vw_komponen_pro';

  protected $fillable = [
    'id', 'pn_patria', 'description', 'pn_vendor', 'qty_order', 'qty_supply', 'qty_use', 'status', 'ket', 'po', 'package', 'pro', 'pn', 'product', 'qty', 'cust'
  ];
}
