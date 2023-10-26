<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'ticketingdelivery';

  protected $fillable = [
    'ID','POID','ticketid','Remarks','DeliveryDate','Status','created_at','CreatedBy','updated_at','LastModifiedBy'
];
}
