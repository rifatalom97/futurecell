@extends('layouts.customer_layout')

@section('customerContent')
<div class="customer_billing_address">
   <h2 class="mt-0 mb-4"><b>Billing Address</b></h2>
   @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show mb-4 alert-custom"
            role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
   <form method="post" action="{{ url('/my-account/billing-address') }}">
      @csrf
      <!-- Billing address -->
      @include('manager/common/form/input',['title'=>'שם פרטי','type'=>'text','name'=>'name', 'value'=>$billing_address->name])
      <div class="row">
         <div class="col-md-6">
            @include('manager/common/form/input',['title'=>'טלפון','type'=>'tel','name'=>'mobile', 'value'=>$billing_address->mobile])
         </div>
         <div class="col-md-6">
            @include('manager/common/form/input',['title'=>'אימייל','type'=>'email','name'=>'email', 'value'=>$billing_address->email])
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            @include('manager/common/form/input',['title'=>'עִיר','type'=>'text','name'=>'city', 'value'=>$billing_address->city])
         </div>
         <div class="col-md-6">
            @include('manager/common/form/input',['title'=>'מיקוד','type'=>'text','name'=>'zip_code', 'value'=>$billing_address->zip_code])
         </div>
      </div>
      <!-- End billing address -->

      <!-- Save -->
      <button type="submit" class="btn btn-lg btn-yellow mt-4">Save</button>
      <!-- End Save -->
   </form>
</div>
@endsection
