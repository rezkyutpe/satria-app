<?php

namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class CommentsMaterial extends Model
{
    public $timestamps      = false;
    protected $connection   = "mysql7";
    protected $table        = "comments_material";
    protected $fillable     = [
        'production_order',
        'product_number',
        'serial_number',
        'material_number',
        'komentar',
        'updated_by'
    ];
}
