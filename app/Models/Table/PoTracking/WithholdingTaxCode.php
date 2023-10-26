<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class WithholdingTaxCode extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'withholding_tax_code';
  protected $primaryKey = 'id';

  protected $fillable = [
    'name','tax_code'
  ];
}
