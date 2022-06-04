<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table    ="products";
    protected $fillable = [
        'slug',
        'model_number',
        'sku',
        'barcode',
        'currency',
        'price',
        'regular_price',
        'quantity',
        'total_sale',
        'brand',
        'model',
        'thumbnail',
        'gallery',
        'options',
        'status',
        'created_at',
        'updated_at',
    ];


    /**
     * Relation with ProductMeta
     */
    public function meta(){
        return $this->hasOne(ProductMeta::class,'product_id')->where('lang',app()->getLocale());
    }
    public function metas(){
        return $this->hasMany(ProductMeta::class,'product_id');
    }

    public function sizes(){
        return $this->hasMany(ProductSize::class,'product_id');
    }
    public function colors(){
        return $this->hasMany(ProductColor::class,'product_id');
    }
    public function categories(){
        return $this->hasMany(ProductCategory::class,'product_id');
    }

    public function cart(){
        $user_id = isset(auth()->user()->id)?auth()->user()->id:0;
        return $this->hasOne(Carts::class,'product_id')
                ->where('session_id',session()->getid())
                ->orWhere('user_id', $user_id);
    }
}
