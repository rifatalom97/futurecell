@extends('layouts.customer_layout')

@section('customerContent')
<div class="customer_dashboard">
   <div class="row mb-5">
      <div class="col-md-6">
         <div class="card shadow bg-white p-1">
            <div class="card-body">
               <h6 class="card-subtitle text-muted your-balance">האיזון שלך</h6>
               <h2 class="card-title mb-0" style="font-size: 48px;"><b>{{ $balance->balance }} ₪</b></h2>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card shadow bg-white p-1">
            <div class="card-body">
               <h6 class="card-subtitle text-muted">הנקודות שלך</h6>
               <h2 class="card-title mb-0" style="font-size: 48px;"><b> {{ $points->points }} </b></h2>
            </div>
         </div>
      </div>
   </div>
   <div class="row mb-5"></div>
   @if(isset($products)&&count($products))
   <div class="customer_dashboard_products mt-5">
      <div class="section_title_container clearfix text-auto">
         <h2 class="m-0">Our products</h2>
      </div>

      <div class="customer_products">
         <div class="row">
            @foreach($products as $product)
               <div class="col-md-4 col-xs-6 mb-4">
                  <div class='each_product text-center'>
                        <div class="each_product_head text-center">
                           <img src="{{ asset('uploads/'.$product->thumbnail) }}" height="294" alt="{{ $product->meta->title }}" />
                        </div>
                        <div class="each_product_details">
                           <!-- <img class="product_mini_brand_image" src="" height={45}/> -->

                           <h4 class="epd_title">{{ $product->meta->title }}</h4>
                           <!-- {/* <p class="epd_short_description">{title}</p> */} -->
                           <p class="epd_short_description">{{ $product->meta->title }}</p>
                           <p class="epd_price">
                              @if($product->regular_price)
                              <span class="epd_regular_price"><del><span>₪</span>{{ $product->retular_price }}</del></span>
                              @endif
                              <span class="epd_sell_price"><span>₪</span>{{ $product->price }}</span>
                           </p>
                        </div>
                        <div class="each_product_footer">
                           <a class="btn btn-yellow btn-big add_to_cart" href="{{ url('store/'.$product->slug) }}">הוסף לסל</a>
                        </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </div>
   @endif;
</div>
@endsection
