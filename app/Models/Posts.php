<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'id',
        'thumbnail',
        'slug',
        'status',
        'show_title',
        'created_by',
        'type',
        'created_at',
        'updated_at',
    ];


    /**
     * Relation to PostsMeta
     */
    public function meta(){
        return $this->hasOne(PostsMeta::class, 'post_id')->where('lang',app()->getLocale());
    }
    public function metas(){
        return $this->hasMany(PostsMeta::class, 'post_id');
    }
    public function metaTags(){
        return $this->hasMany(MetaTags::class, 'meta_for_id');
    }
}