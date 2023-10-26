<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class ScheduleMaintenance extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'schedule_maintenance';

  protected $fillable = [    
    'id', 'asset', 'category', 'flag', 'dept','created_by',  'created_at'
  ];
}
