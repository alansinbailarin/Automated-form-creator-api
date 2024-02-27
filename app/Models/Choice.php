<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'text',
        'value',
        'element_id',
    ];

    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function detailedResponses()
    {
        return $this->hasMany(DetailedResponse::class);
    }
}
