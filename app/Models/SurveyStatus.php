<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
}
