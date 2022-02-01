<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Support\Facades\URL;

class CkeditorImageUploader extends Controller
{
    /**
     * Upload images from ckeditor
     */
    public function upload() {
        if (request()->has('upload')) {

            $uploadFile = request()->file('upload');

            $file_name = $uploadFile->hashName();

            $uploadFile->storeAs(config('filepaths.ckeditorImagePath.internal_path'), $file_name);

            $url = URL::to( config('filepaths.ckeditorImagePath.public_path').$file_name);

            $funcNum = request()->input('CKEditorFuncNum');
            // Optional: instance name (might be used to load a specific configuration file or anything else).
            //$CKEditor = request()->input('CKEditor') ;
            // Optional: might be used to provide localized messages.
            //$langCode = request()->input('langCode') ;

            // Usually you will only assign something here if the file could not be uploaded.
            $message = 'File Uploaded Successfully!';
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
        }
    }

}
