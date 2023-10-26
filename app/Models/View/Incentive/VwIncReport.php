<?php

namespace App\Models\View\Incentive;

use Illuminate\Database\Eloquent\Model;

class VwIncReport extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'vw_report_inc';

  protected $fillable = [
    'inv', 'inv_date', 'cash_date', 'sales', 'sales_name', 'id_cust', 'customer', 'cust_profile', 'product', 'segment', 'qty', 'tot_cost', 'tot_price', 'grading', 'gpm', 'aging', 'target', 'inc_ef', 'cust_type','request', 'status', 'reason', 'created_at', 'updated_at', 'created_by', 'updated_by', 'total', 'inc'
  ];
}
