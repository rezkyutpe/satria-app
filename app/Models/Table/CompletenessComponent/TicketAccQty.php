<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class TicketAccQty extends Model {
    protected $connection   = "mysql7";
    protected $table        = "ticket_acc_qty";
    public $timestamps      = false;

    protected $fillable     = [
        'id',
        'id_component',
        'accepted_qty',
        'created_at',
        'updated_at'
    ];
}