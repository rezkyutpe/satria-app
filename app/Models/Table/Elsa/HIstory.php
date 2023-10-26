<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'history';

  protected $fillable = [    
    'id', 'id_ticket', 'timestamp', 'title', 'description', 'people', 'order'
  ];
}
