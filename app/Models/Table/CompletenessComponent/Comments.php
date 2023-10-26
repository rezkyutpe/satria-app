<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model {
    protected $connection   = "mysql7";
    protected $table        = "comments";
    protected $fillable     = [
        'MATNR',
        'user_by',
        'user_to',
        'comment',
        'is_read',
        'created_at',
        'updated_at'
    ];
}