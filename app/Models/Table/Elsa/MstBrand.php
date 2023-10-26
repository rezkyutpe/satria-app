<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstBrand extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_brand';

  protected $fillable = [    
    'id', 'name', 'flag','created_by',  'created_at'
  ];
}
