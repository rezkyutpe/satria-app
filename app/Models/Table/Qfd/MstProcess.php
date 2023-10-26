<?php

namespace App\Models\Table\Qfd;

use Illuminate\Database\Eloquent\Model;

class MstProcess extends Model
{

  protected $connection = 'mysql4';
  protected $table = 'proses';

  protected $fillable = [
    'id', 'nama','created_by'
];
}
