<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'bom';

  protected $fillable = [
      'Material'
      ,'Description'
      ,'Plant'
      ,'BaseQty'
      ,'ReqdQty'
      ,'Level'
      ,'Item'
      ,'ComponentCategory'
      ,'ComponentNumber'
      ,'ObjectDescription'
      ,'Qty'
      ,'Un'
    ];
}
