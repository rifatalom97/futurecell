<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $table = 'size';
    protected $fillable = [
        'id',
        'unite',
        'value',
        'status',
        'created_at',
        'updated_at'
    ];
}
