<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsFasilitasRuangan extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $connection = 'mysql9';
    protected $table = 'ms_fasilitas_ruangans';

    protected $fillable = [
        'id',
        'nama',
        'created_by',
        'updated_by',
        'status'       
    ];

	public static function idOtomatis()
    {
		$kode = "FS";
		$lastId= MsFasilitasRuangan::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }

}
