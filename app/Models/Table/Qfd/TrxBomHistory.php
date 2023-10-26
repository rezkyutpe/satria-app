<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class TrxBomHistory extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'vw_bom_history';

  protected $fillable = [
    'id', 'material_qfd', 'material_desc', 'item', 'component', 'component_desc', 'qty', 'oum', 'trx_material', 'flag', 'created_at', 'updated_at', 'created_by', 'updated_by', 'item_bom', 'component_bom', 'component_desc_bom', 'qty_bom', 'oum_bom'
];
}
