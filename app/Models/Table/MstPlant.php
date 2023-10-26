<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Model;

class MstPlant extends Model
{

  protected $table = 'mst_plant';

  protected $fillable = [
    'id', 'dept', 'dept_level', 'company_code', 'plant', 'plant_name', 'postcode', 'city', 'name'
  ];
}