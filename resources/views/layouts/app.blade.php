<!doctype html>
@php($current_lang = app()->getLocale())
<html lang="{{ str_replace('_', '-', $current_lang) }}" dir="{{ $current_lang=='he'?'rtl':'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <?php
        $default = $header_footer['default'];
        $metaTags = $header_footer['metaTags'];

        $title = config('app.name', 'Laravel');
        if(isset($default['site_name'])){
            $title = $default['site_name'];
        }
        if(isset($metaTags->metaTitle)){
            $title = $metaTags->metaTitle;
        }
    ?>
    <title>{{ $title }}</title>
    @if(isset($metaTags->metaKeywords))
    <meta name="keywords" content="{{ $metaTags->metaKeywords }}">
    @endif
    @if(isset($metaTags->metaDescription))
    <meta name="description" content="{{ $metaTags->metaDescription }}">
    @endif
    @if(isset($default['favicon']))
    <link rel="shortcut icon" href="{{ asset("uploads/{$default['favicon']}") }}" type="image/x-icon">
    @endif


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Scripts only for home -->
    @if(Route::has('home'))
        <link href="{{ asset('assets/owl_carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/owl_carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    @endif
    <!-- end -->

    <!-- Styles -->
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header_top bg-white">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-6">
                        <ul class="clearList inline">
                            <li>
                                @php($cart_count = (isset($header_footer['carts'])?count($header_footer['carts']):0))
                                <button id="show_minicart" class="show_minicart" type="button" onClick="show_minicart()" title="<?= $cart_count?'Show cart':'No cart items'; ?>">
                                    <img src="{{ asset('assets/frontend/img/shopping_cart.svg') }}" alt="Show cart">
                                </button>
                                <span class="cart_counter bg-yellow" style="display:{{ $cart_count? "block":'none' }}">{{ $cart_count }}</span>
                                <div id="minicart" class="minicart_area {{ ($cart_count?'has_items':'') }}" aria-label="Cart items" style="display: none;">
                                    <div class="minicart_header bg-yellow text-center py-3 px-3">
                                        <p class="mb-0">עגלת קניות<strong><span>{{ $cart_count }}</span> מוצרים</strong></p>
                                    </div>
                                    <div class="minicart_container py-3 px-3">
                                        <table>
                                            <tbody>
                                                @php($total = 0)
                                                @if( $cart_count )
                                                    @foreach( $header_footer['carts'] as $item )
                                                    @if(!$item->product)
                                                    @continue
                                                    @endif
                                                    @php($total += (double)$item->product->price)
                                                    <tr id="minicart_product_{{ $item->product->id }}">
                                                        <td class="mn_thumbnail">
                                                            @if($item->product->thumbnail)
                                                            <img src="{{ asset('uploads/'.$item->product->thumbnail) }}" alt="{{ $item->product->meta->title }}">
                                                            @else 
                                                            <img src="{{ asset('uploads/'.$item->product->thumbnail) }}" alt="{{ $item->product->meta->title }}">
                                                            @endif 
                                                        </td>
                                                        <td class="mn_title">
                                                            <b>{{ $item->product->meta->title }}</b>
                                                        </td>
                                                        <td class="mn_price">
                                                            <span>₪</span> {{ $item->product->price }}
                                                        </td>
                                                        <td class="mn_remove">
                                                            <img src="{{ asset('assets/frontend/img/cross_icon.svg') }}" height="16" width="16" class="pointer" data-id="{{ $item->product->id }}" data-action="{{ url('/remove-cart-item') }}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="minicart_footer px-3">
                                        <div class="minicart_footer_top bg-light-gray text-center py-3">
                                            <p class="mb-0">סה״כ לתשלום<strong>₪<span>{{ $total }}</span></strong></p>
                                        </div>
                                        <div class="minicart_footer_bottom py-3">
                                            <a class="btn btn-default bg-yellow" href="{{ url('/cart') }}">מעבר לרכישה</a>
                                            <button class="btn btn-default" type="button" onClick="hide_minicart()">המשך קניה באתר</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                @auth 
                                <a class="d-flex" href="/my-account" title="My account">
                                    <img src="{{ asset('assets/frontend/img/user_icon.svg') }}" alt="Account">
                                </a>
                                @else
                                <a class="d-flex" href="/login" title="Login">
                                    <img src="{{ asset('assets/frontend/img/user_icon.svg') }}" alt="Login">
                                </a>
                                @endauth
                            </li>
                            <li>
                                <button id="search_box_handler" onClick="show_search_box()" title="Search">
                                    <img src="{{ asset('assets/frontend/img/search_handler.svg') }}" alt="">
                                </button>
                                <div id="search_box" class="search_box" style="display: none;">
                                    <div class="fade_area" onClick="hide_search_box()"></div>
                                    <div class="search_box_form">
                                        <div class="search_area">
                                            <form action="{{ url("/store") }}" method="get">
                                                <div class="input-group input-group-lg mb-0">
                                                    <input name="search" type="text" class="form-control" placeholder="Search keywords" aria-label="Search products">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-yellow" arai-lable="Submit to search">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <a aria-current="page" class="active" href="{{ url('/') }}">
                            <img src="{{ asset((isset($default['logo'])?'uploads/'.$default['logo']:'assets/frontend/img/logo.svg')) }}" alt="logo">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if( isset($header_footer['header']['menus']->items) && count($header_footer['header']['menus']->items))
        <!-- Menus -->
        <div class="main_menu_area">
            <div class="container">
                <div class="row">
                    <nav class="navbar navbar-expand-lg navbar-dark">
                        <button onClick="openNavMenu()" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navMenu">
                            <ul class="navbar-nav" aria-label="Main navigation menu">
                                @foreach($header_footer['header']['menus']->items as $menu_item)
                                <li class="nav-item">
                                    <a href="{{ $menu_item->url }}" class="nav-link">{{ $menu_item->label->{$current_lang} }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- end menus -->
        @endif 
    </header><!-- End header -->


    <!-- content -->
    <div class="main">
        @yield('content')
    </div><!-- End content -->

    <?php 
        // dd($header_footer['footer']);
    ?>
    @if(isset($header_footer['footer']))
    @php($footer = $header_footer['footer'])
    <!-- Footer -->
    <footer class="footer">

        @if(isset($footer['securities']->items))
        <!-- securities -->
        <div class="footer_securities_area py-4">
            <div class="container">
                <div class="footer_securities">
                    @foreach($footer['securities']->items as $item)
                    <div class='security'>
                        <img src="{{ asset('uploads/'.$item->image) }}" />
                    </div>
                    @endforeach
                </div>
            </div>
        </div><!-- end securities -->
        @endif

        @if(isset($footer['menus']))
        <!-- Menus -->
        <div class="footer_menu_area bg-black">
            <div class="container">
                <div class="row">
                    
                    @if(isset($footer['menus']->group1->items)&&count($footer['menus']->group1->items))
                    <!-- Group 1 -->
                    <div class="col-md-2">
                        <ul class="clearList footer_menu">
                            @foreach($footer['menus']->group1->items as $menu_item)
                            <li>
                                <a href="{{ $menu_item->url }}">{{ $menu_item->label->{app()->getLocale()} }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div><!-- Group 1 -->
                    @endif


                    @if(isset($footer['menus']->group2->items)&&count($footer['menus']->group2->items))
                    <!-- Group 2 -->
                    <div class="col-md-3">
                        <ul class="clearList footer_menu">
                            @foreach($footer['menus']->group2->items as $menu_item)
                            <li>
                                <a href="{{ $menu_item->url }}">{{ $menu_item->label->{app()->getLocale()} }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div> <!-- group 2 -->
                    @endif

                    @if(isset($footer['menus']->group3->items)&&count($footer['menus']->group3->items))
                    <!-- Group 3 -->
                    <div class="col-md-3">
                        <ul class="clearList footer_menu">
                            @foreach($footer['menus']->group3->items as $menu_item)
                            <li>
                                <a href="{{ $menu_item->url }}">{{ $menu_item->label->{app()->getLocale()} }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div><!-- Group 3 -->
                    @endif

                    @if(isset($footer['menus']->group4->items)&&count($footer['menus']->group4->items))
                    <!-- Group 4 -->
                    <div class="col-md-3">
                        <ul class="clearList footer_menu">
                            @foreach($footer['menus']->group4->items as $menu_item)
                            <li>
                                <a href="{{ $menu_item->url }}">{{ $menu_item->label->{app()->getLocale()} }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div><!-- Group 4 -->
                    @endif

                    @if(isset($footer['menus']->group5->items)&&count($footer['menus']->group5->items))
                    <!-- Group 5 -->
                    <div class="col-md-1">
                        <ul class="clearList footer_menu">
                            @foreach($footer['menus']->group5->items as $menu_item)
                            <li>
                                <a href="{{ $menu_item->url }}">{{ $menu_item->label->{app()->getLocale()} }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div><!-- Group 5 -->
                    @endif

                </div>
            </div>
        </div>
        <!-- End menus -->
        @endif


        @if(isset($footer['delivery']))
        <div class="footer_delivery_area bg-light-gray">
            <div class="container text-center">
                @php($delivery = $footer['delivery'])
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        @if(isset($delivery->first))
                        <div class="footer_delivery_step">
                            @if($delivery->first->image)
                            <img src="{{ asset('uploads/'.$delivery->first->image) }}" alt="">
                            @endif
                            <p class="mb-0 mt-2">{{ $delivery->first->text->{$current_lang} }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4">
                        @if(isset($delivery->second))
                        <div class="footer_delivery_step">
                            @if($delivery->second->image)
                            <img src="{{ asset('uploads/'.$delivery->second->image) }}" alt="">
                            @endif
                            <p class="mb-0 mt-2">{{ $delivery->second->text->{$current_lang} }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4">
                        @if(isset($delivery->third))
                        <div class="footer_delivery_step">
                            @if($delivery->third->image)
                            <img src="{{ asset('uploads/'.$delivery->third->image) }}" alt="">
                            @endif
                            <p class="mb-0 mt-2">{{ $delivery->third->text->{$current_lang} }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($footer['copyright']))
        <div class="footer_copyright_area bg-white">
            <div class="container">
                @php($copyright = $footer['copyright'])
                <div class="row">
                    <div class="col-md-4 col-sm-5 footer_copyrihgt_social">
                        <ul class="clearList">
                            @if(isset($copyright->social->facebook_url))
                                <li><a href="{{ $copyright->social->facebook_url }}"><img src="{{ asset('assets/frontend/img/facebook_icon.svg') }}" alt="Facebook"></a></li>
                            @endif
                            @if(isset($copyright->social->instagram_url))
                                <li><a href="{{ $copyright->social->instagram_url }}"><img src="{{ asset('assets/frontend/img/instagram_icon.png') }}" alt="Instagram"></a></li>
                            @endif
                            @if(isset($copyright->social->mailto))
                                <li><a href="mailto:{{ $copyright->social->mailto }}"><img src="{{ asset('assets/frontend/img/message_icon.svg') }}" alt="Mailto at {{ $copyright->social->mailto }}"></a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-8 col-sm-7">
                        <p class="mb-0">{{ $copyright->text->$current_lang }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </footer><!-- End footer -->
    @endif


    <!-- Scripts -->
    <script src="{{ asset('assets/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/jquery-migrate.min.js') }}"></script>
    @if(Route::has('home'))
        <script src="{{ asset('assets/owl_carousel/owl.carousel.min.js') }}"></script>
    @endif
    <script src="{{ asset('assets/frontend/js/main.js') }}"></script>
</body>
</html>
