<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstMenu extends Model
{

  protected $table = 'apps_menu';

  protected $fillable = [
    'id', 'app','topmain','main', 'menu', 'link','icon','flag'
  ];
}
