@extends('manager.common.layout')
@section('content')
<div class="coupons">

    @include('manager.common.sessionMessage')

    <div class="row">
        <div class="col-md-3">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Details</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <form action="{{ url('/manager/products/coupons') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ isset($coupon->id)?$coupon->id:0 }}">
                        @include( 'manager/common/form/input',['type'=>'text','title'=>'Coupon code','name'=>'coupon_code','value'=>(isset($coupon->coupon_code)?$coupon->coupon_code:old('coupon_code'))] )
                        
                        @include( 'manager/common/form/select',['title'=>'Discount type','name'=>'discount_type','placeholder'=>"Select type",'options'=>['amount'=>'Amount','percent'=>'Percent'],'value'=>(isset($coupon->discount_type)?$coupon->discount_type:old('discount_type'))] )

                        @include( 'manager/common/form/input',['type'=>'number','title'=>'Discount','name'=>'discount','value'=>(isset($coupon->discount)?$coupon->discount:old('discount'))] )
                        
                        @include( 'manager/common/form/input',['type'=>'datetime-local','title'=>'Start time','name'=>'start_date','value'=>(isset($coupon->start_date)?date('Y-m-d\TH:i',strtotime($coupon->start_date)):'')] )
                        
                        @include( 'manager/common/form/input',['type'=>'datetime-local','title'=>'Expire time','name'=>'expire_date','value'=>(isset($coupon->expire_date)?date('Y-m-d\TH:i',strtotime($coupon->expire_date)):'')] )
                        
                        @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','placeholder'=>"Select status",'options'=>['1'=>'Active','0'=>'Draft'],'value'=>(isset($coupon->status)?$coupon->status:old('status'))] )

                        <button type="submit" class="btn btn-dark">Save coupon</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Coupons</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <div class="all_pages table_container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="sort_button">#</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="name">Coupon</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Discount</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Start date</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Expire date</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                                    <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($coupons->total())
                                    @foreach($coupons as $coupon)
                                        <tr id="coupon_{{ $coupon->id }}">
                                            <td>
                                                <input type="checkbox" name="coupons[]" value="{{ $coupon->id }}">
                                            </td>
                                            <td>{{ $coupon->coupon_code }}</td>
                                            <td>{{ ($coupon->discount_type=='amount'?'$':'') . $coupon->discount . ($coupon->discount_type!='amount'?'$':'') }}</td>
                                            <td>{{ date('d/m/Y \a\t H:iA', strtotime($coupon->start_date)) }}</td>
                                            <td>{{ date('d/m/Y \a\t H:iA', strtotime($coupon->expire_date)) }}</td>
                                            <td>{{ $coupon->status?'Active':'Draft' }}</td>
                                            <td>{{ date('d/m/Y', strtotime($coupon->created_at)) }}</td>
                                            <td class="text-center action_buttons">
                                                <a href="{{ url('/manager/products/coupons/'.$coupon->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit">
                                                </a>
                                                <a href="{{ url('/manager/products/coupons/delete/'.$coupon->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            No product coupon found
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @include('manager.common.paggination', ['paginator' => $coupons])
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection