<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'inventory_history';

  protected $fillable = [    
    'id','id_inventory', 'cat', 'text', 'qty', 'created_by', 'created_at'
  ];
}
