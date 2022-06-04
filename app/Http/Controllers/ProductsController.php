<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\OptionsController;
use App\Http\Controllers\HeaderFooter;

use App\Models\Searches;
use App\Models\Product;
use App\Models\ProductSecurities;
use App\Models\ProductDeliveryOptions;
use App\Models\Color;
use App\Models\Size;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Category;
use DB;

class ProductsController extends Controller
{
    /**
     * Shop page
     */
    public function store(Request $request){

        $page_data      = OptionsController::getOptions( 'store_page' );

        $header_footer  = HeaderFooter::get('store_page');

        $products       = $this->product_sql_query($request)->limit(12)->get();
        $total_count    = $this->product_sql_query($request)->count();


        // dependency
        $colors     = Color::join('product_color','product_color.color_id','=','color.id')->groupBy('color.id')->select('color.*')->get();
        $sizes      = Size::join('product_size','product_size.size_id','=','size.id')->groupBy('size.id')->select('size.*')->get();
        $brands     = Brand::join('products','products.brand','=','brand.id')->join('brand_meta','brand_meta.brand_id','=','brand.id')->groupBy('brand.id')->select('brand_meta.*','brand.*')->get();
        $models     = Models::join('products','products.model','=','model.id')->join('model_meta','model_meta.model_id','=','model.id')->groupBy('model.id')->select('model_meta.*','model.*')->get();
        $categories = Category::join('product_category','product_category.category_id','=','category.id')->join('category_meta','category_meta.category_id','=','category.id')->groupBy('category.id')->select('category_meta.*','category.*')->get();

        // Selected
        $selected_colors        = exploded_flits($request,'color');
        $selected_sizes         = exploded_flits($request,'size');
        $selected_brands        = exploded_flits($request,'brand');
        $selected_models        = exploded_flits($request,'model');
        $selected_categories    = exploded_flits($request,'category');

        return view('store.store')
                ->with('header_footer',$header_footer)
                ->with('page_data',$page_data)
                ->with('colors',$colors)
                ->with('sizes',$sizes)
                ->with('brands',$brands)
                ->with('models',$models)
                ->with('categories',$categories)
                ->with('selected_colors',$selected_colors)
                ->with('selected_sizes',$selected_sizes)
                ->with('selected_brands',$selected_brands)
                ->with('selected_models',$selected_models)
                ->with('selected_categories',$selected_categories)
                ->with('products',$products)
                ->with('total_count',$total_count);
    }
    
    public function get_ajax_products(Request $request){
        // offset 
        $offset     = $request->input('offset');

        $products   = $this->product_sql_query($request)->skip((int)$offset)->limit(12)->get();
        $total_count   = $this->product_sql_query($request)->count();

        if($products && count($products)){
            $html = '';
            foreach($products as $product){
                $html .='<div class="col-lg-3 col-md-4 col-xs-6 mb-4">';
                $html .='<div class="each_product text-center">';
                $html .='<div class="each_product_head text-center">';
                $html .='<img src="'.asset('uploads/'.$product->thumbnail).'" height="294" alt="'.$product->title.'" />';
                $html .='</div>';
                $html .='<div class="each_product_details">';
                $html .='<h4 class="epd_title">'.$product->title.'</h4>';
                $html .='<p class="epd_price">';
                if($product->regular_price){
                $html .='<span class="epd_regular_price"><del><span>$</span>'.$product->regular_price.'</del></span>';
                }
                $html .='<span class="epd_sell_price"><span>$</span>'.$product->price.'</span>';
                $html .='</p>';
                $html .='</div>';
                $html .='<div class="each_product_footer">';
                $html .='<a class="btn btn-yellow btn-big add_to_cart" href="'.url('store/'.$product->slug).'">לפרטים נוספים ורכישה</a>';
                $html .='</div>';
                $html .='</div>';
                $html .='</div>';
            }
            return response()->json(['data'=>$html,'offset'=>$offset+12,'total_count'=>$total_count]);
        }
    }
    private function product_sql_query($request){
        // Search 
        $search     = $request->input('search');
        $sort_by    = $request->input('sort_by');

        $selected_colors        = exploded_flits($request,'color');
        $selected_sizes         = exploded_flits($request,'size');
        $selected_brands        = exploded_flits($request,'brand');
        $selected_models        = exploded_flits($request,'model');
        $selected_categories    = exploded_flits($request,'category');

        $query  = Product::join('products_meta','products_meta.product_id','=','products.id')
                            ->where('products_meta.lang',app()->getLocale());
        if( $search ){
            $query = $query->where('products_meta.title','LIKE','%'.$search.'%');
        }
        

        // Filters
        if(count($selected_colors)){
            $query = $query->join('product_color','product_color.product_id','=','products.id')
                            ->join('color','color.id','=','product_color.color_id');
            $i = 0;
            foreach($selected_colors as $color){
                if($i==0){
                    $query = $query->where('color.code',$color);
                }else{
                    $query = $query->orWhere('color.code',$color);
                }
                $i++;
            }
        }
        if(count($selected_sizes)){
            $query = $query->join('product_size','product_size.product_id','=','products.id')
                            ->join('size','size.id','=','product_size.size_id');
            $i = 0;
            foreach($selected_sizes as $size){
                $flt_size = explode('-',$size);
                if(count($flt_size) !== 2){
                    continue;
                }
                if($i==0){
                    $query = $query->where('size.value',$flt_size[0])->where('size.unite',$flt_size[1]);
                }else{
                    $query = $query->orWhere('size.value',$flt_size[0])->where('size.unite',$flt_size[1]);
                }
                $i++;
            }
        }
        if(count($selected_brands)){
            $query = $query->join('brand_meta','brand_meta.brand_id','=','products.brand');
            $i = 0;
            foreach($selected_brands as $brand){
                if($i==0){
                    $query = $query->where('brand_meta.title',$brand);
                }else{
                    $query = $query->orWhere('brand_meta.title',$brand);
                }
                $i++;
            }
        }
        if(count($selected_models)){
            $query = $query->join('model','model.id','=','products.model');
            $i = 0;
            foreach($selected_models as $model){
                if($i==0){
                    $query = $query->where('model.model',$model);
                }else{
                    $query = $query->orWhere('model.model',$model);
                }
                $i++;
            }
        }
        if(count($selected_categories)){
            $query = $query->join('product_category','product_category.product_id','=','products.id')
                            ->join('category','category.id','=','product_category.category_id');
            $i = 0;
            foreach($selected_categories as $category){
                if($i==0){
                    $query = $query->where('category.slug',$category);
                }else{
                    $query = $query->orWhere('category.slug',$category);
                }
                $i++;
            }
        }
        // end

        
        $query = $query->select('products_meta.*','products.*');
        if($sort_by){
            if($sort_by=='A-to-Z'){
                $query = $query->orderBy('products_meta.title', 'asc');
            }elseif($sort_by=='Z-to-A'){
                $query = $query->orderBy('products_meta.title', 'desc');
            }elseif($sort_by=='Low-to-High'){
                $query = $query->orderBy('products.price', 'asc');
            }elseif($sort_by=='High-to-Low'){
                $query = $query->orderBy('products.price', 'desc');
            }else{
                $query = $query->orderBy('products.id', 'desc');
            }
        }

        return $query;
    }


