<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class MstPackage extends Model
{

  protected $connection = 'mysql1';
  protected $table = 't_package';

  protected $fillable = [
    'id', 'package', 'ket', 'qty', 'name', 'descr', 'pn_eaton', 'flag'
  ];
}
