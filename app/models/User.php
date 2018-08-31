<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $uid
 * @property string $name
 * @property string $phone
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'chat_id', 'username', 'phone', 'full_name', 'status'
    ];

    /**
     * Получить добавочный пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function extension()
    {
        return $this->hasOne(Extension::class);
    }
}