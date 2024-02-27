<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'organization_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->using(TeamUser::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
