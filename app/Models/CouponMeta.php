<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponMeta extends Model
{
    use HasFactory;

    protected $table = 'coupons_meta';
    protected $fillable = [
        'id',
        'lang',
        'coupon_id',
        'title',
        'description'
    ];
}
