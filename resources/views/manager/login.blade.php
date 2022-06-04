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
</head>

<body id="body" class="fixed_header">

    <!-- Login box -->
    <div class="container-fluid login_container">
        <div class="modal-content modal_content">
            <div class="modal-header flex-center">
                <h5 class="modal-title">Admin Login</h5>
            </div>
            <div class="modal-body">
                <form action="<?= url('/manager/login'); ?>" method="POST">
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input class="form-control" type="text" name="email" placeholder="Email address" required>
                            @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
    <!-- End login box -->

    <!-- React JS -->
    <script src="{{ asset('assets/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
</body>
</html>