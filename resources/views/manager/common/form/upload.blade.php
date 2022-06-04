<div class="form-group">
    <label class="mb-0">{{ $title }}</label>
    <div class="file_inputer">
        <input class="form-control" type="file" name="{{ $name }}">
        <img src="@if($value){{ asset('/uploads/'.$value) }}@endif" style="display:<?= $value?'block':'none' ?>">
        <input type="hidden" name="old_{{ $name }}" value="{{ $value }}">
    </div>
    @error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>