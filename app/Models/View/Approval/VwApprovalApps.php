<?php

namespace App\Models\View\Approval;

use Illuminate\Database\Eloquent\Model;

class VwApprovalApps extends Model
{

  protected $table = 'vw_approval_apps';

  protected $fillable = [
    'id', 'approval', 'ket', 'get_link', 'post_link', 'response', 'approval_to', 'status', 'reason',  'created_by','updated_by', 'approval_name', 'get_link_apps'
  ];
}
