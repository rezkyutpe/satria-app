<?php

namespace App\Models\Table\Pajak;

use Illuminate\Database\Eloquent\Model;

class MstDetailPajak extends Model
{

  protected $connection = 'mysql2';
  protected $table = 'detail_transaksi';

  protected $fillable = [
    'id', 'nomorfaktur', 'nama', 'hargasatuan', 'jumlahbarang', 'hargatotal', 'diskon', 'dpp', 'ppn', 'tarifppnbm', 'ppnbm', 'created_at', 'updated_at'
  ];
}
