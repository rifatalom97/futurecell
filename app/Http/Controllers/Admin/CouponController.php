<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Coupon;
use App\Models\CouponMeta;

use DB;
use Session;

class CouponController extends Controller
{
    /**
     * Return all coupons
     */
    public function coupons(Request $request){
        $id = isset($request->id)?$request->id:0;

        if($request->isMethod('post')){
            $coupon_id       = $request->input('id');
            $defaultData    = $request->only('coupon_code','discount_type','discount','start_date','expire_date','status');
            
            $attr = $request->validate([
                'coupon_code'       => 'required|string|max:255',
                'discount_type'     => 'required|string',
                'discount'          => 'required|string',
                'start_date'        => 'required|string',
                'expire_date'       => 'required|string',
                'status'            => 'required'
            ]);

            $defaultData['start_date'] = Carbon::parse($defaultData['start_date']);
            $defaultData['expire_date'] = Carbon::parse($defaultData['expire_date']);
            Coupon::updateOrCreate(
                ['id'=>$coupon_id],
                $defaultData
            );
            // set flush message
            Session::flash('message', 'Coupon saved successfully');
            return redirect('/manager/products/coupons');
        }

        $coupon = Coupon::where('id',$id)->first();
        $coupons = Coupon::orderBy('id','DESC')->paginate(18);
        return view('manager.products.coupons.index')->with('coupon',$coupon)->with('coupons',$coupons);
    }


    /**
     * Delete coupon
     * @param int id
     * @return json bool
     */
    public function delete( Request $request ){
        $deleting_id    = $request->id;
        $deleting_id = !is_array($deleting_id)? [$deleting_id] : $deleting_id;
        foreach($deleting_id as $id){
            Coupon::find($id)->delete();
        }
        // set flush message
        Session::flash('message', 'Coupon deleted successfully');
        return redirect('/manager/products/coupons');
    }

    /**
     * getCoupon
     * @param request coupon id
     * @return json response coupon
     */
    public function getCoupon(Request $request){
        $coupon_id = (int)$request->input('id');

        if( $coupon_id ){
            $coupon = (array) DB::table('coupons')->where('id',$coupon_id)
                        ->select(
                            'id',
                            'couponCode',
                            'discountType',
                            'discount',
                            // 'startDate',
                            // 'expireDate',
                            DB::raw("DATE_FORMAT(startDate, '%Y-%m-%d\T%h:%i:%s') as startDate"),
                            DB::raw("DATE_FORMAT(expireDate, '%Y-%m-%d\T%h:%i:%s') as expireDate"),
                            'couponFor',
                            'couponForId',
                            'status'
                        )

                        ->first();

            if($coupon){
                $coupons_metas= DB::table("coupons_meta")
                                ->where('coupon_id',$coupon_id)
                                ->select('lang','title','description')->get();
                $metas = [];
                if($coupons_metas){
                    foreach($coupons_metas as $meta){
                        $metas[$meta->lang.'_title'] = $meta->title;
                        $metas[$meta->lang.'_description'] = $meta->description;
                    }
                }
                $coupon = array_merge($coupon, $metas);
            }
            return response()->json($coupon);
        }
    }
}
