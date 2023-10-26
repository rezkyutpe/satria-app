<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class InventoryRequestOut extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'inventory_request_out';

  protected $fillable = [    
    'id','pr_id', 'id_inventory', 'user', 'text', 'qty', 'flag', 'created_by', 'created_at'
  ];
}
