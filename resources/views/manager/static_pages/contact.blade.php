@extends('manager.common.layout')
@section('content')
<div class="pages">
    <form action="{{ url('/manager/static-pages/contact') }}" method="post">    
        @csrf
        
        @include('manager.common.sessionMessage')

        @php($subjects = isset($options['subjects'])?$options['subjects']:NULL)
        
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Subject</b></h2>
            </div>
            <div class="admin_info_box_body">
                <div id="subject_container">
                    @if($subjects && count($subjects))
                    @foreach($subjects as $subject)
                    <div class="each_subject border border-dark p-2 mb-2">
                        <button type="button" class="remove_subject btn btn-sm btn-danger">Remove</button>
                        @foreach(config_languages() as $lang => $language)
                        <div class="{{ $lang }}" style="display:{{ $lang==config_lang()?'block':'none' }}">
                            <div class="form-group">
                                <label for="">Subject</label>
                                <input class="form-control" name="subjects[0][{{$lang}}]" value="{{ isset($subject->$lang)?$subject->$lang:'' }}"/>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                    @else 
                    <div class="each_subject border border-dark p-2 mb-2">
                        <button type="button" class="remove_subject btn btn-sm btn-danger">Remove</button>
                        @foreach(config_languages() as $lang => $language)
                        <div class="{{ $lang }}" style="display:{{ $lang==config_lang()?'block':'none' }}">
                            <div class="form-group">
                                <label for="">Subject</label>
                                <input class="form-control" name="subjects[0][{{$lang}}]" value="{{ isset($expartise->title->$lang)?$expartise->title->$lang:'' }}"/>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>

                <button class="btn btn-dark mt-2" type="button" onClick="addSubject()">Add subject</button>
                <script>
                    function addSubject(e){
                        let id = $('#subject_container .each_subject').length;
                        let html = '<div class="each_subject border border-dark p-2 mb-2">';
                        html    += '<button type="button" class="remove_subject btn btn-sm btn-danger">Remove</button>';
                        @foreach(config_languages() as $lang => $language)
                        html += '<div class="{{ $lang }}" style="display:{{ $lang==config_lang()?'block':'none' }}">';
                            html += '<div class="form-group">';
                            html += '<label for="">Subject</label>';
                            html += '<input class="form-control" name="subjects['+id+'][{{$lang}}]" value="{{ isset($expartise->title->$lang)?$expartise->title->$lang:'' }}"/>';
                            html += '</div>';
                            html += '</div>';
                        @endforeach
                        html += '</div>';
                        $('#subject_container').append(html);
                    }
                    $(document).on('click','.remove_subject',()=>{
                        let _t = $(this).parent();
                        if(confirm("Do you want to really remove this subject?")){
                            _t.remove();
                        }
                    });
                </script>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Settings</b></h2>
            </div>
            <div class="admin_info_box_body">
                @php($admin_notification = isset($options['admin_notification'])?$options['admin_notification']:NULL)
                <div class="form-group">
                    <label>
                        <input {{ isset($admin_notification->status)&&$admin_notification->status=='1'?'checked':'' }}  id="contact_notification_handler" name="admin_notification[status]" value="1" type="checkbox"/> 
                        <span>Get contact notification</span>
                    </label>
                </div>
                <div class="notification_email_container" style="display:{{ isset($admin_notification->status)&&$admin_notification->status=='1'?'block':'none' }}">
                    @include('/manager/common/form/input',['title'=>'Notification email','name'=>'admin_notification[email]','value'=>( isset($admin_notification->email)?$admin_notification->email:'' )])
                </div>
                <script>
                    $(document).on('change','#contact_notification_handler',(e)=>{
                        $('.notification_email_container').css('display',(e.target.checked?'block':'none'))
                    });
                </script>
            </div>
        </div><!-- End settings -->

        @include('/manager/common/metaTags',['values'=>$meta_tags])

        <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection