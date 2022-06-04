<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscribers;
use App\Models\Visitors;
use DB;
use Session;
class SubscribersController extends Controller
{
    /**
     * Return all subscribers
     */
    public function subscribers(Request $request){
        
        $query = Subscribers::with(['user','visitor']);
        
        $email     = $request->input('email');
        if( $email ){
            $data = $data->where('email','LIKE','%'.$email.'%');
        }
        $status     = $request->input('status');
        if( $status ){
            $data = $data->where('status',$status);
        }

        return view('manager.subscribers.index')->with('subscribers',$query->orderBy('id','DESC')->paginate(18));
    }
  

    // delete subscribers
    public function delete(Request $request){
        $ids = $request->ids;
        if( !is_array($ids) ){
            $all_ids = [(int)$ids];
        }else{
            $all_ids = $ids;
        }

        if($all_ids){
            foreach($all_ids as $id){
                $subscriber = Subscribers::where('id',$id)->first();
                
                Visitors::where('id',$subscriber->visitor_id)->delete();

                Subscribers::where('id',$id)->delete();
            }
        }

        // set flush message
        Session::flash('message', 'Subscriber deleted successfully');

        return redirect('/manager/subscribers');
    }

        // change subscribers
        public function change(Request $request){
            $id = $request->id;
            
            $subscriber = Subscribers::find($id);
            $subscriber->status = ($subscriber->status?0:1);
            $subscriber->save();
    
            // set flush message
            Session::flash('message', 'Subscriber status changed successfully');
    
            return redirect('/manager/subscribers');
        }
}
