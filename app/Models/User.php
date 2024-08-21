<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    protected $fillable = ['first_name','last_name','email','password','mobile','role_ID','person_ID','created_at',];

    public static function addUser($data)
    {
        $insertUser = User::create([
            'first_name' => ucfirst(trim($data['first_name'])),
            'last_name' => ucfirst(trim($data['last_name'])),
            'email' => trim($data['email']),
            'password' => Hash::make('12345678'),
            'mobile' => trim($data['mobile']),
            'role_ID' => $data['role'],
            'person_ID' => $data['person_ID'],
            'created_at' => now(),
        ]);

        return (!empty($insertUser)) ? $insertUser->id : '';
    }
}
