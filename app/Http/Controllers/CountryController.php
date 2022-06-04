<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class CountryController extends Controller
{
    public static function getCountries( $lang ){

        $result = DB::table('countries')
                    ->join('countries_meta','countries_meta.country_id','countries.id')
                    ->where('countries_meta.lang',$lang)
                    ->select('countries.id','countries.id as value','countries.code','countries.status','countries.is_default','countries_meta.name','countries_meta.name as label')
                    ->orderBy('countries.is_default','ASC')
                    ->orderBy('countries_meta.name','ASC')
                    ->get();

        return $result;
    }
    public static function getFilterdCountries( $lang ){
        $countries = self::getCountries( $lang );

        if($countries){
            $filterd_country = [];
            foreach($countries as $country){
                $filterd_country[] = array(
                    'id'            => $country->id,
                    'value'         => $country->value,
                    'label'         => $country->label,
                    'is_default'    => $country->is_default,
                    'code'          => $country->code,
                );
            }

            return $filterd_country;
        }
    }
}
