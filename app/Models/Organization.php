<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
