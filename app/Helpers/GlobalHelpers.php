<?php

use App\Models\ApiKey;
use App\Models\Settings;
use App\Models\User;
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
            $filename = Str::random(30) . '.' . $file->getClientOriginalExtension();
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

if (!function_exists('getAdmins')) {

    function getAdmins() {
        return User::admins()->select('email', 'id')->get();
    }
}

if (!function_exists('settings')) {
    
    function settings($option) {

        if ($option === null || $option === "") return null;

        return Settings::get_option($option);
    }
}