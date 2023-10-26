<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class CpoAttachment extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cpo_attachment';
  protected $fillable = [
      'OrderID', 
      'AttachmentID', 
      'AttachmentName',
      'AttachmentFile'
    ];
}
