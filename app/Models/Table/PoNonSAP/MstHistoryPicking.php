<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class MstHistoryPicking extends Model
{

  protected $connection = 'mysql1';
  protected $table = 't_history_picking';

  protected $fillable = [
    'id', 'no_po', 'flag','created_by','updated_by'
  ];
}
