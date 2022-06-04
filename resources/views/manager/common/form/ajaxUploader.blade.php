<div class="ajax_uploader" data-action="{{ url("/manager/ajaxFileUpload") }}">
    @if(isset($title)&&$title)
    <label>{{ $title }}</label>
    @endif 
    <input name="file" type="file" accept="image/*" size="2000"/>
    <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
    <input class="file" type="hidden" name="{{ $name }}" value="{{ $value }}"/>
    <input class="old_file" type="hidden" name="{{ $old_name }}" value="{{ $value }}"/>
    <img src="{{ $value?asset('uploads/'.$value):'' }}" class="viewer" style="display:{{ $value?'block':'none' }};"/>
</div>