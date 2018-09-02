<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $session_id
 * @property integer $status
 */
class Session extends Model
{
    protected $table = 'sessions';
    protected $fillable = [
        'session_id', 'status'
    ];
}