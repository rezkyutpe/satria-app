<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class COGSRawMaterial extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cogs_rawmaterial';
  protected $fillable = [
        'ID', 
        'COGSID',
        'Description', 
        'Spesification', 
        'Weight', 
        'Price', 
        'Currency', 
        'Status',
        'Un', 
        'FinalCost', 
        'CreatedBy', 
        'UpdatedBy', 
        'created_at', 
        'updated_at'
    ];
}
