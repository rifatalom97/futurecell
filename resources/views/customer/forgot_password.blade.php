@extends('layouts.app')

@section('content')

<section class="page_area forgot_password_page">
   <div class="container">
      <div class="row justify-content-md-center">
         <div class="page_contents col-md-3 ">
            <div class="page_content">
               
               <form action="{{ url('forgot-password') }}" method="post">
                  @csrf 

                  @if(!Session::has('otp') && !Session::has('access'))
                  <div class="row">
                     <div class="col-md-12">
                        @include('manager/common/form/input',['title'=>'אימייל','type'=>'email','name'=>'email', 'value'=>old('email')])
                        <button class="btn btn-blue btn-block mt-3">SEND OTP</button>
                     </div>
                  </div>
                  @endif 

                  @if(Session::has('otp') && !Session::has('access'))
                  <div class="row">
                     <div class="col-md-12">
                        @include('manager/common/form/input',['title'=>'OTP','type'=>'text','name'=>'otp'])
                        <button class="btn btn-blue btn-block mt-3">SUBMIT OTP</button>
                     </div>
                  </div>
                  @endif 

                  @if(!Session::has('otp') && Session::has('access'))
                  <div class="row">
                     <div class="col-md-12">
                        @include('manager/common/form/input',['title'=>'New password','type'=>'password','name'=>'password'])
                        @include('manager/common/form/input',['title'=>'Confirm password','type'=>'password','name'=>'confirm_password'])
                        <button class="btn btn-blue btn-block mt-3">Update password</button>
                     </div>
                  </div>
                  @endif 

               </form>

            </div>
         </div>
      </div>
   </div>
</section>

@endsection
