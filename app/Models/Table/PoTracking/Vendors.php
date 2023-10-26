<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'uservendors';

  protected $fillable = [
    'Name', 'CountryCode', 'VendorCode', 'Address', 'PhoneNo', 'VendorType'
];
}
