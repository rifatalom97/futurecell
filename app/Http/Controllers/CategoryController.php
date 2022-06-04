<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    public function getProductCategoryBySlug( Request $request ){
        $slug = $request->slug;

        $result = DB::table('category')->where('slug',$slug)->select('category.id','category.slug')->get()->count();
        
        return response()->json([
            'result' => (bool)$result
        ]);
    }
}
