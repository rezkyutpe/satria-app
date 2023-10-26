<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbInventory extends Model
{
    use HasFactory;

    protected $connection = 'mysql9';
    protected $table = 'tb_inventoris';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'konsumable',
        'jumlah_stock',
        'date_in',
        'nama_toko',
        'harga_item',
        'username'
    ];

    public static function idOtomatis()
    {
		$kode = "IV";
		$lastId= TbInventory::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }

}
