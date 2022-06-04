<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    use HasFactory;

    protected $table = 'model';

    protected $fillable = [
        'id',
        'model',
        'brand_id',
        'status'
    ];

    /**
     * Relations
     */
    public function meta(){
        return $this->hasOne(ModelsMeta::class,'model_id')->where('lang',app()->getLocale());
    }
    public function metas(){
        return $this->hasMany(ModelsMeta::class,'model_id');
    }
    public function brand(){
        return $this->hasOne(Brand::class,'id','brand_id');
    }
}
