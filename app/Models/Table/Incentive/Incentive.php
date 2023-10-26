<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'inc_trx';

  protected $fillable = [
    'inv', 'inv_date', 'cash_date', 'sales','id_cust', 'customer', 'cust_profile', 'product', 'segment', 'qty', 'tot_cost', 'tot_price','cash_in','gpm','aging','target','inc_ef','request','status', 'created_at', 'updated_at', 'created_by', 'updated_by'
  ];
  
}
