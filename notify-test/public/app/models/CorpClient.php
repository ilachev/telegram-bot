<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 08.11.18
 * Time: 12:01
 */

namespace Pcs\Bot\Models;

use Illuminate\Database\Eloquent\Model;

class CorpClient extends Model
{
    protected $table = 'ast_clients_corp';
    protected $connection = 'corpclients';
}