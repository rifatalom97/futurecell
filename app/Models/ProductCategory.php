<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table    ="product_category";
    protected $fillable = [
        'product_id',
        'category_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Relation
     */
    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
