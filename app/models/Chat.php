<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $chat_id
 * @property integer $user_id
 */
class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = [
        'user_id', 'chat_id'
    ];

    /**
     * Получить пользователя, с данным chat_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}