<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    use HasFactory;
    protected $table    ="products_meta";
    protected $fillable = [
        'lang',
        'product_id',
        'title',
        'short_description',
        'description',
        'created_at',
        'updated_at',
    ];
}