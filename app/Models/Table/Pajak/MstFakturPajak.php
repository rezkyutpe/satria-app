<?php

namespace App\Models\Table\Pajak;

use Illuminate\Database\Eloquent\Model;

class MstFakturPajak extends Model
{

  protected $connection = 'mysql2';
  protected $table = 'master_fakturpajak';

  protected $fillable = [
    'id', 'url_scan', 'kdjenistransaksi', 'fgpengganti', 'nomorfaktur', 'tanggalfaktur', 'npwppenjual', 'namapenjual', 'alamatpenjual', 'npwplawantransaksi', 'namalawantransaksi', 'alamatlawantransaksi', 'jumlahdpp', 'jumlahppn', 'jumlahppnbm', 'statusapproval', 'statusfaktur', 'referensi', 'masa_pajak', 'date_scan', 'tahun_pajak', 'jenis_faktur', 'is_creditable', 'export'
  ];
}
