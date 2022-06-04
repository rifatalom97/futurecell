<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Contacts;
use App\Models\User;
use App\Models\Visitors;
use DB;
use Session;

class ContactsController extends Controller
{
    /**
     * Return all contacts
     */
    public function contacts(Request $request){
        
        $query = Contacts::with(['user']);

        $name     = $request->input('name');
        if( $name ){
            $data = $data->where('name','LIKE','%'.$name.'%');
        }
        $email     = $request->input('email');
        if( $email ){
            $data = $data->where('email','LIKE','%'.$email.'%');
        }
        $mobile     = $request->input('mobile');
        if( $mobile ){
            $data = $data->where('mobile','LIKE','%'.$mobile.'%');
        }

        return view('manager.contacts.index')->with('contacts',$query->orderBy('id','DESC')->paginate(18));
    }

    public function view(Request $request){
        
        $contact = Contacts::with(['visitor','user'])->where('id',$request->id)->first();

        $this->adminViewd($request->id);

        return view('manager.contacts.view')->with('contact',$contact);
    }

    // Admin viewd
    public function adminViewd( $id ){

        $contact = Contacts::find($id);

        $contact->adminView = true;
        $contact->save();
    }

    // delete contacts
    public function deleteContacts(Request $request){
        $ids = $request->ids;
        if( !is_array($ids) ){
            $all_ids = [(int)$ids];
        }else{
            $all_ids = $ids;
        }

        if($all_ids){
            foreach($all_ids as $id){
                $contact = Contacts::find($id);
                Visitors::find($contact->visitor_id)->delete();
                $contact->delete();
            }
        }

        // set flush message
        Session::flash('message', 'Contact deleted successfully');

        return redirect('/manager/contacts');
    }
}
