<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class DisabledDays extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'disabled_days';
  protected $primaryKey = 'ID';
  
  public $timestamps = false;
  
  protected $fillable = [
    'event_date','event_years','is_disabled','is_active','created_by','updated_by'
  ];
}
