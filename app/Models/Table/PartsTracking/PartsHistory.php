<?php

namespace App\Models\Table\PartsTracking;

use Illuminate\Database\Eloquent\Model;

class PartsHistory extends Model
{

  protected $connection = 'mysql5';
  protected $table = 't_history';

  protected $fillable = [    
    'id', 'transaksi', 'lokasi', 'kondisi', 'created_at', 'created_by'
  ];
}
