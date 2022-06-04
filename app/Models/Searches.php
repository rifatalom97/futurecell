<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Searches extends Model
{
    use HasFactory;

    protected $fillable = [
        'keywords',
        'user_id',
        'visitor_id',
        'created_at',
        'updated_at',
    ];
}
