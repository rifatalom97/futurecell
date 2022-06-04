<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'user_shipping_address';

    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'email',
        'company',
        'address',
        'country',
        'city',
        'state',
        'zip_code',
    ];
}
