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

    public function __construct($uri) {
        $this->uri = $uri ? : '';
    }

    public function setUriParts() {

        $parts = $this->uriExtractParts();

        if (empty(trim($parts[0]))) {
            $this->controller = Util::prepairFileName(FrameworkSettings::DEFAULT_CONTROLLER, 'Controller');
        } else {
            $this->controller = Util::prepairFileName($parts[0], 'Controller');
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
        return explode('/', $this->uri);
    }

    public function getUriSegment($segmentNumber) {
        $parts = $this->uriExtractParts();
        return isset($parts[$segmentNumber]) ? $parts[$segmentNumber] : '';
    }

    public function getRequest() {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }

}
