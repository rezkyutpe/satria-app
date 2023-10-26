<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class JenisHose extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_jenishose';

  protected $fillable = [    
    'id_jhose', 'nama_hose',  'created_by',  'created_at'
  ];
}
