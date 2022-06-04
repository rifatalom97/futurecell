<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class ProductRelationController extends Controller
{
    public static function insertUpdateRelations( $product_id, $data = array() ){
        if($data){
            foreach($data as $key => $values )
            {
                DB::table($key)->where('product_id',$product_id)->delete();
                if($values){
                    foreach($values as $value){
                        DB::table($key)->insert(
                            array(
                                'product_id'                            => (int)$product_id,
                                str_replace('product_','',$key).'_id'   => (int)$value,
                            )
                        );
                    }
                }
            }
        }
    }


    public static function getRelations( $product_id ){
        $categories = DB::table('product_category')->where('product_id',$product_id)->select('category_id')->get();
        $sizes      = DB::table('product_size')->where('product_id',$product_id)->select('size_id')->get();
        $colors     = DB::table('product_color')->where('product_id',$product_id)->select('color_id')->get();

        return array( 
            'selectedCategories'=> self::getFilterdRelations('category_id', $categories),
            'selectedSizes'     => self::getFilterdRelations('size_id', $sizes),
            'selectedColors'    => self::getFilterdRelations('color_id', $colors)
        );
    }

    private static function getFilterdRelations( $key, $values ){
        
        $filterd_data = [];
        if($values){
            foreach($values as $val){
                $filterd_data[] = $val->$key; 
            }
        }

        return $filterd_data;
    }


    public static function deleteRelations( $product_id ){
        $categories = DB::table('product_category')->where('product_id',$product_id)->delete();
        $sizes      = DB::table('product_size')->where('product_id',$product_id)->delete();
        $colors     = DB::table('product_color')->where('product_id',$product_id)->delete();

        return true;
    }
}
