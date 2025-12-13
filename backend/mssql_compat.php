<?php
/**
 * Stub replacements for deprecated mssql_* functions.
 * These no-op implementations avoid opening any SQL connections while keeping
 * legacy includes from failing in view-only mode.
 */
if (!function_exists('mssql_connect')) {
    function mssql_connect($server = null, $user = null, $password = null)
    {
        return false;
    }
}

if (!function_exists('mssql_select_db')) {
    function mssql_select_db($database = null, $link = null)
    {
        return false;
    }
}

if (!function_exists('mssql_query')) {
    function mssql_query($query = null, $link = null)
    {
        return false;
    }
}

if (!function_exists('mssql_fetch_assoc')) {
    function mssql_fetch_assoc($result = null)
    {
        return null;
    }
}

if (!function_exists('mssql_num_rows')) {
    function mssql_num_rows($result = null)
    {
        return 0;
    }
}

if (!function_exists('mssql_free_result')) {
    function mssql_free_result($result = null)
    {
        return true;
    }
}
