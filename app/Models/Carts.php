<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    /**
     * relation
     */
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
