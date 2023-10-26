<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'process';
  protected $fillable = [
    'ID'
    ,'Name'
    ,'IsActive'
    ,'Version'
    ,'IsTotalDayCalculated'
    ,'TotalDay'
    ,'ProductName'
    ,'ProcessOrder'
    ,'ProcessGroup'
    ,'ProcessName'
    ,'ManHour'
    ,'ManPower'
    ,'CycleTime'
  ];
}
