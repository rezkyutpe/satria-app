<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class PartsTrx extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_transaksi';

  protected $fillable = [
    'id', 'id_transaksi', 'tgl_transaksi', 'hose_transaksi', 'konf_transaksi', 'diameter', 'panjang', 'fitting1', 'ukuran1', 'fitting2', 'ukuran2', 'sn_unit', 'aplikasi', 'customer', 'pn_assy', 'lokasi', 'kondisi_transaksi', 'mwp', 'lifetime', 'tgl_lifetime', 'status', 'created_by', 'created_at'
  ];
}
