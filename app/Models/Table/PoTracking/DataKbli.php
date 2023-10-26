<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class DataKbli extends Model
{

    protected $connection = 'mysql6';
    protected $table = 'data_kbli';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ID', 'VendorCode', 'NoKbli', 'Description', 'FileKbli', 'created_at', 'CreatedBy', 'updated_at', 'LastModified',
    ];
}
