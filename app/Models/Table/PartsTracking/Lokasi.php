<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_lokasi';

  protected $fillable = [    
    'id_lokasi', 'nama_lokasi','created_by',  'created_at'
  ];
}
