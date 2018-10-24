<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $country
 * @property string $mapping
 */
class Mapping extends Model
{
    protected $table = 'mappings';
    protected $fillable = [
        'country', 'mapping'
    ];
}