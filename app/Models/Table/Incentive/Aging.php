<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class Aging extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'aging';

  protected $fillable = [
    'id', 'min', 'max', 'descrip', 'percentage', 'type', 'cat'
    ];
}
