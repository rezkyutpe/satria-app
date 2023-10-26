<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsSubcont extends Model
{

  protected $table = 'ms_subcont';

  protected $fillable = [
    'id', 'code', 'name', 'created_by'
  ];
}
