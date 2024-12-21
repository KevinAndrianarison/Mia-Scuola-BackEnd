<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status_user',
        'email',
        'password',
        'photo_name',
        'validiter_compte',
    ];

    public function directeur()
    {
        return $this->hasMany(Directeur::class);
    }
    public function agentscolarite()
    {
        return $this->hasMany(Agentscolarite::class);
    }
    public function enseignant()
    {
        return $this->hasMany(Enseignant::class);
    }
    public function etudiant()
    {
        return $this->hasMany(Etudiant::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function annonce()
    {
        return $this->hasMany(Annonce::class);
    }
    public function com()
    {
        return $this->hasMany(Com::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
