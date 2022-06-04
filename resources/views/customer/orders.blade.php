@extends('layouts.customer_layout')

@section('customerContent')
<div class="customer_orders">
   <h2 class="mt-0 mb-2"><b>My Orders</b></h2>

   @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show mb-4 alert-custom"
            role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

   <table class="table table-striped">
         <thead>
            <tr class="bg-yellow text-center">
               <th>Order Number</th>
               <th>Products</th>
               <th>Total</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            @if(isset($orders)&&count($orders))
               @foreach($orders as $order)
                  <?php 
                     // dd($order);
                  ?>
                  <tr id="order_">
                     <td class="text-center"><b>#{{ $order->order_number }}</b></td>
                     <td>
                        
                        @if(isset($order->orderProducts)&&count($order->orderProducts))   
                        @foreach($order->orderProducts as $products)
                           <span class="d-block">{{ $products->product?$products->product->meta->title:'Product deleted' }}{{ ($loop->iteration<count($order->orderProducts))?",":false }}</span>
                        @endforeach
                        @endif
                     </td>
                     <td class="text-center">{{ $order->currency_sign . $order->total_amount }}</td>
                     <td class="text-center">
                        <?php 
                           if($order->canceled_by>0){
                              echo '<span class="badge badge-danger">Canceled</span>';
                           }else{
                              if($order->paid){
                                 if(!$order->order_status){
                                    echo '<span class="badge badge-primary">Pending</span>';
                                 }else if($order->order_status==1){
                                    echo '<span class="badge badge-secondary">Processing</span>';
                                 }else if($order->order_status==2){
                                    echo '<span class="badge badge-info">Delivering</span>';
                                 }else{
                                    echo '<span class="badge badge-success">Completed</span>';
                                 }
                              }else{
                                 echo '<span class="badge badge-warning">Not Paid</span>';
                              }
                           }
                        ?>
                     </td>
                     <td class="text-center">
                        <a href="{{ url('my-account/orders/delete/'.$order->order_number) }}">
                           <img class="ml-3 mr-3 pointer" src="{{ asset('/assets/frontend/img/delete_icon.svg') }}" alt="Delete" title="Delete" />
                        </a>
                     </td>
                  </tr>
               @endforeach
            @else 
               <tr>
                  <td colspan="5" class="text-center py-5">
                     <b>No orders found to show</b>
                  </td>
               </tr>
            @endif 
         </tbody>
   </table>

   {!! $orders->links('pagination::bootstrap-4') !!}

</div>
@endsection
