<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstSla extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_sla';

  protected $fillable = [    
    'id', 'name','dept', 'resolution_time','created_by',  'created_at'
  ];
}
