<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class SubcontDevUserRole extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'subcontdevuserroles';

  protected $fillable = [
    'ID ', 'Username', 'RoleID', 'IsHead', 'Created', 'CreatedBy', 'Modified', 'ModifiedBy'
];
}
