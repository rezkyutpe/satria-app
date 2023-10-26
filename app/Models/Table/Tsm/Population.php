<?php

namespace App\Models\Table\Tsm;

use Illuminate\Database\Eloquent\Model;

class Population extends Model
{

  protected $connection = 'mysql11';
  protected $table = 'population';
  public $timestamps = false;
  protected $fillable = [
      'serial_number', 'description', 'part_number', 'plant', 'area', 'end_customer', 'status', 'customer', 'no_lambung', 'hm_km', 'satuan', 'type_of_service', 'deliv_date', 'commisioning_date', 'general_category', 'type_of_service', 'tgl_service', 'brand', 'notbrand','created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
