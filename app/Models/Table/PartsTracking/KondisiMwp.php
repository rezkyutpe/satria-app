<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class KondisiMwp extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_kondisi_mwp';

  protected $fillable = [    
    'id_mwp', 'jhose', 'diameter', 'mwp', 'mbp',  'created_by',  'created_at', 

  ];
}
