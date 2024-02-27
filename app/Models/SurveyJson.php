<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyJson extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'data_json',
        'survey_id',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
