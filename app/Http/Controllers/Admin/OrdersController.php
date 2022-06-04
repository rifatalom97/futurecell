<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\Models\Orders;
use Session;
class OrdersController extends Controller
{
    /**
     * Return all orders
     */
    public function orders(Request $request){
        $lang               = 'he';

        $order_number       = $request->input('order_number');
        $customer_name      = $request->input('customer_name');
        $customer_email     = $request->input('customer_email');
        
        $query = Orders::with(['customer'=>function($join)use($customer_name, $customer_email){
            if($customer_name){
                $join->where('name',$customer_name);
            }
            if($customer_email){
                $join->where('email',$customer_email);
            }
        },'orderProducts'=>function($join){
            $join->with(['product'=>function($pdJoin){
                $pdJoin->with(['meta']);
            }]);
        }]);
        $orderNumber     = $request->input('orderNumber');
        if( $orderNumber ){
            $query = $query->where('order_number','LIKE','%'.$orderNumber.'%');
        }
        
        $orders = $query->orderBy('id','DESC')->paginate(20);

        return view('manager.products.orders.index')->with('orders',$orders);
    }
    public function view(Request $request){

        $id     = $request->id;
        
        $order  = Orders::with(array(
            'customer',
            'orderProducts'=>function($join){
                $join->with([
                    'product'=>function($pdJoin){
                        $pdJoin->with(['meta']);
                    },
                    'size',
                    'color'=>function($clJoin){
                        $clJoin->with(['meta']);
                    }
                ]);
            }
        ))->where('id',$id)->first();
        
        return view('manager.products.orders.view')->with('order',$order);
    }
    
    /**
     * Change status
     */
    public function change_status(Request $request){
        $status = $request->input('order_status');
        $order_id = $request->input('order_id');
        
        Orders::where('id',$order_id)->update(['order_status'=>$status]);

        // set flush message
        Session::flash('message', 'Order status changed successfully');
        return redirect()->back();
    }

    // Change order status
    public function changeStatus( Request $request ){
        $id     = $request->id;
        $status = $request->status;

        return response()->json(Orders::where('id',$id)->update(['order_status'=>$status]));
    }

    // delete orders
    public function delete(Request $request){
        $id = $request->id;
        if( !is_array($id) ){
            $all_ids = [(int)$id];
        }else{
            $all_ids = $id;
        }
        if($all_ids){
            foreach($all_ids as $id){
                Orders::where('id',$id)->delete();
            }
        }
        // set flush message
        Session::flash('message', 'Order deleted successfully');
        return redirect()->back();
    }
}
