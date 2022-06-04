<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $fillable = [
        'id',
        'coupon_code',
        'start_date',
        'expire_date',
        'discount_type',
        'discount',
        'coupon_for',
        'coupon_for_id',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * relation
     */
    public function meta(){
        return $this->hasOne(CouponMeta::class,'coupon_id')->where('lang',app()->getLocale());
    }
}