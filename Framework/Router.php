<?php

/**
 * Description of Router
 *
 * @author Iulian Mironica
 */
class Router {

    public $controller;
    public $action;
    public $query;
    public $uri;

    public function __construct() {
        // $this->uri = $this->uri = filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING)?: '';
        $this->uri = $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    public function setUriParts() {

        $parts = $this->uriExtractParts();

        if (isset($parts[0]) AND ! empty(trim($parts[0]))) {
            $this->controller = Util::prepairFileName($parts[0], 'Controller');
        } else {
            $this->controller = Util::prepairFileName(FrameworkSettings::DEFAULT_CONTROLLER, 'Controller');
        }

        if (isset($parts[1]) AND ! empty(trim($parts[1]))) {
            $this->action = strtolower($parts[1]);
        } else {
            $this->action = strtolower(FrameworkSettings::DEFAULT_ACTION);
        }

        if (isset($parts[2]) AND ! empty($parts[2])) {
            $this->query = array_slice($parts, 2);
        }
    }

    public function uriExtractParts() {
        // Also extract the get params
        $uriParts = explode(chr(1), str_replace(array('/', '?'), chr(1), $this->uri));
        // Remove the empty values
        $keysPreserved = array_filter($uriParts);
        return array_values($keysPreserved);
    }

    public function getUriSegment($segmentNumber) {
        $parts = $this->uriExtractParts();
        return isset($parts[$segmentNumber]) ? $parts[$segmentNumber] : '';
    }

    public function getRequest() {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }

}
