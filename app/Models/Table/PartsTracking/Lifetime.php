<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class Lifetime extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_lifetime';

  protected $fillable = [    
    'id_lifetime', 'ket', 'jml','created_by',  'created_at'
  ];
}
