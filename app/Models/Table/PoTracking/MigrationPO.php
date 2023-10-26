<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class MigrationPO extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'migration_po';
  protected $primaryKey = 'id';

  protected $fillable = [
    'ebeln','submi','vendor_name','ernam','ernam_new','name1','created_by','updated_by'
  ];
}
