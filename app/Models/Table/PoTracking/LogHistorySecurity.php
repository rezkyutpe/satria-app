<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class LogHistorySecurity extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'log_history_security';
  protected $primaryKey = 'id';

  protected $fillable = [
    'user',
    'idticket',
    'description',
    'date',
    'time',
    'updated_at',
    'created_by',
    'created_at'
  ];
}
