<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class apiReqDate extends Model {
    protected $connection   = "mysql7";
    protected $table        = "api_reqdate";

    protected $fillable     = [
        'ProductionOrder',
        'ReqDate',
        'SerialNumber',
        'Operation',
        'description'
    ];
}