<?php

namespace App\Models\Table\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TbJadwalRuangan extends Model
{
    use HasFactory;
    protected $connection = 'mysql9';
    protected $table = 'tb_jadwal_ruangans';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'agenda',
        'peminjam',
        'perusahaan',
        'ruangan',
        'start',
        'end',
        'color'
    ];

    public static function idOtomatis()
    {
		$kode = "JR";
		$lastId= TbJadwalRuangan::max('id');
		$serial = str_replace($kode, "", $lastId);
		$newserial= ((int) $serial + 1);
		$newId= $kode.str_pad($newserial, (8-strlen($newserial)), "0", STR_PAD_LEFT);
    	
		return $newId;
    }

    // public static function dateCheck($start, $end){
    //     $jadwals = TbJadwalRuangan::all();
    //     $isValid = false;

    //     foreach($jadwals as $j){
    //         if(
    //             ($start >= $j->start && $start <= $j->end)  // start_input nya berada di antara start dan end yang sudah ada
    //             //or ($end >= $j->start and $end <= $j->end) // end_input nya berada di antara start dan end yang sudah ada
    //             //or ($start <= $j->start and $end >= $j->end) // start_input melebihi start yang ada dan end_input melebihi end yang ada
                
    //             ){
    //                 $isValid = true;
    //                 break;

    //                 dd("start_input ".$start." start_db ".$j->start." end_db ".$j->end);
    //         } else {
    //             $isValid = false;
    //         }
    //     }

    //     return $isValid;
    // }

    // public static function getByIdDate($id, $start, $end){
    //     $list = DB::select("SELECT * FROM `satria_qrgad.vw_jadwal_ruangans` WHERE id_ruangan = '".$id."' AND start >='".$start."' AND end <= '".$end."'");
    //     return $list;
    // }
    
}