    /**
     * Single product
     */
    public function product(Request $request){

        $header_footer = HeaderFooter::get('store_page');

        $product            = Product::with(array('meta','cart'))->where('slug',$request->slug)->first();
        $securities         = ProductSecurities::where('status',1)->get();
        $delivery_methods   = ProductDeliveryOptions::with(['meta'])->where('status',1)->get();

        $options            = OptionsController::getOptions('products_settings');
        $credit_info        = isset($options['creditInfo'])?$options['creditInfo']:NULL;

        return view('store.single')
                ->with('header_footer',$header_footer)
                ->with('securities',$securities)
                ->with('credit_info',$credit_info)
                ->with('delivery_methods',$delivery_methods)
                ->with('product',$product);
    }



    public function getMiniSearchResult( Request $request ){
        $keywords   = $request->keywords;
        $lang       = $request->lang?:'he';
        
        $result =   DB::table('products')
                    ->join('products_meta',function($join)use($lang, $keywords){
                        $join = $join->on('products.id','products_meta.product_id')
                            ->where('products_meta.lang',$lang)
                            ->where('products_meta.title','LIKE','%'.$keywords.'%');
                    })
                    ->where('products.status',1)
                    ->get();

        return response()->json( ($keywords?$result:array()) );
    }
    



    /**
     * Fetch delivery options
     */
    public function fetchDeliveryOptions(Request $request){
        $lang = $request->lang;
        $filterd_result = [];
        $result = DB::table('delivery_options')
                    ->join('delivery_options_meta',function($join)use($lang){
                        $join->on('delivery_options.id','delivery_options_meta.delivery_options_id')
                                ->where('delivery_options_meta.lang',$lang);
                    })->select('delivery_options.id','delivery_options.amount','delivery_options_meta.title')
                    ->where('delivery_options.status',1)
                    ->get();
        if($result){
            foreach($result as $item){
                $filterd_item = [
                    'id' => $item->id,
                    'value' => $item->id,
                    'label' => $item->title,
                ];
                $item->value = $item->id;
                $item->label = $item->title;
                $filterd_result[] = $item;
            }
        }
        return response()->json( $filterd_result );
    }
    /**
     * Fetch delivery option
     */
    public function fetchDeliveryOption(Request $request){
        $lang   = $request->lang;
        $id     = $request->id;
        
        $result = DB::table('delivery_options')
                    ->join('delivery_options_meta',function($join)use($lang){
                        $join->on('delivery_options.id','delivery_options_meta.delivery_options_id')
                                ->where('delivery_options_meta.lang',$lang);
                    })->select('delivery_options.id','delivery_options.amount','delivery_options_meta.title')
                    ->where('delivery_options.status',1)
                    ->where('delivery_options.id',$id)
                    ->first();

        return response()->json( $result );
    }

    /**
     * Fetch coupon
     */
    public function fetchCoupon(Request $request){
        $lang   = $request->lang;
        $coupon     = $request->coupon;

        $result = DB::table('coupons')->where('couponCode',$coupon)
                ->join('coupons_meta',function($join)use($lang){
                    $join->on('coupons_meta.coupon_id','coupons.id')
                    ->where('coupons_meta.lang',$lang);
                })
                ->where('coupons.status',1)
                ->select('coupons.*','coupons_meta.title')
                ->first();
        if(!$result){
            $result = ['error'=>404];
        }else{
            $startDate  = $result->startDate;
            $expireDate = $result->expireDate;
            if( time() >= strtotime($startDate) && strtotime($startDate) >= time() ){
                $result = ['error' => 403];
            }
        }
        
        return response()->json( $result );
    }


    /**
     * getPaymentMethod
     */
    public static function getPaymentMethod(){
        $options    = OptionsController::getOptions('products_settings');
        if($options && isset($options['paymentMethods']) && $options['paymentMethods']){
            foreach($options['paymentMethods'] as $method){
                if($method->status){
                    return $method;
                }
            }
        }
    }




    
}
