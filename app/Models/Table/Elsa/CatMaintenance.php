<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class CatMaintenance extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_cat_maintenance';

  protected $fillable = [    
    'id', 'note', 'start_alert', 'durasi', 'flag','dept', 'created_by',  'created_at'
  ];
}
