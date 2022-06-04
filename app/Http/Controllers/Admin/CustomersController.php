<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\Models\User;
use Session;

class CustomersController extends Controller
{
    /**
     * Return all users
     */
    public function customers(Request $request){

        $query = User::where('accountType','user');
        
        $name     = $request->input('name');
        if( $name ){
            $query = $query->where('users.name','LIKE','%'.$name.'%');
        }
        $email     = $request->input('email');
        if( $email ){
            $query = $query->where('users.email','LIKE','%'.$email.'%');
        }
        $mobile     = $request->input('mobile');
        if( $mobile ){
            $query = $query->where('users.mobile','LIKE','%'.$mobile.'%');
        }
        
        $customers = $query->paginate(18);
        
        return view('manager.products.customers.index')->with('customers',$customers);
    }

    /**
     * Customes edit or view
     */

    public function view(Request $request){

        $customer = User::where('id',$request->id)->where('accountType','user')->first();
                
        return view('manager.products.customers.view')->with('customer',$customer);
    }

    public function save( Request $request ){

        $attr = $request->validate([
            'name'      => 'required|string|max:255',
            'mobile'    => 'required|string|max:15',
            'city'      => 'required|string|max:15',
            'zip_code'      => 'required|string|max:15',
            'password'      => 'nullable|string|min:6|max:15',
            're_password'   => 'required_with:password|same:password'
        ]);

        $data = [
            'name'      => $attr['name'],
            'mobile'    => $attr['mobile'],
            'city'      => $attr['city'],
            'zip_code'  => $attr['zip_code']
        ];
        if(isset($attr['password']) && $attr['password']){
            $data['password'] = bcrypt($attr['password']);
        }
        User::updateOrCreate(
            ['id' => $request->input('id')],
            $data
        );

        Session::flash('message', 'Customer updated successfully');
        
        return redirect('/manager/products/customers');
    }

    // Change user status
    public function changeStatus( Request $request ){
        $id = $request->id;
        $status = $request->status;

        return response()->json(
            DB::table('users')
            ->where('id',$id)
            ->update(['user_status'=>$status])
        );
    }
}
