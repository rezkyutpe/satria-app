<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'weight';
  protected $fillable = [
      'No',
      'PN',
      'Description',
      'Weight',
      'Category'
    ];
}
