<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Client
 * @property integer $id
 * @property string $email
 * @property string $password
 * @package App\Models
 */
class Writer extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected $table = 'writers';
    protected $guarded = [
        'id'
    ];

}
