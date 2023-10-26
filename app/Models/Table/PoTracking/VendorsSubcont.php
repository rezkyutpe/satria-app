<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class VendorsSubcont extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'subcontcomponentcapabilities';

  protected $fillable = [
    'ID ', 'VendorCode','Description', 'isNeedSequence', 'Material', 'DailyLeadTime', 'MonthlyLeadTime', 'PB', 'Setting', 'Fullweld', 'Primer', 'MonthlyCapacity', 'DailyCapacity','CreatedBy','LastModifiedBy'
];
}
