@extends('layouts.app')

@section('content')

<section class="page_area checkout_page">

    <div class="container">
        <div class="row">
            <div class="page_contents col-md-6">
                <form method="post" action="{{ url('process-order') }}">
                    @csrf
                    <div class="page_content">
                        <div class="row">
                            <div class='col-lg-8 col-md-12'>
                                <!-- Page title -->
                                <h3 class="page_title">
                                    <span>לבדוק</span>
                                    @if(!auth()->user())
                                    <a class="checkout_login" href="{{ url('/login?redirect_to='.url('checkout')) }}">יש לי חשבון</a>
                                    @endif
                                </h3>

                                <!-- Shipping address -->
                                <div class="shipping_address">
                                    <h4><b>כתובת למשלוח</b></h4>
                                    <div class="shipping_address">
                                        <?php 
                                            $name       = old('shipping_name');
                                            $mobile     = old('shipping_mobile');
                                            $email      = old('shipping_email');
                                            $company    = old('shipping_company');
                                            $address    = old('shipping_address');
                                            $city       = old('shipping_city');
                                            $zip_code   = old('shipping_zip_code');
                                            if(auth()->user()){
                                                $name       = $shipping_address->name?$shipping_address->name:auth()->user()->name;
                                                $mobile     = $shipping_address->mobile?$shipping_address->mobile:auth()->user()->mobile;
                                                $email      = $shipping_address->email?$shipping_address->email:auth()->user()->email;
                                                $company    = $shipping_address->company?$shipping_address->company:'';
                                                $address    = $shipping_address->address?$shipping_address->address:'';
                                                $city       = $shipping_address->city?$shipping_address->city:'';
                                                $zip_code   = $shipping_address->zip_code?$shipping_address->zip_code:'';
                                            }
                                        ?>
                                        @include('manager/common/form/input',['type'=>'text','title'=>'שם מלא','name'=>'shipping_name','value'=>$name])
                                        @include('manager/common/form/input',['type'=>'tel','title'=>'נייד','name'=>'shipping_mobile','value'=>$mobile])
                                        @include('manager/common/form/input',['type'=>'email','title'=>'אימייל','name'=>'shipping_email','value'=>$email])
                                        @include('manager/common/form/input',['type'=>'text','title'=>'שם חברה (לא חובה)','name'=>'shipping_company','value'=>$company])
                                        @include('manager/common/form/input',['type'=>'text','title'=>'כתובת למשלוח','name'=>'shipping_address','value'=>$address])
                                        @include('manager/common/form/input',['type'=>'text','title'=>'עיר','name'=>'shipping_city','value'=>$city])
                                        @include('manager/common/form/input',['type'=>'text','title'=>'מיקוד','name'=>'shipping_zip_code','value'=>$zip_code])
                                    </div>
                                </div> <!-- End Shipping address -->

                                <!-- Billing address -->
                                <div class="billing_address_area mt-4">
                                    <h4><b>פרטים לחיוב</b></h4>

                                    <!-- Same as shipping address -->
                                    <label class="small same_as_shipping_address">
                                        <input name="sam_as_shipping" type="checkbox" onChange="handle_shame_as(this)" value="1" {{ old('sam_as_shipping')?'checked':'' }}/>&nbsp;
                                        פרטים לחיוב זהים לפרטי המשלוח
                                    </label>
                                    <script>
                                        function handle_shame_as(e){
                                            document.getElementById('billing_address').style.display=e.checked?'none':'block'
                                        }
                                    </script>
                                    <div class="billing_address mt-2" id="billing_address" style="display:{{ old('sam_as_shipping')?'none':'block' }}">
                                        <?php 
                                            $name       = old('billing_name');
                                            $mobile     = old('billing_mobile');
                                            $email      = old('billing_email');
                                            $city       = old('billing_city');
                                            $zip_code   = old('billing_zip_code');
                                            if(auth()->user()){
                                                $name       = isset($billing_address->name)?$billing_address->name:NULL;
                                                $mobile     = isset($billing_address->mobile)?$billing_address->mobile:NULL;
                                                $email      = isset($billing_address->email)?$billing_address->email:NULL;
                                                $city       = isset($billing_address->city)?$billing_address->city:NULL;
                                                $zip_code   = isset($billing_address->zip_code)?$billing_address->zip_code:NULL;
                                            }
                                        ?>
                                        @include('manager/common/form/input',['type'=>'text','title'=>'שם מלא','name'=>'billing_name','value'=>$name])
                                        @include('manager/common/form/input',['type'=>'tel','title'=>'נייד','name'=>'billing_mobile','value'=>$mobile])
                                        @include('manager/common/form/input',['type'=>'email','title'=>'אימייל','name'=>'billing_email','value'=>$email])
                                        @include('manager/common/form/input',['type'=>'text','title'=>'עיר','name'=>'billing_city','value'=>$city])
                                        @include('manager/common/form/input',['type'=>'text','title'=>'מיקוד','name'=>'billing_zip_code','value'=>$zip_code])
                                    </div>
                                </div><!-- End billing address -->

                                <!-- Checkout -->
                                <button type="submit" class="btn btn-lg btn-yellow btn-block mt-4 {{ !auth()->user()?'register_continue':'continue' }}">
                                    {{ !auth()->user()?"הרשמה והמשך":"סיימתי" }}
                                </button>
                                <!-- End checkout -->
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="page_thumbnai top col-md-6">
                <img class="my-5" src="{{ asset('assets/frontend/img/page/contact.png') }}" alt="Register us" />
            </div>
        </div>
    </div>
</section>

@endsection
