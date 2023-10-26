<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_aplikasi';

  protected $fillable = [    
    'id_app', 'nama_app', 'created_by',  'created_at'
  ];
}
