<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class CustType extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'cust_type';

  protected $fillable = [
    'id', 'descrip', 'percentage'
    ];
}
