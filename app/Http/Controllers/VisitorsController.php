<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Visitors;

class VisitorsController extends Controller
{
    public function register(){

    }

    public function getVisitor( $ipOrId ){
        
        return Visitors::where('ip',$ipOrId)->orWhere('id',$ipOrId)->first();
    }

    public function updateVisitorSession( Request $request ){
        $ip             = $request->ip;
        $visitorSession = $request->visitorSession;

        $visitor = Visitors::where('session',$visitorSession);
        if( $visitor->get()->count() ){
            $visitor->update([ 'session' => $visitorSession ]);
        }else{
            $this->register();
        }
    }
}
