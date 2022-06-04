<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OptionsController; 

use App\Models\ProductSecurities;

class HeaderFooter extends Controller
{
    // All page header footer data
    public static function get( $meta_for = 'default_settings', $replace_field = array() ){

        $metaTags = MetaTagsController::get_page_meta_tags( $meta_for, $replace_field );

        $default    = OptionsController::getOptions('default_settings');
        $header     = OptionsController::getOptions('header_settings');
        $carts      = CartController::get_cart_items();
        $footer     = OptionsController::getOptions('footer_settings');

        // Filter footer securities
        if(isset($footer['securities']) && $footer['securities']->show=='true'){
            $footer['securities']->items = ProductSecurities::where('status',1)->get();
        }// End

        return array(
            'default'   => $default,
            'metaTags'  => $metaTags,
            'header'    => $header,
            'carts'     => $carts,
            'footer'    => $footer
        );
    }
    // End page header footer data
}
