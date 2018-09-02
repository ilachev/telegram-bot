<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $redirect
 * @property integer $user_id
 */
class Redirect extends Model
{
    protected $table = 'redirects';
    protected $fillable = [
        'user_id', 'redirect'
    ];
}