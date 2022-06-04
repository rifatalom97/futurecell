<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable = [
        'id',
        'slug',
        'parent',
        'status',
        'image',
        'created_at',
        'updated_at',
    ];


    /**
     * Relation with CategoryMeta
     */
    public function meta(){
        return $this->hasOne(CategoryMeta::class,'category_id')->where('lang',app()->getLocale());
    }
    public function metas(){
        return $this->hasMany(CategoryMeta::class,'category_id');
    }
    public function children(){
        return $this->hasMany(Category::class,'parent','id');
    }
    
    public function products(){
        return $this->hasMany(ProductCategory::class,'category_id','id');
    }
}
