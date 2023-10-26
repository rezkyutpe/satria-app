<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class LogHistoryTicket extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'log_history_ticket';
  protected $primaryKey = 'id';

  protected $fillable = [
    'iduser','name','idticket','description','datelog','timelog','updated_at','created_at'
  ];
}
