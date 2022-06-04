@extends('layouts.customer_layout')

@section('customerContent')
<div class="customer_password_change_page">
   <div class="password_change_container">
         
         @if(!Session::has('otp')&&!Session::has('otp_verified'))
         <div style="display:block">
            <form action="{{ url('/my-account/password-update') }}" method="post">
               @csrf
               <button class="btn btn-primary btn-block btnl-lg cm_pass_update_start">
                  Send password change request
               </button>
            </form>
         </div>
         @endif

         @if(Session::has('otp'))
         <div class="otp_receiver_area" style="display:block">
            <form action="{{ url('/my-account/password-update') }}" method="post">
               @csrf 
               <div class="otp_messages text-center">Please check your email</div>
               @include('manager/common/form/input',['title'=>'','type'=>'text','name'=>'otp', 'value'=>''])
               <button class="btn btn-primary btn-block btnl-lg otp_submit">
                  Submit
               </button>
            </form>
         </div>
         @endif

         @if(Session::has('otp_verified'))
         <div class="otp_receiver_area" style="display:block">
            <form action="{{ url('/my-account/password-update') }}" method="post">
               @csrf 
               @include('manager/common/form/input',['title'=>'Current password','type'=>'password','name'=>'current_password'])
               @include('manager/common/form/input',['title'=>'New password','type'=>'password','name'=>'new_password'])
               @include('manager/common/form/input',['title'=>'Confirm password','type'=>'password','name'=>'confirm_password'])
               <button class="btn btn-primary btn-block btnl-lg otp_submit">
                  Update password
               </button>
            </form>
         </div>
         @endif
   </div>
</div>
@endsection
