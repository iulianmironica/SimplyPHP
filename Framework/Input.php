<?php

/**
 * Description of Input
 *
 * @author Iulian Mironica
 */
class Input {

    /**
     * @param type $item
     * @return type
     */
    public static function post($item = null) {
        if (!empty($item)) {
            return filter_input(INPUT_POST, $item);
        } else {
            return filter_input_array(INPUT_POST);
        }
    }

    /**
     * @param type $item
     * @return type
     */
    public static function get($item = null) {
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
    public function __get($name) {
        if (!empty(filter_input(INPUT_GET, $name))) {
            return filter_input(INPUT_GET, $name);
        } else if (!empty(filter_input(INPUT_POST, $name))) {
            return filter_input(INPUT_GET, $name);
        } else {
            return null;
        }
    }

}