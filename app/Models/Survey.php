<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'slug',
        'owner_id',
        'survey_status_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function surveyJson()
    {
        return $this->hasOne(SurveyJson::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function surveyStatus()
    {
        return $this->belongsTo(SurveyStatus::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function detailedResponses()
    {
        return $this->hasMany(DetailedResponse::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
