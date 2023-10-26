<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsStatusEmp extends Model
{

  protected $table = 'ms_status_emp';

  protected $fillable = [
    'id', 'name', 'created_by'
  ];
}
