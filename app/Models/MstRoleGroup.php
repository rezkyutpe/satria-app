<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRoleGroup extends Model
{

  protected $table = 'role_group';

  protected $fillable = [
    'id', 'name', 'apps', 'flag'
  ];
}
