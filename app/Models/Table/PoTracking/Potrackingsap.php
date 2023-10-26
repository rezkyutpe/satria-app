<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class Potrackingsap extends Model
{

  protected $connection = 'mysql6';
  protected $table = 'potrackingsap';
  protected $primaryKey = 'id';

  protected $fillable = [
    'bsart','ebeln','ebelp','loekz','udate','aedat','ernam','name2','menge','lifnr','name1',
    'zterm','ekgrp','waers','gjahr','belnr','buzei','bewtp','bwart','budat','grmen','wrbtr',
    'waer1','elikz','erekz','knttp','plifz','banfn','bnfpo','frgdt','cpudt','cputm','reewr',
    'matnr','werks','bldat','erna1','vbeln','posnr','maktx','eindt','bstmg','outgr','podat',
    'netpr','idnlf','badat','txz01','frgkz','xblnr',
    'SHKZG','TEXT','CHANGENR','NAME_FIRST','STRAS','ORT01','PSTLZ','TELF1','TELFX','BOLNR',
    'INCO2','MWSKZ','WERKS_2','LGORT','MEINS','BUKRS','LFBNR','LFPOS',
    'created_at','created_by'
];
}
