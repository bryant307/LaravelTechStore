<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'document_type',
        'document_number',
        'email',
        'phone',
        'password',
    ];

    /**
     * 
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * 
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Obtiene los atributos que deben ser convertidos a tipos específicos.
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

    /**
     * Obtiene las direcciones asociadas al usuario.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    
    /**
     * Relación con las reseñas escritas por el usuario
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Verifica si el usuario tiene rol de administrador
     */
    public function isAdmin()
    {
        // Usa la columna is_admin que fue añadida mediante migración
        return $this->is_admin === true || $this->is_admin == 1;
    }
}
