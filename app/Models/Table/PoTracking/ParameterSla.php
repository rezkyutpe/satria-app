<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class ParameterSla extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'parametersla';
  protected $primaryKey = 'id';

  protected $fillable = [
    'prcreatetoprrel ','prtopocreate ','pocreatetoporel','ticket_hour','created_by','updated_by'];
}
