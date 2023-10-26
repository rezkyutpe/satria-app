<?php

namespace App\Models\Table\Incentive;

use Illuminate\Database\Eloquent\Model;

class AdjustmentHistory extends Model
{

  protected $connection = 'mysql3';
  protected $table = 'adjusment_history';

  protected $fillable = [
    'id', 'inc', 'cash_date_old', 'cash_date_new', 'inc_ef_old', 'inc_ef_new', 'created_at', 'created_by', 'updated_at', 'updated_by'
];
  
}
