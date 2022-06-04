<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelsMeta extends Model
{
    use HasFactory;
    protected $table = 'model_meta';

    protected $fillable = [
        'id',
        'lang',
        'model_id',
        'title'
    ];
}
