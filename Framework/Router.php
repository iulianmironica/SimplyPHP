<?php

namespace Framework;

use Application\Settings\Config;
use Framework\Utility;

/**
 * Description of Router
 *
 * @author Iulian Mironica
 */
class Router
{

    public $controller;
    public $action;
    public $query;
    public $uri;

    public function __construct()
    {
        // $this->uri = $this->uri = filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING)?: '';
        $this->uri = $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    /* TODO: Refactor below.
     * -------------------- */

    public function setUriParts()
    {

        $parts = $this->uriExtractParts();

        if (!isset($parts[0]) OR empty($parts[0])) {
            $this->controller = Utility::prepairFileName(Config::DEFAULT_CONTROLLER, 'Controller');
            $this->action = strtolower(Config::DEFAULT_ACTION);

            return;
        }

        // Go deeper one subfolder
        if (is_dir(Utility::getPhpFilePath($parts[0], APPLICATION_CONTROLLER, '')) &&
                isset($parts[1]) &&
                is_file(Utility::getPhpFilePath(Utility::prepairFileName($parts[1], 'Controller'), APPLICATION_CONTROLLER . $parts[0] . DS))) {

            // Also add the subfolder
            $this->controller = $parts[0] . DS . Utility::prepairFileName($parts[1], 'Controller');

            if (isset($parts[2]) AND ! empty(trim($parts[2]))) {
                $this->action = strtolower($parts[2]);
            } else {
                $this->action = strtolower(Config::DEFAULT_ACTION);
            }

            if (isset($parts[3]) AND ! empty($parts[3])) {
                $this->query = array_slice($parts, 3);
            }
        } else {
            $this->controller = Utility::prepairFileName($parts[0], 'Controller');

            if (isset($parts[1]) AND ! empty(trim($parts[1]))) {
                $this->action = strtolower($parts[1]);
            } else {
                $this->action = strtolower(Config::DEFAULT_ACTION);
            }

            if (isset($parts[2]) AND ! empty($parts[2])) {
                $this->query = array_slice($parts, 2);
            }
        }
    }

    public function uriExtractParts()
    {
        // Also extract the get params
        $uriParts = explode(chr(1), str_replace(array('/', '?'), chr(1), $this->uri));
        // Remove the empty values
        $keysPreserved = array_filter($uriParts);
        return array_values($keysPreserved);
    }

    public function getUriSegment($segmentNumber)
    {
        $parts = $this->uriExtractParts();
        if (isset($parts[$segmentNumber])) {
            return $parts[$segmentNumber];
        } else {
            return '';
        }
    }

    public function getRequest()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }

    /* Private methods
     * --------------- */
}
