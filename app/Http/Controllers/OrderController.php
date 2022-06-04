<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Http\Controllers\HeaderFooter;

use App\Models\User;
use App\Models\ProductDeliveryOptions;
use App\Models\Carts;
use App\Models\Product;
use App\Models\OrderProducts;
use App\Models\Orders;
use App\Models\Coupon;
use App\Models\UserPoints;
use App\Models\UserBalance;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use App\Models\Subscribers;
use App\Models\Transactions;
use DB;
use Session;

class OrderController extends Controller
{
    public function apply_coupon(Request $request){
        $coupon_code = $request->input('coupon_code');
        $coupon = Coupon::where('coupon_code',$coupon_code)->where('status',1)->first();
        if($coupon){
            if( (time() >= strtotime($coupon->start_date)) && (strtotime($coupon->expire_date) >= time()) ){
                Session::put( 'coupon', $coupon );
                // set flush message
                Session::flash('coupon_success', 'Coupon applied successfully');
            }else{
                Session::put( 'coupon', NULL );
                Session::flash('coupon_error', 'Coupon has been expired');
            }
        }else{
            Session::put( 'coupon', NULL );
            Session::flash('coupon_error', 'Coupon not found');
        }
        return redirect()->back();
    }

    public function checkout(Request $request){

        if($request->isMethod('post')){
            $delivery_method_id = $request->delivery_method;
            $delivery_method = ProductDeliveryOptions::where('id',$delivery_method_id)->first();
            if(!$delivery_method){
                Session::flash('delivery_method_error', 'Delivery method not found');
                return redirect()->back();
            }
            // Put selected delivery method
            Session::put('delivery_method',$delivery_method);
        }
        // If delivery session not found then redirect it to 404
        if( !Session::has('delivery_method') ){
            return redirect(404);
        }

        $header_footer = HeaderFooter::get('checkout_page',['metaTitle'=>'Checkout']);

        // customer id
        $user_id = Auth::check()?Auth::user()->id:0;

        $shipping_address   = UserShippingAddress::where('user_id',$user_id)->first();
        $billing_address    = UserBillingAddress::where('user_id',$user_id)->first();

        return view('store.checkout')
                    ->with('header_footer',$header_footer)
                    ->with('shipping_address',$shipping_address)
                    ->with('billing_address',$billing_address);
    }

