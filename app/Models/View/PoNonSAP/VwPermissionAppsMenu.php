<?php

namespace App\Models\View\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class VwPermissionAppsMenu extends Model
{

  protected $connection = 'mysql';
  protected $table = 'vw_permission_apps_menu';

  protected $fillable = [
    'id', 'user', 'app', 'menu', 'access', 'c', 'r', 'u', 'd', 'v', 'created_at', 'updated_at', 'app_name', 'link', 'logo', 'status', 'app_menu', 'menu_link', 'flag'
  ];
}
