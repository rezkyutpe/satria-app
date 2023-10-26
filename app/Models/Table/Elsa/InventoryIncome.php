<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class InventoryIncome extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'inventory_income';

  protected $fillable = [    
    'id', 'qty', 'price', 'note', 'inventory_id', 'vendor_id','dept','created_by',  'created_at'
  ];
}
