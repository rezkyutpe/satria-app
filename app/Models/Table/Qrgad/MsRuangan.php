<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsRuangan extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'ms_ruangans';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'kapasitas',
        'lantai',
        'lokasi',
        'status',
        'created_at',
        'created_by'
    ];

    public static function idOtomatis()
    {
		$kode = "RG";
		$lastId= MsRuangan::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }

}
