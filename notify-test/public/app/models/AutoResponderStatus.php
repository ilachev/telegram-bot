<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $status
 * @property integer $user_id
 */
class AutoResponderStatus extends Model
{
    protected $table = 'auto_responder_statuses';
    protected $fillable = [
        'user_id', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}