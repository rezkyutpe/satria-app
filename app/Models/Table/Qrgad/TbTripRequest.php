<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbTripRequest extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_trip_requests';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "id",
        "jenis_perjalanan",
        "tujuan",
        "pemohon",
        "wilayah",
        "keperluan",
        "waktu_berangkat",
        "waktu_pulang",
        "penumpang",
        "count_people",
        "keterangan",
        "departemen",
        "input_time",
        "approve_time",
        "approve_by",
        "response_time",
        "close_time",
        "status",
    ];

    public static function idOtomatis()
    {
		$kode = "TR";
		$lastId= TbTripRequest::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
    
}
