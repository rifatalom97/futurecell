@extends('layouts.app')

@section('content')

    @if(isset($page_data['banners']) && count($page_data['banners']))
    <section class="banner home_banner owl-carousel owl-theme"  dir="ltr">
        @foreach($page_data['banners'] as $banner)
            <div class="home_banner_item">
                <a href="{{ ($banner->url?:'#') }}">
                    <img class="home_banner_item_img" src="{{ asset('uploads/'.$banner->new) }}" alt="" height="586" width="1920"/>
                </a>
            </div>
        @endforeach
    </section>
    @endif 

    <!-- breadcrumb -->
    <section class="breadcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xl-6">
                    <ul class="breadcrumb mt-2">
                        <li><a href="{{ url('/') }}">עמוד הבית</a></li>
                        <li>/</li>
                        <li>מוצרים</li>
                    </ul>
                </div>

                <div class="col-md-6 col-sm-6 col-xl-6">
                    <div class="sort">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="dropdown sort_by">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    מיון לפי
                                    </button>
                                    <div class="dropdown-menu">
                                        @php 
                                            $query_string   = (isset($_SERVER['QUERY_STRING'])? $_SERVER['QUERY_STRING']:'');
                                            $sor_val        = isset($_GET['sort_by'])?$_GET['sort_by']:'';
                                            $query_string   = str_replace(['&sort_by='.$sor_val,'sort_by='.$sor_val,'sort_by='.$sor_val.'&'],'',$query_string);
                                            $query_string   = $query_string?$query_string . '&' : '';
                                        @endphp
                                        <a href="{{ url('/store?'.$query_string.'sort_by=new') }}" class="dropdown-item {{ $sor_val=='new'?'active':'' }}">Newly added</a>
                                        <a href="{{ url('/store?'.$query_string.'sort_by=A-to-Z') }}" class="dropdown-item {{ $sor_val=='A-to-Z'?'active':'' }}">Name - A to Z</a>
                                        <a href="{{ url('/store?'.$query_string.'sort_by=Z-to-A') }}" class="dropdown-item {{ $sor_val=='Z-to-A'?'active':'' }}">Name - Z to A</a>
                                        <a href="{{ url('/store?'.$query_string.'sort_by=Low-to-High') }}" class="dropdown-item {{ $sor_val=='Low-to-High'?'active':'' }}">Low to high</a>
                                        <a href="{{ url('/store?'.$query_string.'sort_by=High-to-Low') }}" class="dropdown-item {{ $sor_val=='High-to-Low'?'active':'' }}">High to low</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End breadcrumb -->

    @include('store.productFilter')

    <section class="store_products_container">
        
        <div class="container">
            <div class="products">
                @if($products && count($products))
                <div class="row" id="products">
                    @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-xs-6 mb-4">
                        <div class='each_product text-center'>
                            <div class="each_product_head text-center">
                                <img src="{{ asset('uploads/'.$product->thumbnail) }}" height="294" alt="{{ $product->title }}" />
                            </div>
                            <div class="each_product_details">
                                <!-- <img class="product_mini_brand_image" src="" height={45}/> -->

                                <h4 class="epd_title">{{ $product->title }}</h4>
                                <!-- {/* <p class="epd_short_description">{title}</p> */} -->
                                <p class="epd_short_description">{{ $product->title }}</p>
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
                @else 
                <h3 class="no_product_title">No product found to show</h3>
                @endif
            </div>
            
            @if($total_count > 12)
            <div class="load_more_products text-center mb-4 pt-4">
                @php( $query_string = (isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'') )
                <button type="button" onClick="load_more_products(this)" data-action="{{ url("get-ajax-products?{$query_string}") }}" data-offset="12">טען עוד</button>
            </div>
            @endif
        </div>

    </section>

@endsection
