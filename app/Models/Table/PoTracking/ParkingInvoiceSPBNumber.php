<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class ParkingInvoiceSPBNumber extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'parkinginvoice_spbnumber';
  protected $primaryKey = 'ID';

  protected $fillable = [
    'Number','SPB_Number','InvoiceNumber','Status','created_by','updated_by',
  ];
}
