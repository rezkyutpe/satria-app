<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class MstBom extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'bom_qfd';

  protected $fillable = [
    'id', 'material_qfd', 'material_desc', 'item', 'component', 'component_desc', 'qty', 'oum'
];
}
