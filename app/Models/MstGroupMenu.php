<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstGroupMenu extends Model
{

  protected $table = 'permission_group_menu';

  protected $fillable = [
    'id', 'group', 'app', 'menu', 'access', 'c', 'r', 'u', 'd', 'v'
  ];
}
