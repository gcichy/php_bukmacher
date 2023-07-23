<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     *
     */

    protected $fillable = [
        'name', 'surname', 'nickname', 'email', 'password',
        'phone_number', 'person_number', 'deposit', 'premium', 'confirmed'
    ];
    protected $table = 'users';
    public function key(): string
    {
        return 'model_user_'.$this->id;
    }
}
