<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class MigrationProcurementPO extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'migration_procurement_po';
  protected $primaryKey = 'id';

  protected $fillable = [
    'name','procurement','procurement_code','vendor_type','created_by','updated_by'
  ];
}
