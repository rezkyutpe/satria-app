<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbTrip extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_trips';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "id",
        "trip_request",
        "kendaraan",
        "supir",
		    "departure_time",
        "status",
		    "cancel_time",
		    "cancel_by",
		    "keterangan",
    ];

	public static function idOtomatis()
    {
		$kode = "TP";
		$lastId= TbTrip::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
    
}
