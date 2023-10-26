<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class DetailRenewKontrak extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'detail_renew_kontrak';

  protected $fillable = [    
    'detail_renew_kontrak', 'kontrak_id', 'detail_renew_date_renew', 'detail_renew_no_kontrak', 'detail_renew_ref_no_kontrak', 'detail_renew_serial_number', 'detail_renew_note', 'ctg_mtn_id', 'detail_renew_file','created_by',  'created_at'
  ];
}
