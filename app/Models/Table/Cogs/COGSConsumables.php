<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class COGSConsumables extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cogs_consumables';
  protected $fillable = [
        'ID', 
        'COGSID', 
        'Component', 
        'Description', 
        'Category', 
        'Price', 
        'Currency', 
        'Qty', 
        'Un', 
        'Tax', 
        'TotalPrice', 
        'LastTransaction', 
        'ManualAdjustment', 
        'FinalPrice', 
        'CreatedBy', 
        'UpdatedBy',
    ];
}
