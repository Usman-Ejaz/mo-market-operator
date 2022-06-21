<?php

use App\Models\ApiKey;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $fileOriginalName = explode(".", $file->getClientOriginalName())[0];
            $filename = $fileOriginalName . '_ismo_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $contents = $file->get();
            Storage::disk(config('settings.storage_disk'))->put($dir . $filename, $contents);
        } catch (\Exception $ex) {
            $filename = null;
        }
        
        return $filename;
    }
}

if (!function_exists("serveFile")) {

    function serveFile($dir, $file)
    {
        return $file ? Storage::disk(config('settings.storage_disk'))->url($dir . $file) : null;
    }
}

if (!function_exists("removeFile")) {

    function removeFile($dir, $oldFile)
    {
        if (Storage::disk(config('settings.storage_disk'))->exists($dir . basename($oldFile)))
        {
            Storage::disk(config('settings.storage_disk'))->delete($dir . basename($oldFile));
            return true;
        }

        return false;
    }
}

if (!function_exists('downloadFile')) {

    function downloadFile($dir, $file)
    {
        $filename = getFileOriginalName($file);
        return Storage::disk(config('settings.storage_disk'))->download($dir . '/' . $file, $filename);
    }
}

if (! function_exists('getFileOriginalName')) {
    
    function getFileOriginalName($file)
    {
        $filename = basename($file);
        list($filename, $ext) = explode(".", $filename);

        if (strpos($filename, "_ismo_") !== false) {
            $filename = explode("_", $filename);
            unset($filename[count($filename) - 1]);
            unset($filename[count($filename) - 1]);
            $filename = implode("_", $filename);
        }

        return $filename . '.' . $ext;
    }
}

if (!function_exists("hasPermission")) {

    function hasPermission($module, $permission) 
    {
        if ($module === "" || $permission === "") return false;

        if (auth()->check() && auth()->user()->role->hasPermission($module, $permission)) {
            return true;
        }

        return false;        
    }
}

if (!function_exists('getNotifiableUsers')) {

    function getNotifiableUsers() {
        return User::notifiable()->get();
    }
}

if (!function_exists('settings')) {
    
    function settings($option) {

        if ($option === null || $option === "") return null;

        return Settings::get_option($option);
    }
}

if (!function_exists('parseDate')) {

    function parseDate($date)
    {
        if ($date === null || $date === "") return null;

        $date = str_replace("/", "-", str_replace(" PM", ":00", str_replace(" AM", ":00", $date)));

        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}

if (!function_exists('str_slug')) {

    function str_slug($string)
    {
        return \Illuminate\Support\Str::slug($string);
    }
}

if (! function_exists('encodeBase64')) {
    
    function encodeBase64($key)
    {
        return base64_encode(base64_encode(base64_encode($key)));
    }

}

if (! function_exists('decodeBase64')) {

    function decodeBase64($key)
    {
        return base64_decode(base64_decode(base64_decode($key)));
    }
}