@extends('manager.common.layout')
@section('content')
<div class="create_admin">

    <div class="col-md-6">
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>My profile</b></h2>
            </div>
            <div class="admin_info_box_body">
                <form action="{{ url('/manager/admins/save') }}" method="POST">
                    @csrf 
                    <input type="hidden" name="id" value="{{ $profile->id }}">
                    <div class="admin_form">
                        <div class="form-group">
                            <label class="mb-0">Name</label>
                            <input class="form-control" value="{{ $profile->name }}" type="text" name="name" placeholder="Name" required>
                            @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Email</label>
                            <input class="form-control" value="{{ $profile->email }}" type="email" name="email" placeholder="Email" disabled>
                            @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Mobile</label>
                            <input class="form-control" value="{{ $profile->mobile }}" type="tel" name="mobile" placeholder="Mobile" required>
                            @error('mobile')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Password</label>
                            <input class="form-control" type="text" name="password" placeholder="Password" required min="8" max="12" value="">
                            @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="status" vlaue="1">
                        </div>
                    </div>

                    <button class="btn btn-dark btn-lg mt-4" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection