<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailedResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer',
        'survey_id',
        'response_id',
        'element_id',
        'page_id',
        'choice_id',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }
}
