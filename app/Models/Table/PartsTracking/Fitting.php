<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class Fitting extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_fitting';

  protected $fillable = [    
    'id_fitting', 'nama_fitting', 'no_urut', 'size', 'created_by',  'created_at'
  ];
}
