<!DOCTYPE html>
<html id="html" lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{{ csrf_token() }}" >

    <!-- Seo metas -->
    <title></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- End -->

    <!-- Favicon -->
    <link rel="shortcut icon" href="" type="image/x-icon">
    <!-- end -->
   
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- jQuery js -->
    <script src="{{ asset('assets/jquery-3.3.1.min.js') }}"></script>
</head>

<body class="fixed_header">

    <div class="admin_area">
        <div class="admin_container clearfix">

            @include('manager.common.sidebar')

            <div class="admin_body">
                <div class="admin_body_inner">
                    
                    <div class="admin_body_inner_header clearfix">
                        <button class="btn btn-danger btn-sm">Menu</button>
                        <img class="float-right" src="{{ asset('/uploads/default/logo.png') }}" alt="logo">
                    </div>

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- main js -->
    <script src="{{ asset('assets/admin/js/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
</body>
</html>