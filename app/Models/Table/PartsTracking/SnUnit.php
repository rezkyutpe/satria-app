<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class SnUnit extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_snunit';

  protected $fillable = [    
    'id_unit', 'sn_unit','created_by',  'created_at'
  ];
}
