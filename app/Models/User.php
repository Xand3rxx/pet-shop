<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\GenerateUUID;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, GenerateUUID;

    /**
     * Default user roles.
     */
    const ROLE = [
        'Admin' => 1,
        'User'  => 0,
    ];

    /**
     * Default marketer roles.
     */
    const MARKETER_ROLE = [
        'Marketer'  => 1,
        'User'      => 0,
    ];

    /**
     * To support mass assignable attributes from a validated request.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['file'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user_uuid' => $this->uuid
        ];
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->firstOrFail();
    }

    /**
     * Get the file associated with the user.
     */
    public function file()
    {
        return $this->hasOne(File::class, 'uuid', 'avatar');
    }

    /**
     * Scope a query to only basic users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBasicUsers($query)
    {
        return $query->select('*')
            ->where('is_admin', self::ROLE['User']);
    }
}
