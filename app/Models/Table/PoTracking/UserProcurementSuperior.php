<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class UserProcurementSuperior extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'userprocurementsuperior';

  protected $fillable = [
    'ID ', 'Username', 'FullName', 'ParentID', 'NRP', 'Email','created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
