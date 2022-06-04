@extends('manager.common.layout')
@section('content')
<div class="info_page">
    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Transaction Status</b></h2>
            <div class="title_right"></div>
        </div>
        <div class="admin_info_box_body">
            <div class="cashiar_update">
                <h2><b>Cashier update</b></h2>
                <h4 class="mt-4">Daily Cashier <b>₪{{ sprintf("%.2f", $report->today_total_sale) }}</b></h4>
                <h4 class="mt-4">Monthly Cashier <b>₪{{ sprintf("%.2f", $report->monthly_total_sale) }}</b></h4>
                <div class="mt-4">
                    <form action="{{ url("/manager") }}" method="POST">
                        @csrf
                        <h4 class="mr-2" style="display: inline-block;">
                            <span>Between date </span>
                            <span class="mr-1 ml-1">From</span>
                            <span style="font-size: 14px;">
                                <input name="from" class="form-control" type="date" style="width: 165px; display: inline-block;" value="{{ (isset($_POST['from'])?date('Y-m-d', strtotime($_POST['from'])) : false) }}">
                            </span>
                            <span class="mr-1 ml-1" style="font-size: 14px;">To</span>
                            <span style="font-size: 14px;">
                                <input name="to" class="form-control" type="date" style="width: 165px; display: inline-block;" value="{{ (isset($_POST['to'])?date('Y-m-d', strtotime($_POST['to'])) : false) }}">
                            </span>
                        </h4>
                        <h4 style="display: inline-block;">
                            <button class="btn btn-danger mr-1">Show</button>
                            <b>₪{{ sprintf("%.2f", $report->total_sale_by_date_range) }}</b>
                        </h4>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2b>Products best sale</b></h2>
                    <div class="title_right"></div>
                </div>
                <div class="admin_info_box_body">
                    <div class="product_sale_report table_container">
                        <table class="table mb-0">
                            <tbody>
                                @if(isset($report->best_sale_products) && count($report->best_sale_products))
                                @foreach($report->best_sale_products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->total_sale }}</td>
                                </tr>
                                @endforeach
                                @else 
                                    <tr>
                                        <td col="3">No product found to show</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Today Report</b></h2>
                    <div class="title_right"></div>
                </div>
                <div class="admin_info_box_body">
                    <div class="customer_order_report table_container">
                        <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td>Today's new orders <b>{{ $report->today_total_new_order }}</b></td>
                            </tr>
                            <tr>
                                <td>Today's new customers <b>{{ $report->today_total_new_customers }}</b></td>
                            </tr>
                            <tr>
                                <td>Today's new messages <b>{{ $report->today_total_contact_messages }}</b></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection