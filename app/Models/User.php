<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'telefon',
        'password',
        'activ'
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function path()
    {
        return "/utilizatori/{$this->id}";
    }

    /**
     * Get all of the roles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles(): HasMany
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    // Verificare daca userul are anumite drepturi
    public function hasRole($roleNume): bool
    {
        return $this->roles()
            ->where('nume', $roleNume)
            ->exists();
    }

    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()
            ->whereIn('nume', $roleNames)
            ->exists();
    }

    public function numarEmailuriFisaCaz($fisaCazId)
    {
        return MesajeTrimiseEmail::where('referinta_id', $fisaCazId)->where('referinta2_id', $this->id)->count();
    }

    public function bonusuri(): HasMany
    {
        return $this->hasMany(Bonus::class, 'user_id');
    }
}
