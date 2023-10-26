<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class MaterialTemporary extends Model {
    public $timestamps      = false;
    protected $connection   = "mysql7";
    protected $table        = "material_temporary";
    protected $fillable     = [
        'AUFNR',
        'PLNBEZ',
        'DESC_PLNBEZ',
        'GroupProduct',
        'STAT',
        'DATE_STAT_CREATED',
        'GAMNG',
        'sch_start_date',
        'MATNR',
        'MAKTX',
        'MEINS',
        'MTART',
        'MATKL',
        'WERKS',
        'LGORT',
        'BDTER',
        'BDMNG',
        'ENMNG',
        'STOCK',
        'RESERVE',
        'MINUS_PLOTTING',
        'RESTOCK',
        'INSME',
        'LTXA1',
        'request_qty'
    ];
}