<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoints extends Model
{
    use HasFactory;

    protected $table = 'user_points';

    protected $fillable = [
        'user_id',
        'points',
        'created_at',
        'updated_at',
    ];
}
