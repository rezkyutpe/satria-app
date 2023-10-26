<?php

namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class LogHistory extends Model
{
    public $timestamps      = false;
    protected $connection   = "mysql7";
    protected $table        = "log_history";
    protected $fillable     = [
        'user',
        'menu',
        'description',
        'date',
        'time'
    ];
}
