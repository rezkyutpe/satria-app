<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbTripHistori extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_trip_historis';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "id",
        "trip",
        "kilometer_berangkat",
        "waktu_berangkat",
        "kilometer_pulang",
        "waktu_pulang",
        "penumpang",
        "kilometer_total",
    ];

    public static function idOtomatis()
    {
		$kode = "TH";
		$lastId= TbTripHistori::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
    
}
