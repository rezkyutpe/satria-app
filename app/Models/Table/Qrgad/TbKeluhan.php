<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbKeluhan extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_keluhans';
    
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'keluhan',
        'aset',
        'non_aset',
        'lokasi',
        'detail_lokasi',
        'pelapor',
        'input_time',
        'respon_time',
        'close_time',
        'status',
        'grup',
    ];

    public static function idOtomatis()
    {
		$kode = "KL";
		$lastId= TbKeluhan::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
    
}
