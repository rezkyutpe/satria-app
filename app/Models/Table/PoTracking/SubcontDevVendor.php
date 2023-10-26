<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class SubcontDevVendor extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'subcontdevvendors';

  protected $fillable = [
    'ID ', 'Username', 'VendorCode','created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
