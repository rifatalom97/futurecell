<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;

use App\Http\Controllers\Admin\OptionsController;

use App\Models\Options;
use DB;
use Session;

class SettingsController extends Controller
{

    public function settings(Request $request){

        $op_type    = "default_settings";

        if($request->isMethod('post')){
            $attr = $request->validate([
                'logo'      => 'nullable|mimes:png,jpg,jpeg,svg|max:2048', //,csv,txt,xlx,xls,pdf
                'favicon'   => 'nullable|mimes:png,jpg,jpeg,ico,svg|max:1024', //,csv,txt,xlx,xls,pdf
            ]);
    
            // Image process
            $logo = UploadController::upload('logo', $request);
            if($request->input('old_logo') && $request->hasFile('logo')){
                $file_path = public_path() . '/uploads/' . $request->input('old_logo');
                if(is_file($file_path)){
                    unlink($file_path);
                }
            }
            $favicon = UploadController::upload('favicon', $request);
            if($request->input('old_favicon') && $request->hasFile('favicon')){
                $file_path = public_path() . '/uploads/' . $request->input('old_favicon');
                if(is_file($file_path)){
                    unlink($file_path);
                }
            }
            
            OptionsController::saveOptions([
                'site_name'          => json_encode($request->input('site_name')),
                'administrator_email'          => json_encode($request->input('administrator_email')),
                'logo'          => json_encode(($logo?:$request->input('old_logo'))),
                'favicon'       => json_encode(($favicon?:$request->input('old_favicon'))),
            ],$op_type);

            // Meta tags
            MetaTagsController::createUpdateMetaTags( $op_type, 0, $request );

            // set flush message
            Session::flash('message', 'Settings updated successfully');
            return redirect('/manager/settings');
        }
        
        $options    = OptionsController::getOptions($op_type);
        $metaTags   = MetaTagsController::getMetaTags($op_type);

        return view('manager.settings.settings')
                        ->with('options',$options)
                        ->with('metaTags',$metaTags);
    }


    public function header( Request $request ){
        $op_type    = "header_settings";

        if($request->isMethod('post')){

            $menus = $request->input('menus');
            
            OptionsController::saveOptions([
                'menus'         => json_encode($menus)
            ],$op_type);

            // set flush message
            Session::flash('message', 'Settings updated successfully');
            return redirect('/manager/settings/header');
        }
        
        $options = OptionsController::getOptions($op_type);

        return view('manager.settings.header')->with('options',$options);
    }


    public function footer( Request $request ){
        $op_type    =  "footer_settings";

        if($request->isMethod('post')){
            // Filter delivery section
            $delivery          = $request->input('delivery');

            if($delivery){
                if(isset($delivery['first']['image'])){
                    UploadController::finishFileUploadProcess($delivery['first']['image'],$delivery['first']['old_image']);
                    $delivery['first']['old_image'] = $delivery['first']['image'];
                }
                if(isset($delivery['second']['image'])){
                    UploadController::finishFileUploadProcess($delivery['second']['image'],$delivery['second']['old_image']);
                    $delivery['second']['old_image'] = $delivery['second']['image'];
                }
                if(isset($delivery['third']['image'])){
                    UploadController::finishFileUploadProcess($delivery['third']['image'],$delivery['third']['old_image']);
                    $delivery['third']['old_image'] = $delivery['third']['image'];
                }
            }
            // end           

            OptionsController::saveOptions([
                'securities'     => json_encode($request->input('securities')),
                'menus'         => json_encode($request->input('menus')),
                'delivery'      => json_encode($delivery),
                'copyright'     => json_encode($request->input('copyright'))
            ],$op_type);

            // set flush message
            Session::flash('message', 'Settings updated successfully');
            return redirect('/manager/settings/footer');
        }

        $options = OptionsController::getOptions($op_type);
        return view('manager.settings.footer')->with('options',$options);
    }


    public function themeSettings(Request $request){
        $op_type    = "themeOptions";

        if($request->isMethod('post') && isset($request->settings) && $request->settings){
            OptionsController::saveOptions([
                'themeOptions'         => json_encode($request->settings),
            ],$op_type);
        }

        return response()->json( OptionsController::getOptions($op_type) );
    }
}
