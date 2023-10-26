<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsSubKategoriKonsumable extends Model
{
    use HasFactory;

    public $incrementing = false;
	public $timestamps = false;

    protected $connection = 'mysql9';
    protected $table = 'ms_sub_kategori_konsumables';

    protected $fillable = [
        'id',
		'nama',
        'kategori_konsumable' ,
		'status',
		'created_by',
		'created_by'
    ];

	public static function idOtomatis()
    {
		$kode = "SK";
		$lastId= MsSubKategoriKonsumable::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
}
