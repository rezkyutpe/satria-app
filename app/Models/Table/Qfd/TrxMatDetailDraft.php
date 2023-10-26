<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class TrxMatDetailDraft extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'trx_material_detail_draft';

  protected $fillable = [
    'id','trx_material', 'id_mat', 'id_proses', 'from', 'to', 'diff', 'pic','pic_nrp','pic_email', 'remark',  'created_by'
];
}
