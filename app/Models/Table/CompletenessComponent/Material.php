<?php

namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $connection   = "mysql7";
    protected $table        = "material";
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'RSNUM',
        'RSPOS',
        'MATNR',
        'MAKTX',
        'WERKS',
        'LGORT',
        'BDTER_SAP',
        'BDTER_API',
        'BDMNG',
        'LABST',
        'INSME',
        'MEINS',
        'ENMNG',
        'STLNR',
        'STLAL',
        'AUFPL',
        'VORNR',
        'OBJNR',
        'LTXA1',
        'AUFNR',
        'MTART',
        'MATKL',
        'BAUGR',
        'APLZL',
        'STEUS',
        'RUECK'
    ];
}
