<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{

  protected $table = 'user_detail';

  protected $fillable = [
    'id', 'user_id','nrp','name', 'email', 'marital_status', 'birth_date', 'address', 'plant', 'join_date', 'end_date', 'status', 'klasifikasi', 'vendor','created_by'
  ];
}
