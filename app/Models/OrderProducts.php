<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;

    protected $table    ="order_products";
    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'unite_price',
        'total_amount',
        'created_at',
        'updated_at',
    ];


    /**
     * Relation to product
     */
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    /**
     * Relation to color
     */
    public function color(){
        return $this->hasOne( Color::class, 'id', 'color_id' );
    }
    /**
     * Relation to size
     */
    public function size(){
        return $this->hasOne(Size::class,'id','size_id');
    }
}
