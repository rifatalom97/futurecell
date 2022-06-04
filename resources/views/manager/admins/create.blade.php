@extends('manager.common.layout')
@section('content')
<div class="create_admin">

    <div class="col-md-6">
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Admin</b></h2>
            </div>
            <div class="admin_info_box_body">
                <form action="{{ url('/manager/admins/save') }}" method="POST">
                    @csrf
                    <div class="admin_form">
                        <div class="form-group">
                            <label class="mb-0">Name</label>
                            <input class="form-control" type="text" name="name" placeholder="Name" required>
                            @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Email</label>
                            <input class="form-control" type="email" name="email" placeholder="Email" required>
                            @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Mobile</label>
                            <input class="form-control" type="tel" name="mobile" placeholder="Mobile" required>
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
                            <label class="mb-0">Status</label>
                            <div>
                                <select name="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                            @error('status')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-dark btn-lg mt-4" type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection