<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstRoom extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_room';

  protected $fillable = [    
    'id', 'name', 'location','flag','created_by',  'created_at'
  ];
}
