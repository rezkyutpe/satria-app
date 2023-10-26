<?php

namespace App\Models\View\Incentive;

use Illuminate\Database\Eloquent\Model;

class VwIncGroup extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'vw_inc_group';

  protected $fillable = [
    'sales', 'name', 'email', 'inv_date', 'cash_date', 'total_price', 'total_inc', 'status'
    ];
}
