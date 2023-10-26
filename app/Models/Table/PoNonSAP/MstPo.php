<?php

namespace App\Models\Table\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class MstPo extends Model
{

  protected $connection = 'mysql1';
  protected $table = 't_po';

  protected $fillable = [
      'nopo', 'pro','po_ref', 'qty_unit','created_at',  'updated_at','created_by',  'updated_by','ttd1','ttd2','uuid','flag','is_invoiced','is_deleted'
  ];
}
