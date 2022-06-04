@extends('manager.common.layout')
@section('content')
<div class="orders">

    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Orders</b></h2>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/orders') }}" class="mb-4 row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control" type="text" name="order_number" placeholder="Search by order number" value="{{ Request()->order_number }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control" type="text" name="customer_name" placeholder="Search by customer name" value="{{ Request()->customer_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control" type="email" name="customer_email" placeholder="Search by customer email" value="{{ Request()->customer_email }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                   <button type="submit" class="btn btn-dark">Search</button>
                </div>
            </form>

            <div class="all_orders table_container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sort_button">#</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="order_number">Number</th>
                            <th>Products</th>
                            <th>Orderd By</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="total">Total</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="payment_status">Payment</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="order_status">Status</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Orderd on</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->total())
                            @foreach($orders as $order)
                                <tr id="order_{{ $order->id }}">
                                    <td><input type="checkbox" name="orders" value="{{ $order->id }}"></td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>
                                        @if(count($order->orderProducts))
                                        @foreach($order->orderProducts as $orderProduct)
                                        {{ $orderProduct->product?$orderProduct->product->meta->title . "\n":'' }}
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $order->customer?$order->customer->name:'' }}</td>
                                    <td>₪{{ $order->total_amount }}</td>
                                    <td><?php 
                                        switch($order->payment_status){
                                            case 1:
                                                echo 'Processing';
                                                break;
                                            case 2:
                                                echo 'Cancel';
                                                break;
                                            case 3:
                                                echo 'Error';
                                                break;
                                            case 4:
                                                echo 'Paid';
                                                break;
                                            default:
                                                echo 'Not paid';
                                                break;
                                        }
                                    ?></td>
                                    <td><?php 
                                        if($order->canceled_by>0){
                                            echo 'Cancel';
                                        }elseif($order->payment_status==4){
                                            switch($order->order_status){
                                                case 1:
                                                    echo 'Processing';
                                                    break;
                                                case 2:
                                                    echo 'Delivering';
                                                    break;
                                                case 3:
                                                    echo 'Completed';
                                                    break;
                                                default:
                                                    echo 'Pending for approval';
                                                    break;
                                            }
                                        }else{
                                            echo 'N/A';
                                        }
                                    ?></td>
                                    <td>{{ $order->paid_on?date('d F, Y', strtotime($order->paid_on)):date('d F, Y', strtotime($order->updated_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/products/orders/'.$order->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/view_icon.svg') }}" alt="view" title="View {{ $order->order_number }}">
                                        </a>
                                        <a href="{{ url('/manager/products/orders/delete/'.$order->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $order->order_number }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="9">No orders found to show</td>
                            </tr>
                        @endif 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- @include('manager.common.paggination', ['paginator' => $orders]) -->
</div>
@endsection