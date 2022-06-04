<div class="form-group">
    <label class="mb-0">Url Slug</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon3">{{ url($url_prefix) . '/' }}<span id="slug_viewer">{{ (isset($value)?$value:old('slug')) }}</span></span>
        </div>
        <input id="slug_field" class="form-control" type="text" name="slug" placeholder="Url slug" onKeyup="slug_filter(this)" value="{{ isset($value)?$value:old('slug') }}">
    </div>
    @error('slug')
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>