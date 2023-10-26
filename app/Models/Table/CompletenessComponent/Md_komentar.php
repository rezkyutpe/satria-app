<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class Md_komentar extends Model {
    public $timestamps      = false;
    protected $connection   = "mysql7";
    protected $table        = "md_komentar";
    protected $fillable     = [
        'id', 
        'komentar', 
        'status',
        'created_by'
    ];
}