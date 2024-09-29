<?php
declare(strict_types=1);

namespace Naroat\PhpHelper\Func;

if (! function_exists('to_object')) {
    /**
     * 数组 转 对象
     *
     * @param array $arr 数组
     * @return object
     */
    function to_object($arr)
    {
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || gettype($v) == 'object') {
                $arr[$k] = (object) array_to_object($v);
            }
        }

        return (object) $arr;
    }
}