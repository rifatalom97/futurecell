@extends('layouts.app')

@section('content')
<section class="page_area contact_page">
   <div class="container">
      <div class="row">
         <div class="page_contents col-md-6">

            <h3 class="page_title">צור קשר</h3>
            @php 
            $filterd_subjects = array();
            if(isset($options['subjects'])&&count($options['subjects'])){
               foreach($options['subjects'] as $subject){
                  $filterd_subjects[$subject->{app()->getLocale()}]  = $subject->{app()->getLocale()};
               }
            }
            @endphp

            <div class="page_content">
               <form action="{{ url('contact') }}" method="post">
                  @csrf
                  <div class="row">
                     <div class="col-md-7">
                        @if(Session::has('message'))
                           <div class="alert alert-success alert-dismissible fade show mt-4 alert-custom"
                                 role="alert">
                                 {{ Session::get('message') }}
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button>
                           </div>
                        @endif

                        @include('manager.common.form.input',['title'=>'שֵׁם','type'=>'text','name'=>'name','value'=>old('name')])
                        
                        @include('manager.common.form.input',['title'=>'נייד','type'=>'tel','name'=>'mobile','value'=>old('mobile')])
                        
                        @include('manager.common.form.input',['title'=>'אימייל','type'=>'email','name'=>'email','value'=>old('email')])
                        
                        @include('manager.common.form.select',['title'=>'נושא הפניה','name'=>'subject','options'=>$filterd_subjects,'value'=>old('subject')])

                     </div>
                     <div class="col-md-12">
                        @include('manager.common.form.textarea',['title'=>'תוכן ההודעה','name'=>'message','value'=>old('message')])
                     </div>
                     <div class="col-md-6">
                        <button class="btn btn-yellow btn-block">המשך</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="page_thumbnai col-md-6">
            <img class="my-4" src="{{ asset('assets/frontend/img/page/contact.png') }}" alt="Contact us">
         </div>
      </div>
   </div>
</section>
@endsection
