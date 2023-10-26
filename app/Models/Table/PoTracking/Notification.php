<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'notification';

  protected $fillable = [
    'id', 'Number','Subjek', 'user_by','menu', 'user_to', 'comment','is_read', 'created_at', 'updated_at'
];
}
