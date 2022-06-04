<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brand';

    protected $fillable = [
        'id',
        'status',
        'slug',
        'image',
        'created_at',
        'updated_at',
    ];



    /**
     * Relation to BrandMeta
     */
    public function meta(){
        return $this->hasOne(BrandMeta::class, 'brand_id')->where('lang',app()->getLocale());
    }
    public function metas(){
        return $this->hasMany(BrandMeta::class, 'brand_id');
    }
    /**
     * Relation with metaTags
     */
    public function metaTag(){
        return $this->hasOne(MetaTags::class, 'meta_for_id','id')->where('meta_for','brand')->where('lang',app()->getLocale());
    }
    public function metaTags(){
        return $this->hasMany(MetaTags::class, 'meta_for_id','id')->where('meta_for','brand');
    }
}
