<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'product_category';
  protected $fillable = [
      'ID',
      'CategoryName',
      'CreatedBy',
      'UpdatedBy',
    ];
}
