<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class TicketListComponent extends Model {
    protected $connection   = "mysql7";
    protected $table        = "ticket_list_component";
    protected $fillable     = [
        'id_ticket',
        'production_order',
        'material_number',
        'material_description',
        'base_unit',
        'material_type',
        'requirement_date',
        'requirement_quantity',
        'good_issue',
        'request_quantity',
        'accepted_quantity',
        'status'
    ];
}