<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class ReportGI extends Model {
    public $timestamps      = false;
    protected $connection   = "mysql7";
    protected $table        = "report_gi";
    protected $fillable     = [
        'production_order',
        'req_qty_zcom',
        'gi_zcom',
        'allocated_zcom',
        'req_qty_zbup',
        'gi_zbup',
        'allocated_zbup',
        'req_qty_zcns',
        'gi_zcns',
        'allocated_zcns',
        'req_qty_zraw',
        'gi_zraw',
        'allocated_zraw',
        'created_at'
    ];
}