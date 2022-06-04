<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Subscribers;

class SubscribersController extends Controller
{
    public function checkSubscribedEmail(Request $request){
        $email      = $request->email;
        $user_id    = auth()->user()?auth()->user()->id:0;

        $subscriber = Subscribers::where('email',$email)->first();
        
        if($subscriber){
            return response()->json(['result'=>200]);
        }else{
            return response()->json(['result'=>404]);
        }
    }

    public function subscribe(Request $request){
        $email      = $request->email;
        $user_id    = auth()->user()?auth()->user()->id:0;

        $attr = $request->validate([
            'email'         => 'required|string|email|unique:subscribers,email',
        ]);

        $result = Subscribers::create(['email'=>$email,'user_id'=>$user_id]);
        if($result){
            return response()->json(['result'=>200]);
        }else{
            return response()->json(['result'=>500]);
        }
    }
}
