<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsLokasiMaintain extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'ms_lokasi_maintains';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'created_by',
        'updated_by',
        'status'       
    ];

    public static function idOtomatis()
    {
		$kode = "LM";
		$lastId= MsLokasiMaintain::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }

    
}
