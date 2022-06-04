@extends('layouts.app')

@section('content')


    @if(isset($home_data['banners']) && count($home_data['banners']))
    <section class="banner home_banner owl-carousel owl-theme"  dir="ltr">
        @foreach($home_data['banners'] as $banner)
            <div class="home_banner_item">
                <a href="{{ ($banner->url?:'#') }}">
                    <img class="home_banner_item_img" src="{{ asset('uploads/'.$banner->new) }}" alt="" height="586" width="1920"/>
                </a>
            </div>
        @endforeach
    </section>
    @endif 


    @if(isset($home_data['grid_category'])&&$home_data['grid_category'])
    @php($grid_category = $home_data['grid_category'])
    @if(count($grid_category))
    <section class="category_grid mt-4">
        <div class="container">
            @if(count($grid_category))
            <div class="row mb-4">
                @foreach($grid_category as $item)
                @if($loop->iteration>2)
                @break
                @endif
                <div class="col-md-6 col-sm-12">
                    <div class="category_grid_item">
                        <a href="{{ url('store?category='.$item->category->slug) }}" alt="{{ $item->category->meta->title }}">
                            <img src="{{ asset('uploads/'.$item->image) }}" alt="{{ $item->category->meta->title }}" width= height="316" />
                            <h3>{{ $item->category->meta->title }}</h3>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <!-- /img/img/category/WiredCam_banner.png -->
            
            @if(count($grid_category)>2)
            <div class="row">
                @foreach($grid_category as $item)
                @if($loop->iteration<3)
                @continue
                @endif
                <div class="col-md-4 col-sm-12">
                    <div class="category_grid_item">
                        <a href="{{ url('store?category='.$item->category->slug) }}" alt="{{ $item->category->meta->title }}">
                            <img src="{{ $item->image?asset('uploads/'.$item->image):asset('assets/frontend/img/category/WiredCam_banner.png') }}" alt="{{ $item->category->meta->title }}" width= height="316" />
                            <h3>{{ $item->category->meta->title }}</h3>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </section>
    @endif 
    @endif 

    @if(isset($home_data['hot_products'])&&$home_data['hot_products']&&count($home_data['hot_products']->products))
    <section class="product_carousel section">
        <div class="container">
            <div class="section_title_container text-center clearfix">
                <h2>מוצרים חמים</h2>
            </div>
        </div>
                
        <div class="section_content">
            <div class="container text-center">
                <div class="product_carousel_items owl-carousel owl-theme" dir="ltr">
                    @foreach($home_data['hot_products']->products as $product)
                    <div class='each_product text-center'>
                        <div class="each_product_head text-center">
                            <img src="{{ asset("uploads/{$product->thumbnail}") }}" height="294" alt="{{ $product->title }}"/>
                        </div>
                        <div class="each_product_details">
                            <h4 class="epd_title">
                            {{ $product->title }}
                            </h4>
                            <!-- {/* <p class="epd_short_description">{{ $product->title }}</p> */} -->
                            <p class="epd_short_description">{{ $product->short_description }}</p>
                            <p class="epd_price">
                                @if($product->regular_price)
                                <span class="epd_regular_price"><del><span>₪</span>{{ $product->regular_price }}</del></span>
                                @endif
                                <span class="epd_sell_price"><span>₪</span>{{ $product->price }}</span>
                            </p>
                        </div>
                        <div class="each_product_footer">
                            <a class="btn btn-yellow btn-big add_to_cart" href="{{ url("store/{$product->slug}") }}">הוסף לסל</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(isset($home_data['brands'])&&$home_data['brands']->show=='true'&&count($home_data['brands']->items))
    <section class="leading_brands pb-5 section">
        <div class="container">
            <div class="section_title_container text-center clearfix">
                <h2>מותגים מובילים</h2>
            </div>
        </div> 
        <div class="section_content">
            <div class="container text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-12">
                        <div class="leading_brands_container">
                            @foreach($home_data['brands']->items as $item)
                            <a href="{{ url("store?brand={$item->slug}") }}" title="{{ $item->meta->title }}" class="leading_brands_item">
                                <img src="{{ asset("uploads/{$item->image}") }}" alt="{{ $item->meta->title }}" />
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif 
    
    @if(isset($home_data['about'])&&$home_data['about'])
    <section class="about_us pb-5 section">
        <div class="container">
            <div class="section_title_container text-center clearfix">
                <h2>אודות</h2>
            </div>
        </div>
        <div class="section_content">
            <div class="container text-center" style="max-width: 600px">
                {!! nl2br($home_data['about']->details->{app()->getLocale()}) !!}
            </div>
        </div>
    </section>
    @endif


@endsection
