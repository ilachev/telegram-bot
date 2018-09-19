<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $extension
 * @property integer $user_id
 */
class Extension extends Model
{
    protected $table = 'extensions';
    protected $fillable = [
        'user_id', 'extension'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}