    public function process_order(Request $request){
        
        if(!Session::has('delivery_method')){
            echo '<h2>Bad Request</h2>';
            exit;
        }


        $validation_rules = [
            'shipping_name'     => 'required|string',
            'shipping_mobile'   => 'required|string|max:15',
            'shipping_email'    => 'required|string|email' . (!Auth::check()?'|unique:users,email':''),
            'shipping_company'  => 'required|string',
            'shipping_address'  => 'required|string',
            'shipping_city'     => 'required|string',
            'shipping_zip_code' => 'required|string',
        ];
        if( $request->input('sam_as_shipping')!=1 ){
            $validation_rules = array_merge($validation_rules,[
                'billing_name'          => 'required|string',
                'billing_mobile'        => 'required|string|max:15',
                'billing_email'         => 'required|string|email',
                'billing_city'          => 'required|string',
                'billing_zip_code'       => 'required|string'
            ]);
        }
        
        $attr = $request->validate($validation_rules);


        // First if this is not customer then register first
        if(!Auth::check()){
            $new_user_pass = substr(uniqid(),0,8);
            $user = User::create([
                'name'      => $attr['shipping_name'],
                'mobile'    => $attr['shipping_mobile'],
                'email'     => $attr['shipping_email'],
                'company'   => $attr['shipping_company'],
                'address'   => $attr['shipping_address'],
                'city'      => $attr['shipping_city'],
                'zip_code'  => $attr['shipping_zip_code'],
                'password'  => bcrypt($new_user_pass),
            ]);

            // mail notifiaction
            Mail::to($user->email)
                ->send(new RegistrationMail($user,$new_user_pass));
            if (Mail::failures()) {
                echo 'Something went wrong';
                die();
            }
            // end


            // Defining user id array
            $user_id_array = array( 'user_id' => $user->id );
    
            // Create points and balance
            UserPoints::create($user_id_array);
            UserBalance::create($user_id_array);
            // end
    
            // billing address and shipping address
            UserBillingAddress::create($user_id_array);
            UserShippingAddress::create($user_id_array);
            // end 


            // Update cart id with local id 
            Carts::where('session_id',session()->getid())
                    ->update($user_id_array);
            // end
    
            // assing to newsletter subscription
            if(isset($request->isNewsletter) && $request->isNewsletter){
                Subscribers::create([
                    'user_id'   => $user->id,
                    'email'     => $user->email,
                    'status'    => true
                ]);
                // mail notifiaction
                Mail::to($user->email)
                    ->send(new SubscriptionMail($user));
                if (Mail::failures()) {
                    echo 'Something went wrong';
                    die();
                }
                // end
            }
            // end 

            Auth::attempt(['email'=>$user->email,'password'=>$new_user_pass]);
        }
        // get customer user id
        $user_id = Auth::user()->id;

        

        // Update shipping and billing address
        UserShippingAddress::where('user_id',$user_id)->update([
            'name'      => $attr['shipping_name'],
            'mobile'    => $attr['shipping_mobile'],
            'email'     => $attr['shipping_email'],
            'company'   => $attr['shipping_company'],
            'address'   => $attr['shipping_address'],
            'city'      => $attr['shipping_city'],
            'zip_code'  => $attr['shipping_zip_code']
        ]);
        UserBillingAddress::where('user_id',$user_id)->update([
            'name'      => $attr['billing_name'],
            'mobile'    => $attr['billing_mobile'],
            'email'     => $attr['billing_email'],
            'city'      => $attr['billing_city'],
            'zip_code'  => $attr['billing_zip_code']
        ]);
       
        // Carts items
        $carts = Carts::where('user_id',$user_id)->get();
        // Get coupon if applied
        $coupon = Session::has('coupon')?Session::get('coupon'):NULL;
        // Get delivery method
        $delivery_method = Session::has('delivery_method')?Session::get('delivery_method'):NULL;
        // Generate order number
        $unique_order_number = strtoupper(uniqid());
        // End update

        if($carts){
            $params = [
                'order_number'          => $unique_order_number,
                'user_id'               => $user_id,
                'currency_code'         => 'ILS',
                'currency_sign'         => '₪',
                'coupon_id'             => isset($coupon->id)?$coupon->id:NULL,
                'delivery_option_id'    => isset($delivery_method->id)?$delivery_method->id:NULL,
            ];
            $params['shipping_address'] = json_encode(array(
                'name'      => $attr['shipping_name'],
                'mobile'    => $attr['shipping_mobile'],
                'email'     => $attr['shipping_email'],
                'company'   => $attr['shipping_company'],
                'address'   => $attr['shipping_address'],
                'city'      => $attr['shipping_city'],
                'zip_code'  => $attr['shipping_zip_code']
            ));
            if($request->input('sam_as_shipping')!=1){
                $params['billing_address'] = json_encode(array(
                    'name'      => $attr['billing_name'],
                    'mobile'    => $attr['billing_mobile'],
                    'email'     => $attr['billing_email'],
                    'city'      => $attr['billing_city'],
                    'zip_code'  => $attr['billing_zip_code']
                ));
            }
            $order_id = Orders::create( $params )->id;

            
            $total_cart_amount = 0;
            foreach($carts as $cart){
                if($cart->product){
                    $total = ceil(((int)$cart->quantity)*((double)$cart->product->price));
                    
                    $total_cart_amount += $total;
                    OrderProducts::create([
                        'order_id'          => (int)$order_id,
                        'product_id'        => $cart->product->id,
                        'size_id'           => isset($cart->size_id)?$cart->size_id:NULL,
                        'color_id'          => isset($cart->color_id)?$cart->color_id:NULL,
                        'quantity'          => (int)$cart->quantity,
                        'unite_price'       => (double)$cart->product->price,
                        'total_amount'      => (double)$total,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                    ]);
                }
            }
            
            // coupon handling
            $total_amount = $total_cart_amount;
            if($coupon && isset($coupon->id)){
                if($coupon->discountType=='percent'){
                    $less           = $total_cart_amount * round((((double)$coupon->discount) / 100), 2);
                    $total_amount   = $total_cart_amount - $less;
                }else{
                    $total_amount   = round($total_cart_amount - (double)$coupon->discount, 2);
                }
            }
            // End discount

            // Total amount with delivery option amount
            $total_amount = $total_amount + (double)$delivery_method->amount;
            Orders::where('id',$order_id)->update([
                'cart_total_amount' => $total_cart_amount,
                'total_amount'      => $total_amount,
                'updated_at'        => Carbon::now(),
            ]);


            // Now remove carts
            Carts::where('user_id',$user_id)->delete();
            if(Session::has('coupon')){
                Session::put('coupon',NULL);
            }
            Session::put('delivery_method',NULL);
            // end

            // Payment handling
            $paymentMethod = ProductsController::getPaymentMethod();
            
            $payment = $this->process_payment(
                        'ILS', 
                        $total_amount, 
                        $paymentMethod, 
                        $unique_order_number
                    );
            if( $payment && isset($payment->Error) && $payment->Error->ErrCode === 0 ){
                $trans = explode('transactionId=',$payment->URL);
                $transaction_id = DB::table('transactions')->insertGetId([
                    'user_id'           => $user_id,
                    'transactionId'     => $trans[1],
                    'confirmationKey'   => $payment->ConfirmationKey,
                    'amount'            => $total_amount,
                    'currency'          => 'ILS',
                    'api_type'          => $paymentMethod->apiType,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);
                Orders::where('id',$order_id)->update([
                    'payment_status' => 1, // 0 unpaid, 1 processing, 2 canceld, 3 error, 4 paid
                    'transaction_id' => $transaction_id,
                    'updated_at'        => Carbon::now()
                ]);

                // Finally update sales report
                DB::table('sales_report')
                ->update([
                    'total_orders' => DB::raw('total_orders + 1')
                ]);

                return redirect($payment->URL);
            }else{
                echo 'Something went wrong';
                die();
            }
            // end
        }
    }
    public function process_payment( $currency, $total_amount, $paymentMethod, $orderNumber ){
        return $this->{$paymentMethod->apiType}( 
            $currency, 
            $total_amount, 
            $paymentMethod->terminal, 
            $paymentMethod->username, 
            $paymentMethod->password, 
            $orderNumber
        );
    }
    private function pelecard( $currency, $total_amount, $terminal, $username, $password, $orderNumber ){
        // var_dump($total_amount);
        // exit;
        $data = array(
            'terminal'                  => $terminal, //'0962210',
            'user'                      => $username, //'peletest',
            'password'                  => $password, //'Pelecard@2013',
    
            'Currency'                  => $this->get_currency_code($currency), // 1 for ISL, 2 for usd
            "Total"                     => (string)($total_amount . '00'), // amount
            
            "ActionType"            => "J4",
            "FirstPayment"          => "auto",
            "FreeTotal"             => "False",
            // "ParamX"                => "test payment",
            "ShopNo"                => "001",

            "ErrorURL"              => url('/checkout/error'),
            "GoodURL"               => url('/checkout/success'),
            "FeedbackOnTop"         => "False",
            "UseBuildInFeedbackPage" => "False",
            "CssURL"                => "https://gateway20.pelecard.biz/Content/Css/variant-he-3.css",
            "HiddenPciLogo"         => "False",
            "HiddenPelecardLogo"    => "False",
            "HiddenSslSeal"         => "False",
            "Language"              => "HE",
            "LogoURL"               => "https://gateway20.pelecard.biz/Content/images/Pelecard.png",
            "PlaceholderCaptions"   => "False",
            "ShowBrandLogo"         => "False",
            "ShowConfirmationCheckbox" => "False",
            "ShowXParam"            => "False",
            "SplitCCNumber"         => "False",
            "AddHolderNameToXParam" => "False",
            "TakeIshurPopUp"        => "False",
            "SupportedCards"        => array(
                "Amex"      => "False",
                "Diners"    => "False",
                "Isra"      => "True",
                "Master"    => "True",
                "Visa"      => "True"
            ),
            "EmvPinpad"             => "False",
            "CardHolderName"        => "hide",
            "CustomerIdField"       => "optional",
            "Cvv2Field"             => "optional",
            "EmailField"            => "hide",
            "TelField"              => "hide",
            "CreateToken"           => "False"
        );
        $json_object_data = json_encode($data);
        # Setup request to send json via POST.
        $ch = curl_init('https://gateway20.pelecard.biz/PaymentGW/init');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_object_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch, 
            CURLOPT_HTTPHEADER, 
            array(
                'Content-Type: application/json; charset=UTF-8',
                'Content-Length: ' . strlen($json_object_data)
            )
        );
        // Start verifier on live serve
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.

        // Return response
        return $result?json_decode($result):array();
    }
    private function get_currency_code($currency){
        switch($currency){
            case 'USD':
                return '2';
                break;
            default:
                return '1';
                break;
        }
    }

