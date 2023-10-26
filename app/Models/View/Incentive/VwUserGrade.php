<?php

namespace App\Models\View\Incentive;

use Illuminate\Database\Eloquent\Model;

class VwUserGrade extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'user_grade';

  protected $fillable = [
    'id_user', 'name', 'email', 'id', 'description', 'percentage', 'month', 'year'
    ];
}
