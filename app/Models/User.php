<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'surname',
        'username',
        'slug',
        'email',
        'password',
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

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->using(TeamUser::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'owner_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
