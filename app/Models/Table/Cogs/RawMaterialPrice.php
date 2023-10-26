<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class RawMaterialPrice extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'raw_material_price';
  protected $fillable = [
        'ID',
        'Category',
        'Un',
        'PriceExmill', 
        'CurrencyExmill', 
        'PriceExstock', 
        'CurrencyExstock',
        'CreatedBy',
        'UpdatedBy',
    ];
}
