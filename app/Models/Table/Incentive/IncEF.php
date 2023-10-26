<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class IncEF extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'inc_ef';

  protected $fillable = [
    'id', 'descrip', 'percentage'
    ];
}
