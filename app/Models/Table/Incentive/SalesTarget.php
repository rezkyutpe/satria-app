<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'sales_target';

  protected $fillable = [
    'id', 'sales_id', 'month', 'year'
    ];
}
