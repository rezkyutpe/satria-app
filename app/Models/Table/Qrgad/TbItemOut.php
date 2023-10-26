<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbItemOut extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_item_outs';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'konsumable',
        'keluhan',
        'jumlah',
        'keterangan',
        'username'
    ];

    public static function idOtomatis()
    {
		$kode = "IO";
		$lastId= TbItemOut::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
}
