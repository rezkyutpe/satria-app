<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'kurs';
  protected $primaryKey = 'id';

  protected $fillable = [
    'MataUang', 'Nilai', 'KursJual', 'KursBeli', 'KursTengah', 'created_at', 'updated_at',
];
}
