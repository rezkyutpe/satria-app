<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_history';

  protected $fillable = [    
    'id', 'transaksi', 'lokasi', 'kondisi', 'created_by',  
  ];
}
