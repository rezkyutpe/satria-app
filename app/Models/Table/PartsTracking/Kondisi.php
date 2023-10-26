<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class Kondisi extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_kondisi';

  protected $fillable = [    
    'id_kondisi', 'kondisi'
  ];
}
