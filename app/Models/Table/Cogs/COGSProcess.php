<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class COGSProcess extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cogs_process';
  protected $fillable = [
        'ID', 
        'COGSID', 
        'Process', 
        'Um', 
        'Hours', 
        'Cost', 
        'RateManhour',
        'CreatedBy', 
        'UpdatedBy', 
    ];
}
