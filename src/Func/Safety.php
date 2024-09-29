<?php
declare(strict_types=1);

namespace Naroat\PhpHelper\Func;

/**
 * 设置保存数据（主要过滤实体，防止xss）
 * @param array $data
 * @return array
 */
if (!function_exists('set_save_data')) {
    function set_save_data(array $data)
    {
        foreach ($data as $key => $v) {
            if (is_string($v)) {
                //转换html内容
                $data[$key] = htmlspecialchars($v, ENT_QUOTES);
            } else {
                $data[$key] = $v;
            }
        }
        return $data;
    }
}