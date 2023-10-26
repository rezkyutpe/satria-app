<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class MstSapComp extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'component';

  protected $fillable = [
     'material', 'material_desc','oum','material_type'
];
}
