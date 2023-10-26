<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class LogErrorCSV extends Model
{
  protected $connection = 'mysql6';
  protected $table = 'log_error_csv';
  protected $primaryKey = 'id';
  
  protected $fillable = [
    'action','filename','code','message','ex_string','created_by','created_at','updated_by','updated_at',
  ];
}
