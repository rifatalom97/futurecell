<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryMeta extends Model
{
    use HasFactory;
    protected $table = 'countries_meta';

    protected $fillable = [
        'id',
        'country_id',
        'lang',
        'name',
        'created_at',
        'updated_at',
    ];
}
