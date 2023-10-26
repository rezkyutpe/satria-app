<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class COGSOthers extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cogs_others';
  protected $fillable = [
        'ID', 
        'COGSID', 
        'PartNumber', 
        'Description', 
        'Price', 
        'Currency', 
        'Tax', 
        'Qty', 
        'Un', 
        'TotalPrice', 
        'CreatedBy', 
        'UpdatedBy'
    ];
}
