<?php
declare(strict_types=1);

namespace Naroat\PhpHelper\Func;

/**
 * xml to array 转换.
 * @param type $xml
 * @return type
 */
if (! function_exists('xml_to_array')) {
    function xml_to_array($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}

if (! function_exists('to_array')) {
    /**
     * toArray
     * 对象转数组.
     * @param $object
     * @return bool
     */
    function to_array($object)
    {
        if (! is_object($object)) {
            return $object;
        }
        return json_decode(json_encode($object), true);
    }
}