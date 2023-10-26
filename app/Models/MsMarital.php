<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsMarital extends Model
{

  protected $table = 'ms_marital_status';

  protected $fillable = [
    'id', 'code', 'ket', 'created_by'
  ];
}
