<?php

if (!function_exists("truncateWords")) {

    function truncateWords($string, $limit, $replaceWith = "...")
    {
        return $string ? ((strlen($string) > $limit) ? substr($string, 0, $limit) . $replaceWith : $string) : '';
    }
}