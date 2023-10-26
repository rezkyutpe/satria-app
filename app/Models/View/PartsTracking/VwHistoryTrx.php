<?php

namespace App\Models\View\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class VwHistoryTrx extends Model
{

  protected $connection = 'mysql5';
  protected $table = 'vw_history_trx';

  protected $fillable = [
    'id', 'transaksi', 'lokasi', 'kondisi', 'created_at', 'updated_at', 'created_by', 'updated_by', 'id_lokasi', 'nama_lokasi'
];
}
