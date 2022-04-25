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
            $filename = $fileOriginalName . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
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

if (!function_exists('downloadFile')) {

    function downloadFile($dir, $file)
    {
        $filename = basename($file);
        list($filename, $ext) = explode(".", $filename);
        $filename = explode("_", $filename);

        if (count($filename) > 1) {
            unset($filename[count($filename) - 1]);
        }
        
        $actualFilename = implode("_", $filename)  . '.' . $ext;
        return Storage::disk('app')->download($dir . '/' . $file, $actualFilename);
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

        $d = Carbon::createFromFormat(config('settings.datetime_format'), $date);

        if (strpos($date, "PM") !== false) {
            $d = $d->addHours(12);
        }

        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}