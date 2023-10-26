<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class TrxBomDetail extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'trx_bom_detail';

  protected $fillable = [
    'id', 'material_qfd', 'material_desc', 'item', 'component', 'component_desc', 'qty', 'oum', 'trx_material','flag', 'created_by'
];
}
