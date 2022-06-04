@if(Session::has('message'))
    <div class="alert alert-success alert-dismissible fade show mt-4 alert-custom"
        role="alert">
        {{ Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif