<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class SubcontLeadtimeMaster extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'subcontleadtimemaster';
  public $timestamps = false;
  
  protected $fillable = [
    'ID ', 'LeadtimeName','Value'
];
}
