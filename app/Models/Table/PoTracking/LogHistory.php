<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class LogHistory extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'log_history';

  protected $fillable = [
    'id ', 'user', 'menu', 'description', 'date', 'time','ponumber','poitem','userlogintype','vendortype','created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
