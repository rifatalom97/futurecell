<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\HeaderFooter;

use App\Models\ProductDeliveryOptions;
use App\Models\Carts;
use App\Models\Product;
use Session;
class CartController extends Controller
{
    /**
     * Cart
     */
    public function cart(Request $request){
        $header_footer = HeaderFooter::get('store_page');

        $delivery_methods   = ProductDeliveryOptions::with(['meta'])->where('status',1)->get();
        return view('store.cart')
                ->with('header_footer',$header_footer)
                ->with('carts',self::get_cart_items())
                ->with('delivery_methods',$delivery_methods);
    }

    public static function get_cart_items(){
        $user_id        = isset(auth()->user()->id)?auth()->user()->id:0;
        $session_id  = session()->getid();

        return  Carts::join('products','products.id','=','carts.product_id')
                        ->join('products_meta','products_meta.product_id','=','products.id')
                        ->where('products_meta.lang',app()->getLocale())
                        ->where('carts.user_id',$user_id)
                        ->orWhere('session_id',$session_id)
                        ->select('carts.*','products.thumbnail','products.price','products_meta.title')
                        ->get();
    }

    public static function is_product_exists( $product_id ){
        $user_id        = isset(auth()->user()->id)?auth()->user()->id:0;
        $session_id  = session()->getid();

        $product_count = Carts::where('user_id',$user_id)
                        ->orWhere('session_id',$session_id)
                        ->where('product_id',$product_id)
                        ->count();

        return (bool) $product_count;
    }


    function add_to_cart(Request $request){
        $product_id     = $request->input('product_id');
        $quantity       = $request->input('quantity');

        
        $session_id     = session()->getid();
        $user_id        = isset(auth()->user()->id)?auth()->user()->id:0;

        $product        = Product::where('id',$product_id)->first();
        if(isset($product->id) && $product->id){
            if($product->quantity > 0){
                $carts = Carts::where('product_id',$product_id)
                        ->where('session_id',$session_id)
                        ->orWhere('user_id',$user_id);
                if($carts->count()){
                    $carts->update( ['quantity' => $quantity] ); 
                }else{
                    Carts::create([
                        'session_id'    => $session_id,
                        'user_id'       => $user_id,
                        'product_id'    => $product_id,
                        'quantity'      => $quantity
                    ]);
                }
                return response()->json([
                    'status'    => '200',
                    'carts'     => $this->get_cart_items(),
                ]);
            }
        }

        return response()->json([
            'status' => 500
        ]);
    }



    public function remove_cart_item(Request $request){
        $product_id     = $request->product_id;
        $session_id     = session()->getid();
        $user_id        = isset(auth()->user()->id)?auth()->user()->id:0;

        Carts::where('user_id',$user_id)
        ->orWhere('session_id',$session_id)
        ->where('product_id',$product_id)
        ->delete();
        return response()->json(['status'=>200]);
    }

    public function update_cart_item(Request $request){
        $product_id     = (int)$request->product_id;
        $quantity       = (int)$request->quantity;
        $session_id     = session()->getid();
        $user_id        = isset(auth()->user()->id)?auth()->user()->id:0;

        $query = Carts::with(['product'])->where('user_id',$user_id)
                    ->orWhere('session_id',$session_id)
                    ->where('product_id',$product_id);
        $selected_cart_item = $query->first();
        if($selected_cart_item){
            $query->update( ['quantity'=>(int)$quantity] );
            $updated_cart_item  = $query->first();
            $price              = $updated_cart_item->product->price;
            $total_amount       = (double)$price * $quantity;
            $total_item_amount  = $total_amount;

            if(Session::has('coupon')){
                $coupon = Session::get('coupon');
                if( $coupon->discount_type != 'amount' ){
                    $total_amount = $total_amount - ( ($total_amount*((double)$coupon->discount))/100 );
                }else{
                    $total_amount = $total_amount - ((double)$coupon->discount);
                }
            }

            return response()->json([ 'total_item_amount' => $total_item_amount, 'total_amount' => $total_amount ]);
        }
    }
}
