<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class ParkingInvoiceDocument extends Model
{
  protected $connection = 'mysql6';
  protected $table = 'parkinginvoicedocument';
  protected $primaryKey = 'ID';
  protected $fillable = [
    'Number','InvoiceNumber','FileName','Status', 'created_at', 'updated_at', 'CreatedBy'
  ];
}
