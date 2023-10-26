<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'comments';
  protected $primaryKey = 'id';

  protected $fillable = [
    'id', 'Number','ItemNumber', 'user_by','menu', 'user_to', 'comment','is_read', 'created_at', 'updated_at',
];
}
