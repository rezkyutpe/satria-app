<?php

namespace App\Models\View\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class VwKomponenTrx extends Model
{

  protected $connection = 'mysql1';
  protected $table = 'vw_komponen_transaksi';

  protected $fillable = [
    'id', 'pn_patria', 'description', 'pn_vendor', 'price', 'qty_order', 'qty_supply', 'qty_rcv', 'qty_use', 'status', 'ket', 'po', 'package', 'created_at', 'updated_at', 'is_pn_vendor', 'is_price', 'total'
  ];
}
