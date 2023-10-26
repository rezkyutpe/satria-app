<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
  protected $connection = 'mysql1';

  protected $table = 't_detail_komponen';

  protected $fillable = [
      'pn_patria', 'description', 'pn_vendor', 'price', 'qty_order','qty_supply','qty_rcv', 'qty_use', 'status','ket','po','package'
  ];
}
