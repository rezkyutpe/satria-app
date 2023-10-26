<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstAssetStatus extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_asset_status';

  protected $fillable = [    
    'id', 'name', 'color','flag','created_by',  'created_at'
  ];
}
