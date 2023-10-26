<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbTripVoucher extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_trip_vouchers';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "id",
        "trip",
		"kode_voucher"
    ];

	public static function idOtomatis()
    {
		$kode = "TV";
		$lastId= TbTripVoucher::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
    
}
