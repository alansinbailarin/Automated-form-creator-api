<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseJson extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'data_json',
        'response_id',
    ];

    public function response()
    {
        return $this->belongsTo(Response::class);
    }
}
