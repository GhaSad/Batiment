<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['username', 'email', 'password', 'role'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function devices(){
        return $this->hasMany(Device::class);
    }

    public function userActions(){
        return $this->hasMany(UsersAction::class);
    }

    public function logs(){
        return $this->hasMany(Logs::class);
    }

    public function home(){
        return $this->belongsTo(Home::class);
    }
}
