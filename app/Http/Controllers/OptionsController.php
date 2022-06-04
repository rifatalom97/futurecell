<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Options;

class OptionsController extends Controller
{
    public static function getOptions( $op_type = 'site_settings', $op_keys=[]  ){
        $options = Options::where('op_type',$op_type);

        if( $op_keys ){
            $options = $options->whereIn('op_key',$op_keys);
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
}
