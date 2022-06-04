<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    use HasFactory;

    protected $table = 'subscribers';
    protected $fillable = [
        'user_id',
        'visitor_id',
        'email',
        'status',
        'created_at',
        'updated_at',
    ];


    /**
     * Relation
     */
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function visitor(){
        return $this->hasOne(Visitors::class,'id','visitor_id');
    }
}
