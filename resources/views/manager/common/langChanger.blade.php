<div class="btn-group langChanger">
    @foreach(config_languages() as $lang => $language)
    <button class="btn btn-primary {{ $lang==config_lang()?'active':false }}">{{ $language }}</button>
    @endforeach
</div>