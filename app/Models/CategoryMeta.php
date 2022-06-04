<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMeta extends Model
{
    use HasFactory;
    protected $table = 'category_meta';
    protected $fillable = [
        'id',
        'lang',
        'category_id',
        'title'
    ];
}
