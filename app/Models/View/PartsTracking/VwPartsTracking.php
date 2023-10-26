<?php

namespace App\Models\View\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class VwPartsTracking extends Model
{

  protected $connection = 'mysql5';
  protected $table = 'vw_parts_transaction';

  protected $fillable = [
    'id', 'id_transaksi', 'tgl_transaksi', 'status', 'hose_transaksi', 'konf_transaksi', 'diameter', 'panjang', 'fitting1', 'ukuran1', 'fitting2', 'ukuran2', 'sn_unit', 'aplikasi', 'customer', 'pn_assy', 'lokasi', 'kondisi_transaksi', 'lifetime', 'tgl_lifetime', 'mwp', 'mbp'
];
}
