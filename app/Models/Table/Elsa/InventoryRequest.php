<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{

  protected $connection = 'mysql8';
  protected $table = 'inventory_request';

  protected $fillable = [    
    'pr_id', 'pr_number', 'pr_nrp', 'pr_name', 'pr_dept', 'pr_quantity','pr_to', 'pr_inventory_id', 'pr_remark', 'pr_description', 'pr_category','accept_to', 'approve_to', 'accepted', 'accepted_date', 'approved', 'approved_date','status', 'dept','created_by',  'created_at'
  ];
}
