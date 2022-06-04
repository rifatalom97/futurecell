<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\CountryController;

use App\Http\Controllers\Admin\StatisticsReportController as STCReports;

use App\Models\User;
use DB;

class AdminController extends Controller
{
    // Admin login
    public function login( Request $request ){
        // if user logged in then redirect to manager
        if( Auth::check() && Auth::user()->accountType == 'admin' ){
            return redirect('/manager');
        }

        // is posted to log in
        if($request->isMethod('post')){
            $attr = $request->validate([
                'email'         => 'required|string|email',
                'password'      => 'required|string|min:6|max:15'
            ]);
            if( Auth::attempt([
                'email'         => $request->email, 
                'password'      => $request->password, 
                'accountType'   => 'admin'
                ]) ) {
                return redirect('/manager');
            }
            return view('manager/login')->withInput($request->only('email'))
            ->withErrors(['errors' => 'User are not allowed']);
        }
        
        // else it will load just
        return view('manager/login');
    }
    // admin logout
    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('/manager/login');
    }

    // Admin dashboard
    public function dashboard( Request $request ){
        $report = STCReports::statisticsReport( $request );

        return view('manager/dashboard')->with('report',$report);
    }



    /**
     * Return all admins
     */
    public function admins(Request $request){

        $query = User::where('accountType','admin');
        // $query = User::where('accountType','admin')
        //             ->where('id','!=',Auth::user()->id);
        // Avoid super admins to load on normal admins
        // if(Auth::user()->adminRole != 0){
        //     $query = $query->where('adminRole','!=', 0);
        // }

        $email       = $request->input('email');
        if( $email ){
            $query = $query->where('email','LIKE','%'.$email.'%');
        }
        $name       = $request->input('name');
        if( $name ){
            $query = $query->where('name','LIKE','%'.$name.'%');
        }

        $admins = $query->paginate(20);

        return view('manager.admins.index')
                        ->with('admins',$admins);
    }
    public function create(){
        return view('manager.admins.create');
    }
    public function edit(Request $request){

        return view('manager.admins.edit')
                    ->with(
                        'admin',
                        User::find($request->id)->first()
                    );
    }
    public function profile(Request $request){

        return view('manager.admins.profile')
                    ->with(
                        'profile',
                        Auth::user()
                    );
    }
    public function save( Request $request ){

        if( $request->input('id') ){
            $attr = $request->validate([
                'name'      => 'required|string|max:255',
                'mobile'    => 'required|string|max:15',
                'password'  => 'nullable|string|min:6|max:15',
                'status'    => 'required'
            ]);
        }else{
            $attr = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'mobile'    => 'required|string|max:15',
                'password'  => 'required|string|min:6|max:15',
                'status'    => 'required'
            ]);
        }

        if( $request->id ){
            $data = [
                'name'      => $attr['name'],
                'mobile'    => $attr['mobile'],
                'status'    => $attr['status']
            ];
            if(isset($attr['password']) && $attr['password']){
                $data['password'] = bcrypt($attr['password']);
            }
        }else{
            $data = [
                'name'          => $attr['name'],
                'mobile'        => $attr['mobile'],
                'email'         => $attr['email'],
                'status'        => $attr['status'],
                'password'      => bcrypt($attr['password']),
                'accountType'   => 'admin',
                'adminRole'     => 1,
            ];
        }


        $user = User::updateOrCreate(
            [
                'id' => $request->id,
            ],
            $data
        );

        Session::flash('success', ($request->id? 'Admin updated successfully' : 'New admin created successfully'));
        Session::flash('flash_admin_id',$user->id);
        
        redirect('/manager/admins');
    }
    public function exists( Request $request ){
        $email  = $request->email;
        $id     = $request->id;
        if($email){
            $user = User::where('email',$email)->where('id','!=',$id)->first();
            
            return response()->json(['result'=>(bool)$user]);
        }
    }
    public function getAdmin(Request $request){
        $id = $request->id;

        return response()->json( 
            DB::table('users')->where('id',$id)->select('name','email','mobile','status','id')->first()
        );
    }
    public function deleteAdmin(Request $request){
        $id = $request->id;
        if($id){

            return response()->json( User::where('id',$id)->delete() );
        }
    }

    
}
