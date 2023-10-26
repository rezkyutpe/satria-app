<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class MstKomponen extends Model
{
  protected $connection = 'mysql1';

  protected $table = 't_komponen';

  protected $fillable = [
      'pn_patria', 'description', 'pn_vendor','price','uom','type'
  ];
}
