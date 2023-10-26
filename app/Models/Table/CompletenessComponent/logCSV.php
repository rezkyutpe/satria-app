<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class logCSV extends Model {
    protected $connection   = "mysql7";
    protected $table        = "log_history_csv";
    public $timestamps      = false;

    protected $fillable     = [
        'proses',
        'filename',
        'total_row',
        'start',
        'stop',
        'status',
        'message',
        'upload_by'
    ];
}