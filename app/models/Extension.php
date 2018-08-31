<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    protected $table = 'extensions';
    protected $fillable = [
        'user_id', 'extension'
    ];

    /**
     * Получить пользователя, с данным добавочным
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}