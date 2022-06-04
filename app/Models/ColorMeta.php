<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorMeta extends Model
{
    use HasFactory;

    protected $table = 'color_meta';
    protected $fillable = [
        'lang',
        'color_id',
        'title',
    ];
}
