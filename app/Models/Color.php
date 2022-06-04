<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'color';
    protected $fillable = [
        'id',
        'code',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Relation to ColorMeta
     */
    public function meta(){
        return $this->hasOne(ColorMeta::class,'color_id')->where('lang',app()->getLocale());
    }
    public function metas(){
        return $this->hasMany(ColorMeta::class,'color_id');
    }
}
