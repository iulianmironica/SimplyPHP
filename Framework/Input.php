<?php

namespace Framework;

/**
 * Description of Input
 *
 * @author Iulian Mironica
 */
class Input
{

    /**
     * @param null $item
     * @param int $filter
     * @return mixed
     */
    public static function post($item = null, $filter = FILTER_DEFAULT)
    {
        if (!empty($item)) {
            return filter_input(INPUT_POST, $item, $filter);
        } else {
            return filter_input_array(INPUT_POST);
        }
    }

    /**
     * @param type $item
     * @return type
     */
    public static function get($item = null)
    {
        if (!empty($item)) {
            return filter_input(INPUT_GET, $item);
        } else {
            return filter_input_array(INPUT_GET);
        }
    }

    /**
     * @param string $name
     * @return string or null
     */
    public function __get($name)
    {
        $filteredGetValue = filter_input(INPUT_GET, $name);
        $filteredPostValue = filter_input(INPUT_POST, $name);
        if (!empty($filteredGetValue)) {
            return $filteredGetValue;
        } else if (!empty($filteredPostValue)) {
            return $filteredPostValue;
        } else {
            return null;
        }
    }
}
