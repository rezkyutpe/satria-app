<?php

namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class MaterialHasilOlah extends Model
{
    protected $connection   = "mysql7";
    protected $table        = "material_hasil_olah";
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'AUFNR',
        'PLNBEZ',
        'DESC_PLNBEZ',
        'GroupProduct',
        'GAMNG',
        'sch_start_date',
        'sch_finish_date',
        'STAT',
        'DATE_STAT_CREATED',
        'MATNR',
        'MAKTX',
        'LTXA1',
        'WERKS',
        'LGORT',
        'MEINS',
        'MTART',
        'MATKL',
        'BDTER',
        'BDMNG',
        'ENMNG',
        'STOCK',
        'INSME'
    ];
}
