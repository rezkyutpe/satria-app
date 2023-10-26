<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'customer';

  protected $fillable = [
    'id', 'name', 'status'
    ];
}
