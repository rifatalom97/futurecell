@extends('layouts.app')

@section('content')

    <?php 
        $total_price = 0;

        $coupon = Session::has('coupon')? Session::get('coupon') : NULL;
    ?>

    <section class="page_area cart_page">
        <div class="container">
            @if(Session::has('coupon_success'))
                <div class="alert alert-success alert-dismissible fade show mt-4 alert-custom"
                    role="alert">
                    {{ Session::get('coupon_success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="page_contents col-md-12">

                    <h3 class="page_title">סל הקניות</h3>
                    
                    <div class="page_content cart_page_content">
                        <div class="table-responsive">
                            <table class="table cart_page_cart_items mb-2">
                                <thead>
                                    <tr class="">
                                        <th>תמונה</th>
                                        <th>תיאור</th>
                                        <th class="text-center">מחיר</th>
                                        <th class="text-center">כמות</th>
                                        <th class="text-center">מחיקה</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($carts))
                                        @foreach($carts as $cart)
                                            <tr class="{{ $loop->iteration%2==0?'active':'' }}">
                                                <td class="mn_thumbnail" width="120">
                                                    @if($cart->product->thumbail)
                                                    <img src="{{ asset('uploads/'.$cart->product->thumbail) }}" alt="{{ $cart->product->meta->title }}" height=""/>
                                                    @endif
                                                </td>
                                                <td class="mn_title">
                                                    <b>{{ $cart->product->meta->title }}</b>
                                                </td>
                                                <td class="mn_total text-center">
                                                    ₪<span>{{ $cart->quantity * $cart->product->price }}</span>
                                                </td>
                                                <td class="mn_quantity text-center">
                                                    <div class="handle_epd_cart_count">
                                                        <img src="{{ asset('assets/frontend/img/minus_icon.svg') }}" onClick="update_cart_quantity(this,-1,{{$cart->product->id}},'{{url("update-cart-item")}}')" class="epd_cart_counter pointer" alt="-" width="20"/>
                                                        <span>{{ $cart->quantity }}</span>
                                                        <img src="{{ asset('assets/frontend/img/plus_icon.svg') }}" onClick="update_cart_quantity(this,1,{{$cart->product->id}}, '{{url("update-cart-item")}}')" class="epd_cart_counter pointer" alt="+" width="20"/>
                                                    </div>
                                                </td>
                                                <td class="mn_remove text-center">
                                                    <img src="{{ asset('assets/frontend/img/cross_icon.svg') }}" alt="Remove item" class="pointer" data-id="{{ $cart->product->id }}" data-action="{{ url('/remove-cart-item') }}"/>
                                                </td>
                                            </tr>
                                        <?php 
                                            $total_price += (int)($cart->quantity * $cart->product->price);
                                        ?>
                                        @endforeach
                                    @else 
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No products found in the cart
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        @if( count($carts) )
                        <div class="cart_page_coupon_price_area">
                            <div class="row">

                                <div class="col-lg-4 col-md-5 my-2">
                                    <div class="cart_coupon_box">
                                        
                                        <p class="mb-0"><label>קוד קופון</label></p>

                                        <div class="coupon_input_field">
                                            <form action="{{ url('/apply-coupon') }}" method="post">
                                                @csrf
                                                <input type="text" class="form-control @if(Session::has('coupon_error')) is-invalid @endif"  value="{{ $coupon?$coupon->coupon_code:'' }}" name="coupon_code" aria-label="קוד קופון" />
                                                <button class="btn btn-transparent-red" type="submit">שלח</button>
                                            </form>
                                        </div>

                                        <div class="row"></div>
                                        @if(Session::has('coupon_error'))
                                        <div class="invalid-feedback" style="display:block!important">{{ Session::get('coupon_error') }}</div>
                                        @endif
                                    </div>

                                    <div class="cart_total_box">
                                        <b class="mx-1">סה״כ לתשלום</b>
                                        ₪
                                        <span><?php  
                                        if( $coupon ){
                                            if($coupon->discount_type=='amount'){
                                                $total_price = (float)$total_price - (float)$coupon->discount;
                                            }else{
                                                $total_price = (float)$total_price - (((float)$total_price*(float)$coupon->discount)/100);
                                            }
                                        }
                                        echo (double)$total_price;
                                        ?></span>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <form action="{{ url('/checkout') }}" method="post">
                                        @csrf 
                                        @if($delivery_methods && count($delivery_methods))
                                        <?php 
                                            $selected_method = Session::has('delivery_method')?Session::get('delivery_method'):NULL;
                                        ?>
                                        <div class="cart_items_delivery_options mt-4">
                                            @foreach($delivery_methods as $method)
                                            <?php 
                                                $checked = '';
                                                if($selected_method){
                                                    if($selected_method->id==$method->id){
                                                        $checked = 'checked';
                                                    }
                                                }else{
                                                    $checked = $loop->iteration==1?'checked':'';
                                                }
                                            ?>
                                            <label>
                                                <input type="radio" name="delivery_method" value="{{ $method->id }}" {{ $checked }}/> <span>{{ $method->meta->title }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                        @if(Session::has('delivery_method_error'))
                                        <div class="invalid-feedback" style="display:block!important">{{ Session::get('delivery_method_error') }}</div>
                                        @endif
                                        @endif

                                        <div class="row">
                                            <div class="col-lg-4 col-md-5">
                                                <button class="cart_checkout_btn btn btn-lg btn-yellow mt-4 btn-block">לבדוק</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
