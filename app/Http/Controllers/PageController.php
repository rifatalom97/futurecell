<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminContactNotificationMail;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\HeaderFooter;


use App\Models\Contacts;
use App\Models\MetaTags;
use App\Models\Category;

use App\Models\Product;
use App\Models\Securities;
use App\Models\ProductDeliveryOptions;
use App\Models\Brand;
use DB;
use Session;

class PageController extends Controller
{
    /**
     * home
     */
    public function home(Request $request){
        $home_data  = OptionsController::getOptions( 'home_page' );


        // filter
        if(isset($home_data['grid_category'])){
            $filter_category  = array();
            foreach($home_data['grid_category'] as $item){
                $category = Category::where('id',$item->category)->first();
                // $filter_category[] = $category;
                $item->category = $category;
                $filter_category[] = $item;
            }
            $home_data['grid_category'] = $filter_category;
        }
        if(isset($home_data['hot_products'])){
            if($home_data['hot_products']->type=='1'){
                $products = Product::join('products_meta','products_meta.product_id','products.id')
                            ->where('products_meta.lang',app()->getLocale())
                            ->where('products.status',1)
                            ->orderBy('products.total_sale','DESC')
                            ->select('products_meta.*','products.*')
                            ->limit(12)
                            ->get();
            }else{
                $products = Product::join('products_meta','products_meta.product_id','products.id')
                                ->join('product_category','product_category.product_id','products.id')
                                ->where('products_meta.lang',app()->getLocale())
                                ->where('product_category.category_id',$home_data['hot_products']->category)
                                ->where('products.status',1)
                                ->select('products_meta.*','product_category.*','products.*')
                                ->limit(12)
                                ->get();
            }

            $home_data['hot_products']->products = $products;
        }
        if(isset($home_data['brands'])&&$home_data['brands']->show=='true'){
            $home_data['brands']->items = Brand::with(['meta'])->where('status',1)->get();
        }
        // end filter

        $header_footer = HeaderFooter::get('home_page');

        return view('home')->with('header_footer',$header_footer)->with('home_data',$home_data);
    }

    /**
     * Category page
     */
    public function categoryPage(Request $request){
        $categorySlug       = $request->categorySlug;
        $categoryPage       = OptionsController::getOptions( 'category_page' );
        $category           = DB::table('category')->where('slug',$categorySlug)->select('id')->first();

        return response()->json([
            'categoryPage' => $categoryPage,
            'categoryId'   => $category&&$category->id?$category->id:0
        ]);
    }
    /**
     * Brand page
     */
    public function brandPage(Request $request){
        $brandSlug      = $request->brandSlug;

        $brandPage      = OptionsController::getOptions( 'brand_page' );
        $brand        = DB::table('brand')->where('slug',$brandSlug)->select('id')->first();

        return response()->json([ 
            'brandPage' => $brandPage, 
            'brandId'   => $brand&&$brand->id?$brand->id:0
        ]);
    }
    /**
     * Search page
     */
    public function searchPage(Request $request){

        $searchPage   = OptionsController::getOptions( 'search_page' );
        $metaTags   = MetaTagsController::getMetaTagsFilterd( 'shop_page' );

        return response()->json([ 'searchPage' => $searchPage, 'metaTags' => $metaTags]);
    }
    /**
     * Contact page
     */
    public function contact(Request $request){
        
        $options      = OptionsController::getOptions( 'contact_page' );
        
        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'     => 'required|string|max:100',
                'email'         => 'required|string|email',
                'mobile'         => 'required|string|max:15',
                'subject'       => 'required|string',
                'message'       => 'required|string',
            ]);

            $attr['visitor_id'] = 1;

            $contact = Contacts::create($attr);
            // Send notifiaction
            if(isset($options['admin_notification']->status)&&$options['admin_notification']->status=='1'&&$options['admin_notification']->email){
                Mail::to($options['admin_notification']->email)
                    ->send(new AdminContactNotificationMail($contact));
            }
            
            Session::flash('message','Success! Message send successfully');
            
            return redirect('contact-us');
        }

        $header_footer = HeaderFooter::get('contact_page');

        return view('contact')->with('header_footer',$header_footer)->with('options',$options);
    }
    // End static pages

    /**
     * about page
     */
    public function about(Request $request){
        
        $options      = OptionsController::getOptions( 'about_page' );
        
        $header_footer = HeaderFooter::get('about_page');

        return view('about')->with('header_footer',$header_footer)->with('options',$options);
    }
    /**
     * terms condition page
     */
    public function terms_conditions(Request $request){
        
        $options      = OptionsController::getOptions( 'terms_conditions_page' );
        
        $header_footer = HeaderFooter::get('terms_conditions_page');

        return view('terms')->with('header_footer',$header_footer)->with('options',$options);
    }
    // End static pages







    /**
     * getPage dynamic page
     */
    function getPage(Request $request){
        $lang = $request->lang;
        $slug = $request->slug;

        $query = DB::table('pages')->where('slug',$slug);
        $page = $metaTags = [];
        if($query->first()){
            $query = $query->join('pages_meta',function($join)use($lang){
                $join->on('pages_meta.page_id','pages.id')->where('lang',$lang);
            });
            $page = $query->select('pages.id', 'pages.thumbnail','pages_meta.title','pages_meta.subTitle','pages_meta.content')->first();
        }
        
        if($page){
            $metaTags       = MetaTagsController::getMetaTags( 'page', $page->id, $lang );
        }

        return response()->json([ 
                        'result'    => (bool)$page,
                        'page'      => $page,
                        'metaTags'  => $metaTags
                    ]);
    }
}
