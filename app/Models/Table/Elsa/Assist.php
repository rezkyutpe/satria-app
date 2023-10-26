<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class Assist extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'assist';

  protected $fillable = [    
    'id', 'satria_id', 'name', 'nickname', 'id_group', 'nrp', 'title', 'email', 'mobile', 'avatar', 'rating', 'sunfish', 'flag', 'created_by',  'created_at'
  ];
}
