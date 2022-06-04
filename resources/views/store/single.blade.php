@extends('layouts.app')

@section('content')

    <section class="single_product">
        <div class="product_details_top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        @if($securities && count($securities))
                        <div class="product_securities">
                            @foreach($securities as $security)
                                <img src="{{ asset('uploads/'.$security->image) }}"/>
                            @endforeach
                        </div>
                        @endif

                        <div class="product_ddetails">

                            <h2 class="product_title mb-3">{{ $product->meta->title }}</h2>

                            <h2 class="product_price">
                                @if($product->regular_price)
                                <span class="regular_price d-inline-block"><del>₪{{ $product->regular_price }}</del></span>
                                @endif 
                                <span class="price d-inline-block">₪ {{ $product->price }}</span>
                            </h2>

                            <div class="product_description">
                                {{ $product->description }}
                            </div>

                            <div class="product_action mt-5 mb-4">
                                <form action="{{ url("/add-to-cart") }}" method="post" id="add_to_cart" style="display:{{ !$product->cart?'block' : 'none' }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="number" value="1" class="form-control" min="1" name="quantity"/>
                                    <button class="cart_button btn btn-transparent-red br-0">הוסף לסל</button>
                                </form>
                                <a class="cart_page_button btn btn-yellow br-0" href="{{ url('/cart') }}" style="display:{{ $product->cart?'block' : 'none' }}">קנה עכשיו</a>
                            </div>

                            @if( $credit_info )
                            @if( isset($credit_info->title->{app()->getLocale()}) )
                            <h4 class="credit_free_payment"><b>{{ $credit_info->title->{app()->getLocale()} }}</b></h4>
                            @endif 
                            @if( isset($credit_info->details->{app()->getLocale()}) )
                            <h4>{{ $credit_info->details->{app()->getLocale()} }}</h4>
                            @endif
                            @endif

                            @if($delivery_methods)
                            <div class="mt-5">
                                <h4><b>אפשרויות משלוח</b></h4>
                                <ul class="delivery_options_list">
                                    @foreach($delivery_methods as $method)
                                        <li>{{ $method->meta->title . ' - ' . $method->amount . '₪' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="product_gallery">
                            @php($gallery = json_decode($product->gallery))
                            @if(count($gallery))
                            <div class="big_image">
                                <img src="{{ asset('uploads/'.$gallery[0]->image) }}" style="opacity:1" alt="" height="350"/>
                            </div>
                            <div class="thumbs owl-carousel owl-theme" dir="ltr">
                                @foreach($gallery as $item)
                                    <div class="single_thumb" >
                                        <img src="{{ asset('uploads/'.$item->image) }}" onClick="show_bing_one(this)" height="100" width="100"/>
                                    </div>
                                @endforeach
                            </div>
                            @else 
                            <div class="big_image">
                                <img src="{{ asset('uploads/'.$product->thumbnail) }}" style="opacity:1" alt="" height="350"/>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @php($options = json_decode($product->options))

        @if($options && isset($options->show_reviews) && $options->show_reviews == 'true')
        <div class="clearfix"></div>
        <div class="product_options_details">
            <div class="container">
                <div class="section_title_container text-center clearfix">
                    <h2>סקירה</h2>
                </div>

                @if( isset($options->video->show) && $options->video->show == 'true')
                <div class="section_video_container clearfix text-center ">
                    <!-- https://www.youtube.com/embed/ -->
                    
                    <iframe width="750" height="420" src="{{ isset($options->video->url)?$options->video->url:'' }}" title="YouTube video player" frameBorder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowFullScreen></iframe>
                    @if( isset($options->video->details->{app()->getLocale()}) )
                    <div class="video_details">
                        <p>{{ $options->video->details->{app()->getLocale()} }}</p>
                    </div>
                    @endif 
                </div>
                @endif

                @if( isset($options->gridGalleryReviews->items) && count($options->gridGalleryReviews->items))
                <div class="section_grid_gallery_container">
                    @foreach( $options->gridGalleryReviews->items as $item )
                    <div class="grid_gallery_item">
                        @if(isset($item->thumb1)&&$item->thumb1)
                        <div class="row">
                            <div class="col-md-12 thumbnail">
                                <img src="{{ asset("uploads/{$item->thumb1}") }}">
                            </div>
                        </div>
                        @endif 

                        @if((isset($item->thumb2)&&$item->thumb2)||(isset($item->thumb3)&&$item->thumb3))
                        <div class="row">
                            @if(isset($item->thumb2)&&$item->thumb2)
                            <div class="col-md-6 mini_thumbnail">
                                <img src="{{ asset("uploads/{$item->thumb2}") }}">
                            </div>
                            @endif
                            @if(isset($item->thumb3)&&$item->thumb3)
                            <div class="col-md-6 mini_thumbnail">
                                <img src="{{ asset("uploads/{$item->thumb3}") }}">
                            </div>
                            @endif
                        </div>
                        @endif 
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endif
    </section>

@endsection
