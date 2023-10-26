<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsKategoriKonsumable extends Model
{
    use HasFactory;

    public $incrementing = false;
	public $timestamps = false;

    protected $connection = 'mysql9';
    protected $table = 'ms_kategori_konsumables';

    protected $fillable = [
        'id',
        'nama' ,
		'status'      
    ];

	public static function idOtomatis()
    {
		$kode = "KK";
		$lastId= MsKategoriKonsumable::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
}
