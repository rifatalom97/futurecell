<div class="form-group">
    @if(isset($title))
    <label class="mb-0">{{ $title }}</label>
    @endif
    <input class="form-control" type="{{ isset($type)?$type:'text' }}" name="{{ $name }}" placeholder="{{ isset($placeholder)&&$placeholder?$placeholder:(isset($title)?$title:'') }}" onKeyup="{{ (isset($slug_generator)&&$slug_generator?'generate_slug(this)':false) }}" value="{{ isset($value)?$value:'' }}">
    @error((isset($paramKey)?$paramKey:$name))
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>