<?php

namespace App\Models\View\Qrgad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwTrip extends Model
{
    use HasFactory;
    protected $connection = "mysql9";
    protected $table = 'vw_trips';
}
