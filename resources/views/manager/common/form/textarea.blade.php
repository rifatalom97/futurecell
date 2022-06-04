<div class="form-group">
    @if(isset($title))
    <label class="mb-0">{{ $title }}</label>
    @endif
    <textarea id="{{ isset($tinymce)&&$tinymce?'tinymce_handler':'' }}"  class="form-control" name="{{ $name }}" cols="30" rows="5" placeholder="{{ isset($title)?$title:false }}" >{{ $value }}</textarea>
    @error((isset($paramKey)?$paramKey:$name))
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>