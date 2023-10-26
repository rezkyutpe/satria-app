<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstMesin extends Model
{
    protected $connection = 'mysql8';
    protected $table = 'mst_mesin';

    protected $guarded = [
        'id_mesin',
    ];
}
