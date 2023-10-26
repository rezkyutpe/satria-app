<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class RequestInc extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'request';

  protected $fillable = [
    'id_req', 'sales', 'req_month', 'ket', 'accepted',  'accepted_date', 'accepted_remark', 'approved', 'approved_date', 'approved_remark','status', 'created_at', 'created_by', 'updated_at', 'updated_by'
];
  
}
