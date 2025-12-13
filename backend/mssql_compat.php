<?php
/**
 * Polyfill the deprecated mssql_* API using MySQLi so legacy QueryBuilder code
 * can operate against the T-BOT MySQL database without requiring the mssql
 * extension.
 */
if (!function_exists('mssql_connect')) {
    function mssql_connect($server, $user, $password)
    {
        return mysqli_connect($server, $user, $password);
    }
}

if (!function_exists('mssql_select_db')) {
    function mssql_select_db($database, $link)
    {
        return mysqli_select_db($link, $database);
    }
}

if (!function_exists('mssql_query')) {
    function mssql_query($query, $link)
    {
        return mysqli_query($link, $query);
    }
}

if (!function_exists('mssql_fetch_assoc')) {
    function mssql_fetch_assoc($result)
    {
        return mysqli_fetch_assoc($result);
    }
}

if (!function_exists('mssql_num_rows')) {
    function mssql_num_rows($result)
    {
        return mysqli_num_rows($result);
    }
}

if (!function_exists('mssql_free_result')) {
    function mssql_free_result($result)
    {
        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
        }
    }
}
