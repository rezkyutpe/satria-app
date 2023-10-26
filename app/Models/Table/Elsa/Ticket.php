<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'ticket';

  protected $fillable = [    
    'id', 'ticket_id','dept', 'assist_id', 'subject', 'reporter_nrp','reporter_by', 'asset_id', 'message', 'notification', 'media', 'location', 'priority', 'note', 'respond_time', 'resolve_time', 'resolve_status', 'resolve_result', 'resolve_percent','rate','review', 'sla', 'approve_dept_to', 'approve_dept', 'approve_dept_at', 'approve_dept_remark', 'approve_div_to', 'approve_div', 'approve_div_at', 'approve_div_remark',  'approve_dic_to', 'approve_dic', 'approve_dic_at', 'approve_dic_remark',  'rejected', 'rejected_date', 'rejected_remark','status', 'flag', 'analisis', 'corrective', 'preventive', 'consumable', 'costs', 'created_by',  'created_at'
  ];
}
