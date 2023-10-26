<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class SubcontDevRoles extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'subcontdevroles';

  protected $fillable = [
    'ID ', 'Name', 'created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
