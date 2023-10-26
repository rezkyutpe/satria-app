<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class DetailTicket extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'detailticketingdelivery';

  protected $fillable = [
    'ID ', 'TicketID ','PDIID','Number','ItemNumber','Material','Description','Quantity','status','remarks','DeliveryDate','AcceptedDate','headretext','SPBDate','Plant','created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
];
}
