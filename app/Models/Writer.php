<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Client
 * @property integer $field_id
 * @property string $email
 * @property string $password
 * @package App\Models
 */
class Writer extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected $primaryKey = 'field_id';
    protected $table = 'writers';
    protected $guarded = [
        'field_id'
    ];

    // this is method from Laravel Passport
    public function findAndValidateForPassport ($username, $password)
    {
        return $this::where(['email' => $username])
            ->where(['password' => $password])
            ->first();
    }

    public function statistics()
    {
        return $this->hasOne('App\Models\WriterStatistics', 'sw_id', 'sw_id');
    }
}
