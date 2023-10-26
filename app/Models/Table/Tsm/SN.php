<?php

namespace App\Models\Table\Tsm;

use Illuminate\Database\Eloquent\Model;

class SN extends Model
{

  protected $connection = 'mysql11';
  protected $table = 'sn';
  protected $fillable = [
      'sn', 'mat_desc', 'mat_num', 'cust_name', 'deliv_date', 'created_at', 'updated_at'
    ];
}
