<?php
declare(strict_types=1);

namespace Naroat\PhpHelper\Func;

/**
 * 判断数据库是否存在
 * return true:存在
 */
if (!function_exists('db_exists')) {
    function db_exists($host, $username, $password, $dbname)
    {
        $flag = true;
        $dbh = mysqli_connect($host, $username, $password);
        $select_db = mysqli_query($dbh, 'use ' . $dbname);
        if (!$select_db) {
            $flag = false;
        }
        return $flag;
    }
}