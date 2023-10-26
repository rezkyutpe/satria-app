<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class KonfHose extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_konfhose';

  protected $fillable = [    
    'id_khose', 'nama_khose','created_by',  'created_at'
  ];
}
