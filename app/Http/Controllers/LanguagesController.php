<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Languages extends Controller
{
    public static function languages(){
        $languages = array(
            'he' => array('title'=>'Hebrew')
        );

        return $languages;
    }
    public static function CurrentLang(){
        return 'he';
    }
}
