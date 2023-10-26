<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstInfo extends Model
{

  protected $table = 'portal_information';

  protected $fillable = [
    'id', 'name', 'content', 'image'
  ];
}
