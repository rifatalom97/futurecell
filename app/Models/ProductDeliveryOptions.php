<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDeliveryOptions extends Model
{
    use HasFactory;

    protected $table = 'delivery_options';
    protected $fillable = [
        'id',
        'amount',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Relation
     */
    public function meta(){
        return $this->hasOne(ProductDeliveryOptionsMeta::class,'delivery_options_id');
    }
    public function metas(){
        return $this->hasMany(ProductDeliveryOptionsMeta::class,'delivery_options_id');
    }
}
