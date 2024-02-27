<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function elements()
    {
        return $this->hasMany(Element::class);
    }
}
