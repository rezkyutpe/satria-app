<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstLocMesin extends Model
{
    public $timestamps = True;
    protected $connection = 'mysql8';
    protected $table = 'mst_loc_mesin';

    protected $guarded = [
        'id_loc_mesin',
    ];
}
