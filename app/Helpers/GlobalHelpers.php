<?php

use App\Models\ApiKey;

if (!function_exists("truncateWords")) {

    function truncateWords($string, $limit, $replaceWith = "...")
    {
        return $string ? ((strlen($string) > $limit) ? substr($string, 0, $limit) . $replaceWith : $string) : '';
    }
}

if (!function_exists("valiadteKey")) {

    function valiadteKey($name, $value)
    {
        return ApiKey::valid($name, $value)->first() !== null;
    }
}