<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'cart_total_amount',
        'total_amount',
        'currency_code',
        'currency_sign',
        'coupon_id',
        'delivery_option_id',
        'shipping_address',
        'billing_address',
        'transaction_id',
        'canceled_by',
        'canceled_on',
        'admin_view',
        'order_status',
        'payment_status',
        'created_at',
        'updated_at',
    ];

    /**
     * Relation to user
     */
    public function customer(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    /**
     * Relations to OrderProducts
     */
    public function orderProducts(){
        return $this->hasMany(OrderProducts::class,'order_id');
    }
}
