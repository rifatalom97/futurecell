@extends('layouts.customer_layout')

@section('customerContent')
<div class="customer_account_settings">

   <h2 class="mt-0 mb-4"><b>Account settings</b></h2>
   @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show mb-4 alert-custom"
            role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

   <form method="POST" action="{{ url('/my-account/settings') }}">
         <!-- account settings -->
         @include('manager/common/form/input',['title'=>'שם פרטי','type'=>'text','name'=>'name', 'value'=>$user->name])
         
         <div class="row">
            <div class="col-md-6">
               @include('manager/common/form/input',['title'=>'טלפון','type'=>'tel','name'=>'mobile', 'value'=>$user->mobile])
            </div>
            <div class="col-md-6">
               @include('manager/common/form/input',['title'=>'אימייל','type'=>'email','name'=>'email', 'value'=>$user->email])
            </div>
         </div>

         @include('manager/common/form/input',['title'=>'Company (optional)','type'=>'text','name'=>'company', 'value'=>$user->company])
         @include('manager/common/form/input',['title'=>'Address','type'=>'text','name'=>'address', 'value'=>$user->address])

         <div class="row">
            <div class="col-md-6">
               @include('manager/common/form/input',['title'=>'עִיר','type'=>'text','name'=>'city', 'value'=>$user->city])
            </div>
            <div class="col-md-6">
               @include('manager/common/form/input',['title'=>'מיקוד','type'=>'text','name'=>'zip_code', 'value'=>$user->zip_code])
            </div>
         </div>
         <!-- End account settings -->


         <!-- Checkout -->
         <button type="submit" class="btn btn-lg btn-yellow mt-4">Save</button>
         <!-- End checkout -->
   </form>
</div>
@endsection
