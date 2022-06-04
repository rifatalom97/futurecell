@extends('layouts.app')

@section('content')

<section class="page_area login_page">
   <div class="container">
      <div class="row">
         <div class="page_contents col-md-6">
            <h3 class="page_title">התחברות</h3>
            <div class="page_content">
               <form action="{{ url('register') }}" class="" method="post">
                   @csrf
                    <div class="row">
                        <div class="col-lg-7 col-md-9">
                           @include('manager/common/form/input',['title'=>'שֵׁם','type'=>'text','name'=>'name'])
                           @include('manager/common/form/input',['title'=>'אימייל','type'=>'email','name'=>'email'])
                           @include('manager/common/form/input',['title'=>'עִיר','type'=>'text','name'=>'city'])
                           @include('manager/common/form/input',['title'=>'מיקוד','type'=>'text','name'=>'zip_code'])
                           @include('manager/common/form/input',['title'=>'כתובת','type'=>'text','name'=>'address'])
                           @include('manager/common/form/input',['title'=>'טלפון','type'=>'tel','name'=>'phone'])
                           @include('manager/common/form/input',['title'=>'סיסמה','type'=>'password','name'=>'password'])
                           @include('manager/common/form/input',['title'=>'סיסמה','type'=>'password','name'=>'confirm_password'])
                           
                           <div class="form-group my-4">
                              <label class="mb-0">
                                 <input class="form-check-input" type="checkbox" name="isNewsletter" value="1"> &nbsp;
                                    <span class="mx-2">הירשם לניוזלטרים שלנו</span>
                              </label>
                           </div>

                           <button class="btn btn-yellow btn-block">הירשם</button>
                        </div>
                    </div>
               </form>
            </div>
         </div>
        <div class="page_thumbnai col-md-6">
             <img class="my-5" src="{{ asset('assets/frontend/img/page/contact.png') }}" alt="Register">
        </div>
      </div>
   </div>
</section>

@endsection
