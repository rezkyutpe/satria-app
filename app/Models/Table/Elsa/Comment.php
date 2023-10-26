<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'comment';

  protected $fillable = [    
    'id', 'id_ticket', 'status', 'text', 'people', 'timestamp', 'media', 'notification', 'order','created_by'
  ];
}
