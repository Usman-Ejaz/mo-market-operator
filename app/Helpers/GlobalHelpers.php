<?php

use App\Models\ApiKey;
use Illuminate\Support\Facades\Storage;

if (!function_exists("truncateWords")) {

    function truncateWords($string, $limit, $replaceWith = "...")
    {
        return $string ? ((strlen($string) > $limit) ? substr($string, 0, $limit) . $replaceWith : $string) : '';
    }
}

if (!function_exists("isValidKey")) {

    function isValidKey($name, $value)
    {
        return ApiKey::valid($name, $value)->first() !== null;
    }
}

if (!function_exists("storeFile")) {

    function storeFile($dir, $file, $oldFile = null)
    {
        if ($oldFile !== null) {
            removeFile($dir, $oldFile);
        }
        
        try {
            $filename = (md5(time()) . md5(time()) . '.' . $file->getClientOriginalExtension());
            $contents = $file->get();
            Storage::disk('app')->put($dir . $filename, $contents);
        } catch (\Exception $ex) {
            $filename = null;
        }
        
        return $filename;
    }
}

if (!function_exists("serveFile")) {

    function serveFile($dir, $file)
    {
        return $file ? Storage::disk("app")->url($dir . $file) : null;
    }
}

if (!function_exists("removeFile")) {

    function removeFile($dir, $oldFile)
    {
        if (Storage::disk('app')->exists($dir . basename($oldFile)))
        {
            Storage::disk('app')->delete($dir . basename($oldFile));
            return true;
        }

        return false;
    }
}