<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoleGroup extends Model
{

  protected $table = 'user_role_group';

  protected $fillable = [
    'id', 'user', 'group'
  ];
}
