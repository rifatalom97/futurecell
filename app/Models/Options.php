<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_type',
        'op_key',
        'lang',
        'op_value',
        'created_at',
        'updated_at',
    ];
}
