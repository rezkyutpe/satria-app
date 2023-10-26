<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxWfa extends Model
{

  protected $table = 'trx_wfa';

  protected $fillable = [
    'id', 'id_user', 'worklocation', 'start_date', 'end_date', 'destination_lat','destination_long', 'destination_country_code', 'destination_province', 'destination_city', 'destination_address', 'distance', 'approve_dept_to', 'approve_dept', 'approve_dept_at', 'approve_dept_remark', 'approve_div_to', 'approve_div', 'approve_div_at', 'approve_div_remark', 'approve_dic_to', 'approve_dic', 'approve_dic_at', 'approve_dic_remark', 'rejected', 'rejected_at', 'rejected_remark', 'reason', 'attendance_flag', 'created_by','updated_by'
  ];
}
