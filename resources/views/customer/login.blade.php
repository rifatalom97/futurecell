@extends('layouts.app')

@section('content')

<section class="page_area login_page">
   <div class="container">
      <div class="row">
         <div class="page_contents col-lg-6 col-md-6">
            <h3 class="page_title">התחברות</h3>
            <div class="page_content">
               <form class="{{ url('login') }}" method="post">
                  @csrf
                  <div class="row">
                     <div class="col-lg-7 col-md-9">
                        @include('manager/common/form/input',['title'=>'אימייל','type'=>'email','name'=>'email'])
                        @include('manager/common/form/input',['title'=>'סיסמה','type'=>'password','name'=>'password'])
                        <a href="{{ url('forgot-password') }}"><u>שכחת את הסיסמא?</u></a>
                        <button class="btn btn-yellow btn-block mt-3">התחברות</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>

         <div class="page_thumbnai col-lg-6 col-md-6">
            <img class="my-4" src="{{ asset('assets/frontend/img/page/contact.png') }}" alt="Login us">
         </div>
      </div>
   </div>
</section>

@endsection
