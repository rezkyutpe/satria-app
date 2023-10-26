<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class Diameter extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_diameter';

  protected $fillable = [    
    'id_diameter', 'ukuran_diameter', 'no_urut', 'max_wp','created_by',  'created_at'
  ];
}
