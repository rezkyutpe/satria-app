<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsKlasifikasi extends Model
{

  protected $table = 'ms_klasifikasi';

  protected $fillable = [
    'id', 'name', 'created_by'
  ];
}
