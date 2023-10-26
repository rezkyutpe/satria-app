<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class RolesType extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'rolestype';

  protected $fillable = [
    'ID ', 'Name'
];
}
