<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'internal_entity_id',
        'phone',
        'work_status',
        'is_active',
    ];

    public function internal_entity()
    {
        return $this->belongsTo(InternalEntity::class, 'internal_entity_id');
    }

    public function geographicScopes()
    {
        return $this->hasMany(UserGeographicScope::class, 'user_id');
    }

    /**
     * Accessor for administrative_scope_id (alias for internal_entity_id)
     */
    public function getAdministrativeScopeIdAttribute()
    {
        return $this->internal_entity_id;
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];
}
