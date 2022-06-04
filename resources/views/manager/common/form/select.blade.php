<div class="form-group">
    @if(isset($title))
    <label class="mb-0">{{ $title }}</label>
    @endif
    <div>
        <select name="{{ $name }}" class="form-control" required>
            @if(isset($placeholder))
            <option value="">{{$placeholder?:'Select option'}}</option>
            @endif
            @foreach($options as $key_val => $label)
                <option value="{{ $key_val }}" {{ $value!==null&&$value==$key_val? 'selected':false }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    @error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>