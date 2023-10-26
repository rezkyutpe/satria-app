<?php

namespace App\Model\Table;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Role
 *
 * @author RafiZendaris
 */
class ResetPasswordToken extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $table = 'reset_password_token';

    protected $fillable = [
        'id',
        'email',
        'token',
        'expired_at',
        'created_at',
        'updated_at',
    ];

}
