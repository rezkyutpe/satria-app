<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class PP extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'progressphoto';
  protected $primaryKey = 'ID';

  protected $fillable = [
    'PurchasingDocumentItemID','PONumber','ItemNumber','FileName','ProcessName', 'created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
