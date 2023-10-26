<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class UserVendorWTHTax extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'uservendors_wth_tax';
  protected $primaryKey = 'ID';

  protected $fillable = [
    'VendorCode','CompanyCode','WithholdingTaxType','SubjectToWithholdingTax','WithholdingTaxCode','ExemptionRate','created_by','updated_by'
  ];
}
