@extends('manager.common.layout')
@section('content')
<div class="order">

    <div class="order_view mt-5" id="order">
        <table class="table table-sm table-bordered mb-0 order_view_heading">
            <tbody>
                <tr>
                    <td colspan="3" class="text-center">
                        <h3><b>Future Cell Ltd</b></h3>
                        <h5>Order summery</h5>
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%">
                        <p><b>Order Info:</b></p>
                        <p>Order Number: <b>{{ $order->order_number }}</b></p>
                        <p>Placed: <b>{{ date('d F, Y',strtotime($order->created_at)) }}</b></p>
                        <p>Payment Status: <b>
                            @php
                            switch($order->payment_status){
                                case 1: 
                                    echo 'Pending';
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
                                    echo 'Unpaid';
                                    break;
                            }
                            @endphp 
                        </b></p>
                        <p>Delivery: <b>
                            @php 
                            if($order->payment_status==4){
                                switch($order->order_status){
                                    case 1:
                                        echo 'Processing';
                                        break;
                                    case 2:
                                        echo 'On the way';
                                        break;
                                    case 3:
                                        echo 'Completed';
                                        break;
                                    default:
                                        echo 'N/A';
                                        break;
                                }
                            }else{
                                echo 'N/A';
                            }
                            @endphp 
                            <form action="{{ url('/manager/products/orders/change-status') }}" method="post" class="border p-2">
                                @csrf 
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <select name="order_status" required>
                                    <option value="">Change status</option>
                                    <option value="2">Delivering</option>
                                    <option value="3">Completed</option>
                                </select>
                                <button class="btn btn-sm btn-success">Change</button>
                            </form>
                        </b></p>
                    </td>
                    <td style="width: 40%">
                        &nbsp;
                    </td>
                    <td style="width: 20%">
                        @php 
                        $shipping_address = json_decode($order->shipping_address);
                        @endphp 
                        <p>Delivery Address:</p>
                        <p>Name: <b>{{ $shipping_address->name }}</b></p>
                        <p>Address: <b>{{ $shipping_address->address . ',' . $shipping_address->city . ',' . $shipping_address->zip_code }}</b></p>
                        <p>Mobile: <b>{{ $shipping_address->mobile }}</b></p>
                        <p>Email: <b>{{ $shipping_address->email }}</b></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered table-sm mb-0">
            <thead>
                <tr class="table-active">
                    <th class="text-center">SL</th>
                    <th>Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Size</th>
                    <th class="text-center">Color</th>
                    <th class="text-center">Unite Price</th>
                    <th class="text-right">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @if(count($order->orderProducts))
                    @foreach($order->orderProducts as $orderProduct)
       
                        <tr id="ordered_product_{{ $orderProduct->product_id }}">
                            <td class="text-center">{{ (($loop->iteration<9)?'0'.$loop->iteration : $loop->iteration) }}</td>
                            <td>
                                @if(count($order->orderProducts))
                                @foreach($order->orderProducts as $orderProduct)
                                {{ $orderProduct->product?$orderProduct->product->meta->title . "\n":'' }}
                                @endforeach
                                @endif
                            </td>
                            <td class="text-center">{{ $orderProduct->quantity }}</td>
                            <td class="text-center">{{ !$orderProduct->size?'N/A':'d' }}</td>
                            <td class="text-center">{{ !$orderProduct->color?'N/A':'d' }}</td>
                            <td class="text-center">₪{{ $orderProduct->unite_price }}</td>
                            <td class="text-right">₪{{ $orderProduct->total_amount }}</td>
                        </tr>
                    @endforeach
                @else 
                    <tr>
                        <td colspan="7">No products found to show</td>
                    </tr>
                @endif 
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary btn-lg mt-5" onClick="print_order('order')">Print Order</button>
                <script>
                    function print_order(divName) {
                        var printContents       = document.getElementById(divName).innerHTML;
                        var originalContents    = document.body.innerHTML;
                        document.body.innerHTML = printContents;
                        $(document.body).find('form,button').remove();
                        window.print();
                        document.body.innerHTML = originalContents;
                    }
                </script>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-sm float-right" style="max-width: 300px;">
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-right">
                                <b>Subtotal:</b>
                            </td>
                            <td class="text-right">₪{{ $order->cart_total_amount }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right" br="0">
                                <b>Shipping:</b>
                            </td>
                            <td class="text-right">₪{{ ($order->total_amount - $order->cart_total_amount) }}</td>
                        </tr>
                        <tr class="text-right">
                            <td colspan="6">
                                <b>Total:</b>
                            </td>
                            <td class="text-right">₪{{ $order->total_amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection