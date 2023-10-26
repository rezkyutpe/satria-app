<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class ApcrAttachment extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'apcr_attachment';
  protected $fillable = [
      'PCRID', 
      'AttachmentID', 
      'AttachmentName',
      'AttachmentFile'
    ];
}
