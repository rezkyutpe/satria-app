<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class TrxMat extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'trx_material';

  protected $fillable = [
    'id', 'material_number', 'material_description','no_so','req_deliv_date', 'cust', 'qty', 'note', 'attendance','file','accept_to', 'approve_to', 'accepted', 'accepted_date', 'accepted_remark', 'approved', 'approved_date', 'approved_remark', 'status','created_by'
];
}
