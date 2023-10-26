<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class ReasonSubCont extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'sequencesprogressreasons';

  protected $fillable = [
    'ID ', 'Name', 'created_at', 'CreatedBy', 'update_at', 'ModifiedBy'
];
}
