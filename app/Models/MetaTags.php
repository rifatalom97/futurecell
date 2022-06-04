<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaTags extends Model
{
    use HasFactory;

    protected $table = 'meta_tags';

    protected $fillable = [
        'id',
        'lang',
        'metaTitle',
        'metaKeywords',
        'metaDescription',
        'meta_for',
        'meta_for_id',
        'created_at',
        'updated_at'
    ];
}
