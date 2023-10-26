<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstAssets extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_asset';

  protected $fillable = [    
    'id', 'asset_num', 'asset_desc', 'asset_class', 'asset_sn', 'asset_createdby', 'asset_createdon', 'fa_period', 'capitalized', 'useful_life', 'cost_center', 'company_code', 'plant', 'location', 'room', 'asset_condition', 'pic', 'dept', 'created_by'
  ];
}
