@extends('layouts.app')

@section('content')

    <section class="page_area">
        <div class="container">
            <div class="row">
                <div class="page_contents col-md-8">
                    @php($local = app()->getLocale())
                    
                    <div class="content">
                        <h2 class="page_title">{{ isset($options['title']->$local)? $options['title']->$local : false }}</h2>
                        {!! isset($options['content']->$local)?$options['content']->$local : false !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
