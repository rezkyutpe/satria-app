<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class UsingAsset extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'using_asset';

  protected $fillable = [    
    'id', 'asset_id', 'status', 'user_nrp', 'user_dept', 'user_name', 'room', 'approve_by_nrp', 'approve_date', 'approve_status','created_by',  'created_at'
  ];
}
