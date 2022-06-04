<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visitor_id',
        'name',
        'email',
        'mobile',
        'subject',
        'message',
        'adminView',
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
        return $this->hasOne(Visitors::class,'id','user_id');
    }
}
