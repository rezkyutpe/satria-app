<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

  protected $table = 'attendance';

  protected $fillable = [
    'id', 'remote_addr_in', 'longitude_in', 'latitude_in', 'address_in', 'subcont', 'client', 'in_time', 'out_time', 'revice_in_time', 'revice_out_time', 'remote_addr_out', 'longitude_out', 'latitude_out', 'address_out', 'work_metode', 'foto_in', 'foto_out', 'note', 'checked_by', 'checked_at', 'reject_reason', 'is_actived', 'flag', 'created_by'
  ];
}
