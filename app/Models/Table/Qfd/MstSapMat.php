<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class MstSapMat extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'vw_material_qfd';

  protected $fillable = [
    'smt_id', 'smt_name', 'smt_desc'
];
}
