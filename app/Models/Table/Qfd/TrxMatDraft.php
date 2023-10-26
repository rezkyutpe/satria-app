<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class TrxMatDraft extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'trx_material_draft';

  protected $fillable = [
    'id', 'material_number', 'material_description','no_so','req_deliv_date' ,'cust', 'qty', 'note', 'attendance','file', 'accepted', 'accepted_date', 'accepted_remark', 'approved', 'approved_date', 'approved_remark', 'status','created_by'
];
}
