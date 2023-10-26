<?php

namespace App\Models\Table\Tsm;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{

  protected $connection = 'mysql11';
  protected $table = 'area';
  protected $fillable = [
      'area', 'plant', 'created_at', 'updated_at'
    ];
}
