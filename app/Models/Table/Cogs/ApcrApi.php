<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class ApcrApi extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'apcr_api';
  protected $fillable = [
      'Owner',
      'CreatedOn',
      'PCRID',
      'Opportunity',
      'PIC',
      'NeedRevision',
      'PLI_ID',
      'NeedRevisionPLI_ID',
      'COGS',
      'Price',
      'GP',
      'ApprovalStatus',
      'Status',
      'Stagging',
    ];
}
