<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsMeta extends Model
{
    use HasFactory;
    protected $table = 'posts_meta';
    protected $fillable = [
        'post_id',
        'lang',
        'title',
        'sub_title',
        'content',
        'created_at',
        'updated_at',
    ];
}
