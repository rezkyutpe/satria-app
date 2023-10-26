<?php

namespace App\Models\Table\Pajak;

use Illuminate\Database\Eloquent\Model;

class MstVendor extends Model
{

  protected $connection = 'mysql2';
  protected $table = 'vendor';

  protected $fillable = [
    'id', 'kode_vendor', 'title', 'nama_vendor', 'npwp', 'pic', 'alamat', 'kota','created_by','updated_by'
];
}
