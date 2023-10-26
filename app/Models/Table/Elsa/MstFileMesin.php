<?php

namespace App\Models\Table\Elsa;

use Illuminate\Database\Eloquent\Model;

class MstFileMesin extends Model
{
    public $timestamps = True;
    protected $connection = 'mysql8';
    protected $table = 'mst_file_mesin';

    protected $fillable = [
        'id_file',
        'id_mesin',
        'file_name'
    ];
}
