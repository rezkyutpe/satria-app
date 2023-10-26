<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstLocation extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_location';

  protected $fillable = [    
    'id', 'name', 'flag','created_by',  'created_at'
  ];
}
