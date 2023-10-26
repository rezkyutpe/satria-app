<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class MstPro extends Model
{

  protected $connection = 'mysql1';
  protected $table = 't_pro';

  protected $fillable = [
      'pro', 'pn', 'product',  'qty','cust'
  ];
}
