<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class DelayReason extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'delayreason';

  protected $fillable = [
    'ID ', 'Name', 'create_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
