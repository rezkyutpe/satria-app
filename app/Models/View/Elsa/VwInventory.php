<?php

namespace App\Models\View\Elsa;

use Illuminate\Database\Eloquent\Model;

class VwInventory extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'vw_inventory';

  protected $fillable = [    
    'inventory_id', 'inventory_nama', 'inventory_qty', 'inventory_qty_min', 'inventory_brand_id', 'flag', 'inventory_category_id', 'inventory_dept', 'dept', 'created_by',  'created_at','inventory_satuan', 'brand_name', 'cat_name', 'dept_name'
  ];
}
