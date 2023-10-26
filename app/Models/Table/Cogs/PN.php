<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class PN extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'part_number';
  protected $fillable = [
      'ID',
      'PartNumber',
      'Description',
      'Category',
    ];
}
