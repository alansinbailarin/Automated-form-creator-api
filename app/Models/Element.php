<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'order',
        'visible',
        'required',
        'multiple_choice',
        'page_id',
        'element_type_id',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function elementType()
    {
        return $this->belongsTo(ElementType::class);
    }

    public function detailedResponses()
    {
        return $this->hasMany(DetailedResponse::class);
    }
}
