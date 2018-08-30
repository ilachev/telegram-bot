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
class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['uid', 'name', 'phone', 'updated_at'];
}