    public function success_and_confirm_payment(){
        
        $transactionId      = $request->PelecardTransactionId;
        $statusCode         = $request->PelecardStatusCode;

        $transaction_db = Transactions::where('status',0);

        if(!$transaction_db->first()){
            return redirect(404);
        }

        if($transactionId && $statusCode){
            $transaction    = $transaction_db->where('transactionId',$transactionId);
            if($transaction->count()==1){
                $order                  = Orders::where('transaction_id', $transaction->first()->id);
                if($statusCode == '000'){
                    $transaction->update(array('status'=>1));
                    $order->update(array('payment_status' => 1));

                    Mail::to($auth->email)->send(new OrderConfirmMail($auth,$otp));
                    if (Mail::failures()) {
                        echo 'Something went wrong';
                        die();
                    }
                }else{
                    $order->update(array('payment_status' => ($statusCode!='000'?3:2)));
                    return redirect('/checkout/error');
                }
            }
        }

        $header_footer = HeaderFooter::get('checkout_page',['metaTitle'=>'Confirm payment']);

        return view('store.checkout_success')
                    ->with('header_footer',$header_footer);
    }
    public function payment_error(){

        $transactionId      = $request->input('PelecardTransactionId');

        $transaction_db = Transactions::where('status',0);
        if(!$transaction_db->first()){
            return redirect(404);
        }

        if($transactionId){
            $transaction = $transaction_db->where('transactionId',$transactionId);
            if($transaction->count()==1){
                Orders::where('transaction_id',$transaction->first()->id)
                            ->update(array('payment_status' => 2));
            }
        }

        $header_footer = HeaderFooter::get('checkout_page',['metaTitle'=>'Payment errror']);

        return view('store.checkout_error')
                    ->with('header_footer',$header_footer);
    }
}
