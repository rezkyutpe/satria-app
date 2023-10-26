<?php

namespace App\Models\Table\Approval;

use Illuminate\Database\Eloquent\Model;

class ApprovalDetail extends Model
{

  protected $table = 'approval_detail';

  protected $fillable = [
    'id', 'approval', 'ket', 'get_link', 'post_link', 'response', 'approval_to', 'approval_level','status', 'reason',  'created_by','updated_by'
  ];
}
