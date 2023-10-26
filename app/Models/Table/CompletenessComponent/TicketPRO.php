<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class TicketPRO extends Model {
    protected $connection   = "mysql7";

    protected $table        = "ticket_pro";

    protected $fillable     = [
        'ticket',
        'production_order',
        'req_date',
        'created_by'
    ];
}