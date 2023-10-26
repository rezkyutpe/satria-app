<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstKontrak extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'mst_kontrak';

  protected $fillable = [    
    'kontrak_id', 'kontrak_no_kontrak', 'kontrak_perusahaan', 'kontrak_serial_number', 'kontrak_category', 'status', 'ctg_mtn_id', 'kontrak_date', 'kontrak_desc', 'kontrak_priority', 'kontrak_pic_nrp', 'kontrak_pic_email', 'kontrak_pic_name', 'kontrak_file','created_by',  'created_at'
  ];
}
