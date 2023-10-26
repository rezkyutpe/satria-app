<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'users';
  protected $primaryKey = 'id';

  protected $fillable = [
    'id_user', 'name', 'email', 'assign_plant', 'created_by','updated_at',
  ];
}
