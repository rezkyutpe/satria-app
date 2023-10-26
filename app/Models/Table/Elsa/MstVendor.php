<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstVendor extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_vendor';

  protected $fillable = [    
    'code', 'id', 'name', 'flag', 'created_by',  'created_at'
  ];
}
