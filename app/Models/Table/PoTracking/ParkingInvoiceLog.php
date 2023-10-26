<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class ParkingInvoiceLog extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'parkinginvoice_log';
  protected $primaryKey = 'ID';

  protected $fillable = [
    'Number','InvoiceNumber','Description','Name','updated_by',
  ];
}
