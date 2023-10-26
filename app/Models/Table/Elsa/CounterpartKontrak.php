<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class CounterpartKontrak extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'counterpart_kontrak';

  protected $fillable = [    
    'id', 'kontrak_id', 'counterpart_company', 'counterpart_contact', 'created_by',  'created_at'
  ];
}
