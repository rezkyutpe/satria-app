<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class InventoryProblem extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'inventory_problem';

  protected $fillable = [    
    'prob_id', 'inventory_id','prob_type','from_id', 'prob_note', 'prob_qty', 'dept',  'created_by',  'created_at'
  ];
}
