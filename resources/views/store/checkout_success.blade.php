@extends('layouts.app')

@section('content')

<section class="page_area response_page">
    <div class="container">
        <div class="response_page_content text-center">
            <img src="{{ url('/assets/frontend/img/success_circle.svg') }}" width="100" height="100"/>
            <h2 class="response_title mt-4" style="color:#808080">Order placed successfully</h2>
        </div>
    </div>
</section>

@endsection
