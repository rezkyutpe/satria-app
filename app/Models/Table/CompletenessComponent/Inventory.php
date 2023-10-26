<?php
namespace App\Models\Table\CompletenessComponent;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
    protected $connection   = "mysql7";
    protected $table        = "inventory";
    public $timestamps      = false;
    protected $fillable     = [
        'material_number',
        'material_description',
        'material_type',
        'material_group',
        'base_unit',
        'plant',
        'storage_location',
        'stock',
        'in_qc',
        'free_stock',
        'alokasi_stock'
    ];
}