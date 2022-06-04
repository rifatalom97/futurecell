<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;

class StatisticsReportController extends Controller
{
    public static function statisticsReport($request){
        $data = array();

        $lang = 'he';

        // Cashier daily or monthly
        $data['today_total_sale']   = DB::table('transactions')
                                            ->select(DB::raw('SUM(amount) as total_amount'))
                                            ->where('type','incomming')
                                            ->where(DB::raw('day(updated_at)'),Carbon::now()->day)
                                            ->first();
        $data['today_total_sale']   = $data['today_total_sale']?$data['today_total_sale']->total_amount:0;

        $data['monthly_total_sale'] = DB::table('transactions')
                                            ->select(DB::raw('SUM(amount) as total_amount'))
                                            ->where('type','incomming')
                                            ->where(DB::raw('month(updated_at)'),Carbon::now()->month)
                                            ->first();
        $data['monthly_total_sale']   = $data['monthly_total_sale']?$data['monthly_total_sale']->total_amount:0;

        // products orders range
        $data['total_sale_by_date_range'] = 0;
        if($request->input('from') && $request->input('to') && $request->isMethod('post')){
            $from = $request->input('from');
            $to = $request->input('to');
            $result   = DB::table('transactions')
                        ->select(DB::raw('SUM(amount) as total_amount'))
                        ->where(DB::raw('date(updated_at)'),'>=',Carbon::parse($from)->toDateTimeString())
                        ->where(DB::raw('date(updated_at)'),'<=',Carbon::parse($to)->toDateTimeString())
                        ->first();
            $data['total_sale_by_date_range'] = 50;
        }


        // // Best sale products lists
        $data['best_sale_products'] = DB::table('products')
                                            ->leftJoin('products_meta',function($join)use($lang){
                                                $join->on('products_meta.product_id','=','products.id')
                                                    ->where('products_meta.lang',$lang);
                                            })
                                            ->select('products.id','products.slug','products.total_sale','products_meta.title')
                                            ->where('products.status','1')
                                            ->orderBy('products.total_sale','DESC')
                                            ->limit(5)
                                            ->get();

        // // Today new order
        $data['today_total_new_order'] = DB::table('orders')
                                            ->where('payment_status',4)
                                            ->where(DB::raw('day(updated_at)'), Carbon::now()->day)
                                            ->count();
        // Today new customer
        $data['today_total_new_customers'] = DB::table('users')
                                            ->where('accountType','user')
                                            ->where('status',1)
                                            ->where(DB::raw('day(created_at)'), Carbon::now()->day)
                                            ->count();

        // Today contacts messages
        $data['today_total_contact_messages'] = DB::table('contacts')
                                            ->where(DB::raw('day(created_at)'), Carbon::now()->day)
                                            ->count();

        return (object)$data;
    }
}
