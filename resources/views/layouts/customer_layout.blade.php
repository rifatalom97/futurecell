@extends('layouts.app')

@section('content')
<section class="page_area">
   <div class="container">
      <div class="row">
         <div class="col-md-3">
            <div class="customer_navigation_area shadow bg-white pt-3 px-2 pb-2">
               <div class="customer_account_header text-center ">
                  <div class="customer_image">
                     <svg class="bd-placeholder-img shadow bg-white" width="150" height="150" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="User image" preserveAspectRatio="xMidYMid slice" focusable="false" style="border-radius: 50%;">
                        <rect width="100%" height="100%" fill="#dddddd"></rect>
                     </svg>
                  </div>
                  <h3 class="mt-2 mb-4">{{ auth()->user()->name }}</h3>
                  <a class="btn btn-default btn-lg" href="{{ url('logout') }}">Logout</a>
               </div>
               <div class="customer_menus mt-4">
                    <div class="list-group">
                        <a class="list-group-item list-group-item-action py-4 {{ request()->is('my-account')?'active':'' }}" href="{{ url('my-account') }}">Dashboard</a>
                        <a class="list-group-item list-group-item-action py-4 {{ request()->is('my-account/orders')?'active':'' }}" href="{{ url('my-account/orders') }}">My orders</a>
                        <a class="list-group-item list-group-item-action py-4 {{ request()->is('my-account/shipping-address')?'active':'' }}" href="{{ url('my-account/shipping-address') }}">Shipping address</a>
                        <a class="list-group-item list-group-item-action py-4 {{ request()->is('my-account/billing-address')?'active':'' }}" href="{{ url('my-account/billing-address') }}">Billing address</a>
                        <a class="list-group-item list-group-item-action py-4 {{ request()->is('my-account/settings')?'active':'' }}" href="{{ url('my-account/settings') }}">Account</a>
                        <a class="list-group-item list-group-item-action py-4 {{ request()->is('my-account/password-update')?'active':'' }}" href="{{ url('my-account/password-update') }}">Change pasword</a>
                    </div>
               </div>
            </div>
         </div>

         <div class="col-md-9">
            <div class="customer_account_content_area">

                @yield('customerContent')
               
            </div>
         </div>
      </div>
   </div>
</section>
@endsection
