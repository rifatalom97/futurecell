<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDeliveryOptionsMeta extends Model
{
    use HasFactory;

    protected $table = 'delivery_options_meta';
    protected $fillable = [
        'id',
        'delivery_options_id',
        'lang',
        'title',
        'created_at',
        'updated_at',
    ];
}
