<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class TargetPercent extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'target_percentage';

  protected $fillable = [
    'id', 'min', 'max', 'percentage','cat','created_by','updated_by'
    ];
}
