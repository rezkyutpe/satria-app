<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class Gpm extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'gpm';

  protected $fillable = [
    'gpm', 'min', 'max', 'percentage', 'cat'
    ];
}
