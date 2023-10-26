<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbKonsumable extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_konsumables';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama',
        'kategori_konsumable',
        'sub_kategori_konsumable',
        'jenis_satuan',
        'minimal_stock'
    ];

    public static function idOtomatis()
    {
		$kode = "KS";
		$lastId= TbKonsumable::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }

}
