<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsSupir extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'ms_supirs';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'kontak',
        'created_by',
        'updated_by',
        'status'       
    ];

    public static function idOtomatis()
    {
		$kode = "SP";
		$lastId= MsSupir::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }
}
