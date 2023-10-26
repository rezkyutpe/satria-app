<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'grade';

  protected $fillable = [
    'id','description', 'percentage','month','year','created_by','updated_by'
    ];
}
