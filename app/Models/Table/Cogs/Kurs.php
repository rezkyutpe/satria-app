<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'kurs';
  protected $fillable = [
        'MataUang',
        'Nilai',
        'KursJual',
        'KursBeli',
        'KursTengah',
    ];
}
