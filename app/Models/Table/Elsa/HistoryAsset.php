<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class HistoryAsset extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'history_asset';

  protected $fillable = [    
    'id', 'asset_id', 'room', 'asset_condition', 'pic', 'note', 'created_by'
  ];
}
