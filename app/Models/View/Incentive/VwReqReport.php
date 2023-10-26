<?php

namespace App\Models\View\Incentive;

use Illuminate\Database\Eloquent\Model;

class VwReqReport extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'vw_report_req';

  protected $fillable = [
    'id_req', 'req_month', 'sales', 'sales_name', 'status', 'updated_at', 'total_inc'
  ];
}
