<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\File; // Assurez-vous d'importer le modèle File s'il est dans un autre namespace

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;


    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
