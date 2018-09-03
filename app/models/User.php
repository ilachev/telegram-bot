<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $extension
 * @property string $phone
 * @property string $full_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'extension', 'phone', 'full_name'
    ];

    /**
     * Получить chat_id пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function redirect()
    {
        return $this->hasOne(Redirect::class);
    }
}