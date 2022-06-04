<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class BrandController extends Controller
{
    public function getProductBrandBySlug( Request $request ){
        $slug = $request->slug;

        $result = DB::table('brand')->where('slug',$slug)->select('brand.id','brand.slug')->get()->count();
        
        return response()->json([
            'result' => (bool)$result
        ]);
    }
}
