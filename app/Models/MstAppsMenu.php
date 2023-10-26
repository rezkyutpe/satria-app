<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAppsMenu extends Model
{

  protected $table = 'permission_apps_menu';

  protected $fillable = [
    'id', 'user', 'app', 'menu', 'access', 'c', 'r', 'u', 'd', 'v'
  ];
}
