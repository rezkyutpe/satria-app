<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class InventoryCat extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'inventory_category';

  protected $fillable = [    
    'id', 'name', 'flag', 'dept',  'created_by',  'created_at'
  ];
}
