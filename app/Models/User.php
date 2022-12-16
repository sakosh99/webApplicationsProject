<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'user_name',
        'password',
        'role',
        'subscription_plan_id'
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
    ];

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
        return [];
    }
    public function groups()
    {
        return $this->hasMany(Group::class, 'publisher_id');
    }
    public function files()
    {
        return $this->hasMany(File::class, 'publisher_id');
    }
    public function reports()
    {
        return $this->hasMany(FileReport::class);
    }
    /**
     * The roles that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inGroups()
    {
        return $this->belongsToMany(
            Group::class,
            'users_groups',
            'user_id',
            'group_id'
        );
    }

    public function getStorageGroup()
    {
        return $this->hasOne(Group::class, 'publisher_id')
            ->where('publisher_id', Auth::user()->id)
            ->where('group_name', Auth::user()->user_name . '_storage');
    }
    public function subscriptionPLan()
    {
        return $this->belongsTo(SubscriptionPLan::class, 'subscription_plan_id');
    }
}
