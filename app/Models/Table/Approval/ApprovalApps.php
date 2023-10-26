<?php

namespace App\Models\Table\Approval;

use Illuminate\Database\Eloquent\Model;

class ApprovalApps extends Model
{

  protected $table = 'approval_apps';

  protected $fillable = [
    'id', 'approval_name', 'get_link',  'created_by','updated_by'
  ];
}
