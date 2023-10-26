<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class Status extends Model {
    public $timestamps      = false;
    protected $connection   = "mysql7";
    protected $table        = "tj02";
    protected $fillable     = [
        'ISTAT', 
        'TXT04', 
        'TXT30'
    ];
}