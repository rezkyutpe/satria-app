<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstDetailMesin extends Model
{
    protected $connection = 'mysql8';
    protected $table = 'mst_detail_mesin';

    protected $fillable = [
        'id_detail_mesin',
        'id_mesin',
        'category',
        'function',
        'material',
        'length_mesin',
        'width',
        'thickness',
        'tools',
        'remarks'
    ];
}
