<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class CkeditorImageUploader extends Controller
{    
    /**
     * upload
     *
     * @param  Request $request
     * @return void
     */
    public function upload(Request $request) {

        $validator = Validator::make($request->all(), [
            'upload' => 'required|file|image|max:' . config('settings.maxImageSize'),
            'CKEditorFuncNum' => 'required|string'
        ], [
            'upload.max' => 'Maximum allowed size is 2MB.'
        ], [
            'upload' => 'file'
        ]);

        if ($validator->fails()) {
            $errors = json_encode($validator->errors());
            echo "<script type='text/javascript'>
                alert('$errors');
            </script>";
            return;
        }

        $filename = storeFile('ckeditor/', $request->file('upload'), null);

        $fileURL = serveFile('ckeditor/', $filename);

        $funcNum = $request->input('CKEditorFuncNum');
            // Optional: instance name (might be used to load a specific configuration file or anything else).
            // $CKEditor = request()->input('CKEditor') ;
            // Optional: might be used to provide localized messages.
            // $langCode = request()->input('langCode') ;

            // Usually you will only assign something here if the file could not be uploaded.
        $message = 'File Uploaded Successfully!';
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$fileURL', '$message');</script>";
    }

}
