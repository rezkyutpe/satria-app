<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class AssistGroup extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'assist_group';

  protected $fillable = [    
    'id', 'name', 'dept', 'status',  'created_by',  'created_at'
  ];
}
