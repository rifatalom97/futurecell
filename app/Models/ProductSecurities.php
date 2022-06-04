<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSecurities extends Model
{
    use HasFactory;
    protected $table = 'securities';

    protected $fillable = array(
        'image',
        'status',
        'created_at',
        'updated_at',
    );
}
