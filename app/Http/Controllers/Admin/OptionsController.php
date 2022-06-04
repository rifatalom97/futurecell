<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Options;

class OptionsController extends Controller
{
    public function add_option( $op_type, $op_key, $op_value, $lang=NULL ){
        return Options::create([
            'op_type'   => $op_type,
            'op_key'    => $op_key,
            'op_value'  => $op_value,
            'lang'      => $lang
        ]);
    }

    public function update_option( $op_type, $op_key, $op_value, $lang=NULL ){
        return Options::where('op_type',$op_type)
                        ->where('op_key',$op_key)
                        ->where('lang',$lang)
                        ->update(['op_value'=>$op_value]);
    }

    public function delete_option( $op_type, $op_key=false, $lang=NULL ){
        $options = Options::where('op_type',$op_type);
        
        if( $op_key ){
            $options = $options->where('op_key',$op_key);

            if($lang){
                $options = $options->where('lang',$lang);
            }
        }

        return $options->delete();
    }

    public function get_option( $op_type, $op_key, $lang=NULL ){

        return Options::where('op_type',$op_type)
                    ->where('op_key',$op_key)
                    ->where('lang',$lang)
                    ->select('op_value')
                    ->first();
    }

    public function get_options( $op_type, $op_key=false, $lang=NULL ){
        $options = Options::where('op_type',$op_type);
        
        if( $op_key ){
            $options = $options->where('op_key',$op_key);

            if($lang){
                $options = $options->where('lang',$lang);
            }
        }
        return $options->get();
    }




    public static function getOptions( $op_type = 'site_settings', $op_keys=[], $lang = ''  ){
        $options = Options::where('op_type',$op_type);

        if( $op_keys ){
            $options = $options->whereIn('op_key',$op_keys);
        }
        if( $lang ){
            $options = $options->where('lang',$lang);
        }

        $options = $options->select('op_key','op_value');

        $result = $options->get();

        $filterd_data = [];
        if($result){
            foreach($result as $item){
                $filterd_data[ $item->op_key ] = json_decode($item->op_value);
            }
        }
        return $filterd_data;
    }
    public static function saveOptions( $options, $op_type = 'site_settings' ){
        if(count($options)){
            foreach($options as $key => $value){
                Options::updateOrCreate( 
                    ['op_type'=> $op_type,'op_key'=> $key], 
                    ['op_type'=> $op_type,'op_key'=> $key,'op_value'=> $value]
                );
            }
        }
    }
    public static function removeOption( $op_type, $op_key=false, $lang=NULL ){
        $options = Options::where('op_type',$op_type);
        
        if( $op_key ){
            $options = $options->where('op_key',$op_key);
            if($lang){
                $options = $options->where('lang',$lang);
            }
        }

        return $options->delete();
    }
}