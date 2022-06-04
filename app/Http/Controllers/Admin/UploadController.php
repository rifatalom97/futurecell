<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * upload
     * 
     * @param string input key 
     * @param request file
     * @return boolean
     */
    public static function upload( $key, $request ){
        if($request->hasFile($key)){

            $file               = $request->file($key) ;
            $fileName           = $file->getClientOriginalName() ;
            $fileExtention      = $file->getClientOriginalExtension();
            $newName            = md5(time().md5($fileName).uniqid(rand(), true)).'.'.$fileExtention;
    
            $destinationPath    = public_path().'/uploads';
    
            return $file->move($destinationPath,$newName)? $newName : NULL;
        }
        return NULL;
    }

    /**
     * ajaxUpload
     * 
     * @param request file
     * @return json object file name
     */
    public function ajaxUpload(Request $request){

        $file               = $request->file('uploading_file') ;
        $fileName           = $file->getClientOriginalName() ;
        $fileExtention      = $file->getClientOriginalExtension();
        $newName            = md5(time().md5($fileName).uniqid(rand(), true)).'.'.$fileExtention;

        $destinationPath    = public_path().'/uploads/temp';
        $newFileUrl         = url('/uploads/temp/'.$newName);

        $url = $file->move($destinationPath,$newName);

        // var_dump( $newName );
        return response()->json(['result'=>true, 'filename'=>$newName, 'fileurl'=>$newFileUrl]);
    }

    /**
     * moveTempToImages
     * 
     * @param string filename
     * @return void
     */
    static public function moveTempToImages($filename){
        if( $filename ){
            $temp_image = public_path() . '/uploads/temp/'.$filename;
            if(is_file($temp_image)){
                return rename($temp_image, public_path().'/uploads/'.$filename);
            }
        }
        return false;
    }
    /**
     * remove File
     * @param relative url
     * @return bool
     */
    static private function removeFile($relative_url){
        return unlink($relative_url);
    }


    /**
     * finishFileUploadProcess
     * 
     * @param string newFileName
     * @param string oldFileName
     */
    static public function finishFileUploadProcess($newFileName, $oldFileName){
        if( $newFileName != $oldFileName ){
            $oldFile = public_path() . '/uploads/' . $oldFileName;
            $newFile = public_path() . '/uploads/temp/' . $newFileName;

            if( $oldFileName && is_file($oldFile) ){
                self::removeFile($oldFile);
            }
            if( $newFileName && is_file($newFile) ){
                self::moveTempToImages($newFileName);
            }

            return true;
        }
    }
}
