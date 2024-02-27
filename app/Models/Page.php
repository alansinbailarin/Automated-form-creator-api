<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'order',
        'number',
        'visible',
        'survey_id',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function elements()
    {
        return $this->hasMany(Element::class);
    }

    public function detailedResponses()
    {
        return $this->hasMany(DetailedResponse::class);
    }
}
