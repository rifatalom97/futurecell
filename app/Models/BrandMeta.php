<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandMeta extends Model
{
    use HasFactory;

    protected $table = 'brand_meta';

    protected $fillable = [
        'brand_id',
        'lang',
        'title',
    ];
}
