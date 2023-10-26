<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class MstHistoryPackage extends Model
{

  protected $connection = 'mysql1';
  protected $table = 't_history_package';

  protected $fillable = [
    'id', 'id_package', 'flag','created_by','updated_by'
  ];
}
