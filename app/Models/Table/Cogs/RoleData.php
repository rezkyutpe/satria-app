<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class RoleData extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'role_data';

  protected $fillable = [
      'ID', 
      'RoleName', 
      'CreatedBy', 
      'UpdatedBy', 
      'created_at', 
      'updated_at'
    ];
}
