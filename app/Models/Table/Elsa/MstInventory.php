<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstInventory extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_inventory';

  protected $fillable = [    
    'inventory_id', 'inventory_nama', 'inventory_group', 'inventory_file', 'inventory_qty', 'inventory_qty_min', 'inventory_brand_id', 'flag', 'inventory_category_id', 'inventory_dept','inventory_satuan', 'dept', 'created_by',  'created_at'
  ];
}
