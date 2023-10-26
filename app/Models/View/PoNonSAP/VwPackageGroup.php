<?php

namespace App\Models\View\PoNonSAP;

use Illuminate\Database\Eloquent\Model;

class VwPackageGroup extends Model
{

  protected $connection = 'mysql1';
  protected $table = 'vw_package_group';

  protected $fillable = [
    'package', 'total', 'as_updated'
  ];
}
