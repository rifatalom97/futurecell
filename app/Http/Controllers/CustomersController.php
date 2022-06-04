<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\CountryController;
use App\Http\Controllers\HeaderFooter;

use App\Rules\MatchOldPassword;
use App\Rules\MatchEmail;
use App\Rules\MatchWith;

use App\Mail\VerificationMail;
use App\Mail\RegistrationMail;

use App\Models\Searches;
use App\Models\User;
use App\Models\Orders;
use App\Models\Subscribers;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use App\Models\Product;
use App\Models\Carts;
use App\Models\UserBalance;
use App\Models\UserPoints;
use DB;

use Carbon\Carbon;

use Session;

// use Stripe\Error\Card;
// use Cartalyst\Stripe\Stripe;


class CustomersController extends Controller
{
    public function login( Request $request ){

        if(Auth::check()){
            return redirect('404');
        }

        if($request->isMethod('post')){
            $attr = $request->validate([
                'email'         => 'required|string|email',
                'password'      => 'required|string|min:6|max:15'
            ]);
        
            if( Auth::attempt($attr) ) {
                
                // update carts ids session_id with user id
                Carts::where('session_id',session()->getid())->update(['user_id'=>Auth::user()->id]);

                // if redirect url exists on url
                if($request->input('redirect_to')){
                    return redirect($request->input('redirect_to'));
                }
                // else return to customer account
                return redirect('my-account');
            }
        }

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'Liogin'));

        return view('customer.login')->with('header_footer',$header_footer);
    }

    public function register( Request $request ){
        
        if(Auth::check()){
            return redirect('404');
        }

        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'          => 'required|string|max:255',
                'mobile'        => 'required|string|max:15',
                'email'         => 'required|string|email|unique:users,email',
                'address'       => 'required|string|max:255',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255',
                'password'          => 'required|string|min:6|max:15',
                'confirm_password'  => 'required|same:password',
            ]);
    
            $user = User::create([
                'name'      => $attr['name'],
                'mobile'    => $attr['mobile'],
                'email'     => $attr['email'],
                'company'   => $attr['company'],
                'address'   => $attr['address'],
                'city'      => $attr['city'],
                'zip_code'  => $attr['zip_code'],
                'password'  => bcrypt($attr['password']),
            ]);
    
            // Defining user id array
            $user_id_array = array( 'user_id' => $user->id );
    
            // Create points and balance
            DB::table('user_points')->insert($user_id_array);
            DB::table('user_balance')->insert($user_id_array);
            // end
    
            // billing address and shipping address
            DB::table('user_billing_address')->insert($user_id_array);
            DB::table('user_shipping_address')->insert($user_id_array);
            // end 

            // Update cart id with local id 
            Carts::where('session_id',session()->getid())
                    ->update($user_id_array);
            // end
    
            // assing to newsletter subscription
            if($request->input('isNewsletter')){
                Subscribers::create([
                    'user_id'   => $user->id,
                    'email'     => $user->email,
                    'status'    => true
                ]);
            }
            // end 

            // mail notifiaction
            Mail::to($auth->email)
                ->send(new RegistrationMail($auth,$otp));
            if (Mail::failures()) {
                echo 'Something went wrong';
                die();
            }
            // end
    
            // set flush message
            Session::flash('message', 'Your account created succcessfully');
            return redirect('/my-account');
        }

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'Register'));

        return view('customer.register')->with('header_footer',$header_footer);
    }
    public function forgot_password( Request $request ){

        if($request->isMethod('post')){
            
            if(!Session::has('otp') && !Session::has('access')){
                $attr = $request->validate([
                    'email' => array('required','string','email',new MatchEmail)
                ]);
                
                $user = User::where('email',$attr['email'])->first();

                $otp = substr(rand(1,time()), 0, 5);

                Mail::to($attr['email'])
                    ->send(new VerificationMail($user,$otp));
                if (Mail::failures()) {
                    echo 'Something went wrong';
                    die();
                }else{
                    Session::flash('message','Verification email sent to your email');
                    Session::put('otp_sender',$attr['email']);
                    Session::put('otp',$otp);
                    
                    return redirect('forgot-password');
                }
            }

            if( Session::has('otp') && !Session::has('access')){
                $attr = $request->validate([
                    'otp' => array('required','string',new MatchWith(Session::get('otp')))
                ]);

                Session::forget('otp');
                Session::put('access',true);
                return redirect('forgot-password');
            }
            if( Session::has('access') ){
                $attr = $request->validate([
                    'password'          => 'required|min:8|max:15',
                    'confirm_password'  => 'required|min:8|max:15|same:password',
                ]);
                User::where('email',Session::get('otp_sender'))->update(['password'=>Hash::make($attr['confirm_password'])]);
                Session::forget('otp_verified');
                Session::forget('otp_sender');
                Session::forget('access');

                Session::flush('message','Password updated successfully');
                return redirect('/login');
            }
        }

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'Fogot password'));

        return view('customer.forgot_password')->with('header_footer',$header_footer);
    }

    public function dashboard( Request $request ){
        $header_footer = HeaderFooter::get('',array('metaTitle'=>'My account'));

        $searches = Searches::where('user_id',auth()->user()->id)
                        ->orderBy('id','DESC')
                        ->limit(10)
                        ->get();
        if($searches){
            $keywords = [];
            foreach($searches as $search){
                $search_line = $search->keywords;
                if($search_line){
                    $words = explode(' ',$search_line);
                    $keywords = array_merge( $words, $keywords );
                }
            }
        }

        $products = Product::with(['meta'=>function($join)use($keywords){
            if(count($keywords)){
                $i = 0;
                foreach($keywords as $keyword){
                    if($i==0){
                        $join = $join->where('title','LIKE','%'.$keyword.'%');
                    }else{
                        $join = $join->orWhere('title','LIKE','%'.$keyword.'%');
                    }
                    $i++;
                }
                $join->orWhere('title','!=',null);
            }
        }])->where('status',1)
        ->inRandomOrder()
        ->limit(3)
        ->get();


        $balance = UserBalance::where('user_id',Auth::user()->id)->select('balance')->first();
        $points = UserPoints::where('user_id',Auth::user()->id)->select('points')->first();

        return view('customer.dashboard')
                    ->with('header_footer',$header_footer)
                    ->with('balance',$balance)
                    ->with('points',$points)
                    ->with('products',$products);
    }
    public function orders( Request $request ){

        $orders = Orders::with(['orderProducts'=>function($join){
            $join->with(['product'=>function($join2){
                $join2->with(['meta']);
            }]);
        }])->where('user_id',auth()->user()->id)
            ->where('user_trashed',0)
            ->orderBy('id','DESC')
            ->paginate(9);

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'My Orders'));
        return view('customer.orders')->with('header_footer',$header_footer)->with('orders',$orders);
    }
    public function shipping_address( Request $request ){

        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'          => 'required|string|max:255',
                'mobile'        => 'required|string|max:15',
                'email'         => 'required|string|email',
                'address'       => 'required|string|max:255',
                'company'       => 'nullable|string|max:255',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255'
            ]);

            UserShippingAddress::where('user_id',Auth::user()->id)->update($attr);

            // set flush message
            Session::flash('message', 'Shipping address updated successfully');
            
            return redirect( 'my-account/shipping-address' );
        }

        $shipping_address = UserShippingAddress::where('user_id',Auth::user()->id)->first();

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'My shipping address'));

        return view('customer.shipping_address')->with('header_footer',$header_footer)->with('shipping_address',$shipping_address);
    }
    public function billing_address( Request $request ){
        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'          => 'required|string|max:255',
                'mobile'        => 'required|string|max:15',
                'email'         => 'required|string|email',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255'
            ]);

            UserBillingAddress::where('user_id',Auth::user()->id)->update($attr);
            
            // set flush message
            Session::flash('message', 'Billing address updated successfully');
            
            return redirect( 'my-account/billing-address' );
        }

        $billing_address = UserBillingAddress::where('user_id',Auth::user()->id)->first();

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'My billing address'));

        return view('customer.billing_address')->with('header_footer',$header_footer)->with('billing_address',$billing_address);
    }
    public function settings( Request $request ){
        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'          => 'required|string|max:255',
                'mobile'        => 'required|string|max:15',
                'address'          => 'required|string|max:255',
                'company'          => 'required|string|max:255',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255'
            ]);

            User::where('id',Auth::user()->id)->update($attr);
            
            // set flush message
            Session::flash('message', 'Account details updated successfully');
            
            return redirect( 'my-account/settings' );
        }

        $user = User::where('id',Auth::user()->id)->first();

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'My billing address'));

        return view('customer.settings')->with('header_footer',$header_footer)->with('user',$user);
    }
    public function password_update( Request $request ){

        if($request->isMethod('post')){  
            if(!Session::has('otp') && !Session::has('otp_verified')){
                $otp = substr(rand(1,time()), 0, 5);
                // Verification mail send
                $auth = Auth::user();
                Mail::to($auth->email)
                    ->send(new VerificationMail($auth,$otp));
                if (Mail::failures()) {
                    echo 'Something went wrong';
                    die();
                }else{
                    Session::put('otp',$otp);
                }
                // end
                // finally auto redirec to same page
                return redirect('/my-account/password-update');
            }
            if( Session::has('otp') && !Session::has('otp_verified') ){
                // var_dump(Session::get('otp'));
                // exit;
                $attr = $request->validate([
                    'otp' => array('required','string',new MatchWith(Session::get('otp')))
                ]);
                Session::forget('otp');
                Session::put('otp_verified',true);
                // finally auto redirec to same page
                return redirect('/my-account/password-update');
            }
            if( !Session::has('otp') && Session::has('otp_verified') ){
                $attr = $request->validate([
                    'current_password'  => ['required','min:8','max:15',new MatchOldPassword],
                    'new_password'      => 'required|different:current_password|min:8|max:15',
                    'confirm_password'  => 'required|same:new_password|min:8|max:15',
                ]);
                User::where('id',Auth::user()->id)->update(['password'=>Hash::make($attr['confirm_password'])]);
                Session::forget('otp_verified');
                Session::flush('message','Password updated successfully');
                return redirect('/my-account');
            }
        }

        $header_footer = HeaderFooter::get('',array('metaTitle'=>'Change password'));

        return view('customer.password_update')->with('header_footer',$header_footer);
    }
    public function logout( Request $request ){
        Auth::logout();
        return redirect('/login');
    }

    public function update( Request $request ){

        $attr = $request->validate([
            'id'            => 'required|string|max:11',
            'name'      => 'required|string|max:255',
            'mobile'        => 'required|string|max:15',
            'email'         => 'required|string|email|unique:users,email',
            'company'       => 'nullable|string',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'zip_code'       => 'required|string|max:255',
            'password'      => 'nullable|string|min:6|max:15'
        ]);

        $fields = [
            'name'  => $attr['name'],
            'mobile'    => $attr['mobile'],
            'email'     => $attr['email'],
            'company'   => $attr['company'],
            'address'   => $attr['address'],
            'city'      => $attr['city'],
            'zip_code'   => $attr['zip_code']
        ];
        if($attr['password']){
            $fields['password'] = bcrypt($attr['password']);
        }
        $user = User::where('id',$attr['id'])->update( $fields );

        return response()->json([
            'token'     => $user->createToken('tokens')->plainTextToken,
            'details'   => $user
        ]);
    }
    public function checkEmail( Request $request ){
        $email = $request->email;
        if($email){
            $user = User::where('email',$email)->first();
            
            return response()->json(['result'=>(bool)$user]);
        }
    }
    public function getCountries( Request $request ){
        $lang = $request->lang;

        $countries = CountryController::getFilterdCountries( $lang );

        return response()->json(['countries'=>$countries]);
    }

 

    public function fetchOrders(Request $request){
        $customerId = auth()->user()->id;
        $lang       = $request->lang;

        if( $customerId ){
            $orders = DB::table('orders')
                            ->where('user_id',$customerId)
                            ->where('user_trashed',0)
                            ->orderBy('id','DESC')
                            ->get();
            if($orders){
                $filterd_orders = [];
                foreach($orders as $order){
                    $order->products = DB::table('order_products')
                                        ->join('products_meta',function($join)use($lang){
                                            $join
                                            ->on('products_meta.product_id','order_products.product_id')
                                            ->where('products_meta.lang',$lang);
                                        })
                                        ->leftJoin('size','size.id','order_products.size_id')
                                        ->leftJoin('color','color.id','order_products.color_id')
                                        ->leftJoin('color_meta',function($join)use($lang){
                                            $join
                                            ->on('color_meta.color_id','color.id')
                                            ->where('color_meta.lang',$lang);
                                        })
                                        ->where('order_products.order_id',$order->id)
                                        ->select(
                                            'order_products.*',
                                            'products_meta.title',
                                            DB::raw("CONCAT(size.sizeValue,' ',size.sizeUnite) as size"),
                                            'color.code',
                                            'color_meta.title as color_name'
                                        )
                                        ->get();
                    $filterd_orders[] = $order;
                }

                return response()->json($filterd_orders);
            }
        }
    }
    public function orderTrash(Request $request){
        $orderNumber    = $request->orderNumber;
        $customerId = auth()->user()->id;
        if($orderNumber){
            $order = DB::table('orders')
                    ->where('order_number',$orderNumber)
                    ->where('user_id',$customerId)
                    ->where('orders.order_status',0);
            $selected_order = $order->first();
            if( $selected_order ){
                $order->delete();
                DB::table('order_products')->where('order_id',$selected_order->id)->delete();
                $result = 200;
            }else{
                $result = 500;
            }
        }else{
            $result = 500;
        }

        return response()->json($result);
    }
    public function delete_order(Request $request){
        $order_number    = $request->order_number;
        $customerId     = auth()->user()->id;
        $msg            = '';
        if($order_number){
            $order = DB::table('orders')
                    ->where('order_number',$order_number)
                    ->where('user_id',$customerId)
                    ->where('orders.order_status',0);
            $selected_order = $order->first();
            if( $selected_order ){
                $order->delete();
                DB::table('order_products')->where('order_id',$selected_order->id)->delete();
                $msg = 'Success! Order deleted successfully';
            }
        }

        // set flush message
        Session::flash('message', $msg?:'OOPs! Something went wrong');
        
        return redirect()->back();
    }



    /**
     * Save and get account
     */
    public function account(Request $request){
        $user = auth()->user();

        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'          => 'required|string|max:255',
                'mobile'         => 'required|string|max:15',
                'company'       => 'nullable|string|max:15',
                'address'       => 'required|string|max:255',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255',
                'state'         => 'nullable|string|max:255',
            ]);
            $result = User::updateOrCreate(
                ['id'=>$user->id],
                $attr
            );
        }
            
        $points = DB::table('user_points')->where('user_id',$user->id)->first();
        $balance = DB::table('user_balance')->where('user_id',$user->id)->first();
        $user->points = $points?$points->points:0;
        $user->balance = $balance?$balance->balance:0.00;

        $billing_address    = DB::table('user_billing_address')->where('user_id',$user->id)->first();
        $shipping_address   = DB::table('user_shipping_address')->where('user_id',$user->id)->first();
        $user->billing_address = $billing_address?:[];
        $user->shipping_address = $shipping_address?:[];

        return response()->json([
            'details'   => $user
        ]);
    }
    /**
     * Save and get shipping address
     */
    public function shippingAddress(Request $request){
        $user_id = auth()->user()->id;

        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'     => 'required|string|max:255',
                'email'         => 'required|string|email',
                'mobile'         => 'required|string|max:15',
                'company'       => 'nullable|string|max:15',
                'address'       => 'required|string|max:255',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255',
                'state'         => 'nullable|string|max:255',
            ]);
            $attr['user_id'] = $user_id;
            $result = UserShippingAddress::updateOrCreate(
                ['user_id'=>$user_id],
                $attr
            );
        }else{
            $result = UserShippingAddress::where('user_id',$user_id)->first();
        }

        
        return response()->json($result);
    }
    /**
     * Save billing address
     */
    public function billingAddress(Request $request){
        $user_id = auth()->user()->id;

        if($request->isMethod('post')){
            $attr = $request->validate([
                'name'     => 'required|string|max:255',
                'email'         => 'required|string|email',
                'mobile'         => 'required|string|max:15',
                'address'       => 'required|string|max:255',
                'city'          => 'required|string|max:255',
                'zip_code'       => 'required|string|max:255',
            ]);
            $attr['user_id'] = $user_id;
            $result = UserBillingAddress::updateOrCreate(
                ['user_id'=>$user_id],
                $attr
            );
        }else{
            $result = UserBillingAddress::where('user_id',$user_id)->first();
        }

        
        return response()->json($result);
    }



    /**
     * Send customer otp
     */
    public function sendOtpMail(Request $request){
        $user = auth()->user();
        if($user){
            $random_number = substr(rand(1,time()), 0, 5);
            
            User::where('id',$user->id)->update(['otp'=>$random_number]);

            return response()->json(['result'=>$random_number]);
        }
    }
    public function verifyOtp(Request $request){
        $otpCode = $request->otpCode;
        $user = auth()->user();

        $attr = $request->validate([
            'otpCode'     => 'required|string|max:5',
        ]);

        $user = DB::table('users')->where('id',$user->id)->select('otp')->first();

        return response()->json(['result'=>($user->otp == $otpCode)]);
    }
    public function updatePassword(Request $request){
        $otpCode    = $request->otpCode;
        $email      = $request->input('email');
        $user = auth()->user();

        $attr = $request->validate([
            'otpCode'               => 'required|string|max:5',
            'email'                 => 'nullable|email',
            'oldPassword'           => 'required|min:8',
            'newPassword'           => 'required|min:8',
            'confirmNewPassword'    => 'required|min:8',
        ]);

        $user_query = DB::table('users')->where('otp',$otpCode)->where('id',$user->id)->orWhere('email',isset($attr->email)?$attr->email:'');

        $user = $user_query->select('password')->first();

        if( !$user || !Hash::check($request->oldPassword, $user->password) ){
            return response()->json(['result'=>404]);
        }else{
            $user_query->update(['password'=>Hash::make($request->confirmNewPassword)]);
            return response()->json(['result'=>200]);
        }
    }


    /**
     * resetPassword
     */
    public function resetPassword(Request $request){
        $attr = $request->validate([
            'password'     => 'nullable|string|min:8',
            'otpCode'     => 'nullable|string|max:5',
            'email'       => 'required|email',
        ]);

        $user = User::where('email',$request->email)->first();
        if(!$user){
            $result = ['errors'=>['email'=>404]];
        }else{
            if(!isset($request->otpCode)||!$request->otpCode){
                $random_number = substr(rand(1,time()), 0, 5);
                User::where('email',$request->email)->update(['otp'=>$random_number]);
                $result = ['result'=>200,'otpCode'=>$random_number];
            }else if( (!isset($request->password)||!$request->password) && isset($request->otpCode)&&$request->otpCode){
                $user   = DB::table('users')->where('id',$user->id)->select('otp')->first();
                $result = ['result'=>($user->otp == $request->otpCode)];
            }else{
                if( isset($request->password) && $request->password && $request->email && $request->otpCode){
                    $user = DB::table('users')->where('id',$user->id)->where('otp',$request->otpCode);
                    if($user->first()){
                        $user->update(['password'=>Hash::make($request->password)]);
                        $result = ['result'=>200];
                    }else{
                        $result = ['result'=>500];
                    }
                }
            }
        }

        return response()->json($result);
    }
}
