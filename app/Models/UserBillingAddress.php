<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBillingAddress extends Model
{
    use HasFactory;
    protected $table = 'user_billing_address';

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
