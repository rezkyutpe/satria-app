<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ende extends Model
{

  protected $table = 'ende';

 
  protected $fillable = [
    'id', 'nrp', 'credential', 'pdf_pass', 'amount', 'created_by'
  ];
}
