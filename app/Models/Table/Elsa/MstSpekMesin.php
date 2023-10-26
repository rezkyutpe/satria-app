<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstSpekMesin extends Model
{
    public $timestamps = true;
    protected $connection = 'mysql8';
    protected $table = 'mst_spek_mesin';

    protected $fillable = [
        'id_spek_mesin',
        'id_mesin',
        'description',
        'unit',
        'value',
    ];
}
