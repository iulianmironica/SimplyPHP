<?php

namespace Framework;

use Application\Settings\Config;

/**
 * Description of Router
 *
 * @author Iulian Mironica
 */
class Router
{

    public $controller;
    public $action = 'index';
    public $query = null;
    public $uri;
    public $route;

    public function __construct()
    {
        // $this->uri = filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING)?: '';
        $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    /* TODO: Refactor below.
     * -------------------- */

    public function setUriParts()
    {
        $parts = $this->uriExtractParts();

        // Routes case first
        if (!empty($parts[0]) AND (strpos($parts[0], '?', 0) === false)) {

            $routes = [
                'static' => [],
                'dynamic' => [],
            ];
            /*$query = end($parts);
            $this->query = strpos($query, '?', 0) === 0 ? ltrim($query, '?') : null;*/

            $session = new Session();

            // Only if database routes are enabled - large amount of data
            if (!empty(Config::$routes['database']['enable'])
                AND !empty(Config::$routes['database']['session']['enable'])
            ) {
                // Session routes should expire after a certain amount of time
                $expirationTime = !empty(Config::$routes['database']['session']['expire'])
                    ? Config::$routes['database']['session']['expire'] : 1800;

                if (!is_null($session->lastActivityTime)
                    && ((time() - $session->lastActivityTime) > $expirationTime)
                ) {
                    // Invalidate the session routes
                    $session->framework->routes = [];
                }
                // Update last activity time stamp
                $session->lastActivityTime = time();
            }

            if (!empty($session->framework->routes)) {

                // Get db routes from session
                $routes = $session->framework->routes;

            } else {
                // Set routes on session
                if (!empty(Config::$routes['database']['enable'])) {
                    $model = new Model();
                    $routesResult = $model->getAll(Config::$routes['database']['table'], [
                        Config::$routes['database']['columns']['slug'],
                        Config::$routes['database']['columns']['controller'],
                    ], null, null);

                    foreach ($routesResult as $route) {

                        // route/example/* = everything that starts with route/example
                        if (!strrpos($route['slug'], '*', 0)) {
                            // Slug is unique
                            $routes['static'][$route['slug']] = $route['controller'];
                        } else {
                            // Dynamic routes will need an extra iteration
                            $routes['dynamic'][$route['slug']] = $route['controller'];
                        }
                    }
                }

                // Config routes overwrite db routes
                if (!empty(Config::$routes['list'])) {
                    foreach (Config::$routes['list'] as $slug => $controller) {
                        if (!strrpos($slug, '*', 0)) {
                            // Slug is unique
                            $routes['static'][$slug] = $controller;
                        } else {
                            // Dynamic routes will need an extra iteration
                            $routes['dynamic'][$slug] = $controller;
                        }
                    }
                }

                // $routes = array_merge($routes, Config::$routes['list']);

                // Set session data
                $session->framework = (object)['routes' => $routes];
            }

            $bind = function ($route, $type = 'static') use ($routes) {
                $this->route = $route;

                // String routes are more frequent
                if (is_string($routes[$type][$route])) {
                    $this->controller = $routes[$type][$route] . 'Controller';
                    $this->action = Config::DEFAULT_ACTION;
                } else {
                    $this->controller = $routes[$type][$route][0] . 'Controller';
                    $this->action = $routes[$type][$route][1];
                }
                return;
            };

            $uriParts = $parts;
            if (strstr(end($uriParts), '?')) {
                array_pop($uriParts);
            }

            $assembledRoute = join('/', $uriParts);

            // March static routes first - route/example
            if (!empty($routes['static'][$assembledRoute])) {
                $bind($assembledRoute);
                return;
            }

            // March static routes first - route/example/1/2/3
            if (!empty($routes['static'][$parts[0]])) {
                $bind($parts[0]);
                return;
            }

            // Match dynamic routes
            if (!empty($routes['dynamic'])) {
                foreach ($routes['dynamic'] as $slug => $controller) {

                    $segments = explode('/', $slug);
                    // Last segment contains '*'
                    array_pop($segments);
                    $routeStartWith = join('/', $segments);

                    // The pattern must match the start string of the current route
                    if ($routeStartWith == substr($assembledRoute, 0, strlen($routeStartWith))) {
                        // Matched
                        $bind($slug, 'dynamic');
                        return;
                    }
                }
            }
        }

        if (!isset($parts[0]) OR empty($parts[0]) OR (strpos($parts[0], '?', 0) === 0)) {
            $this->controller = Utility::prepairFileName(Config::DEFAULT_CONTROLLER, 'Controller');
            $this->action = strtolower(Config::DEFAULT_ACTION);
            /*if (isset($parts[0]) AND strpos($parts[0], '?', 0) === 0) {
                $this->query = ltrim($parts[0], '?');
            }*/
            return;
        }

        // Go deeper one sub folder
        if (is_dir(Utility::getPhpFilePath($parts[0], APPLICATION_CONTROLLER, '')) &&
            isset($parts[1]) &&
            is_file(Utility::getPhpFilePath(Utility::prepairFileName($parts[1], 'Controller'), APPLICATION_CONTROLLER . $parts[0] . DS))
        ) {

            // Also add the sub folder
            $this->controller = $parts[0] . DS . Utility::prepairFileName($parts[1], 'Controller');

            if (isset($parts[2]) AND !empty(trim($parts[2])) AND (strpos($parts[2], '?', 0) === false)) {
                $this->action = strtolower($parts[2]);
            } else {
                $this->action = strtolower(Config::DEFAULT_ACTION);
            }

            /*if (isset($parts[3]) AND !empty($parts[3])) {
                $this->query = array_slice($parts, 3);
            }*/
        } else {
            $this->controller = Utility::prepairFileName($parts[0], 'Controller');

            if (isset($parts[1]) AND !empty(trim($parts[1]))) {
                $this->action = strtolower($parts[1]);
            }

            /*if (isset($parts[2]) AND !empty($parts[2])) {
                $this->query = array_slice($parts, 2);
            }*/
        }
    }

    public function uriExtractParts()
    {
        // Narrow the route processing
        $uriPortion = stristr($this->uri, '?', true);

        if (!$uriPortion) {
            $uriParts = explode(chr(1), str_replace('/', chr(1), $this->uri));
        } else {
            $uriParts = explode(chr(1), str_replace('/', chr(1), $uriPortion));
            // Set the query
            $this->query = ltrim(stristr($this->uri, '?'), '?');
        }

        /*if (!empty($uriParts) && preg_match("/(.*?)(\?.*)/", end($uriParts), $query)) {
            // Pop the last element from array
            unset($uriParts[count($uriParts) - 1]);
            $uriParts = array_merge($uriParts, array(
                $query[1], // Contains the action
                $query[2], // Contains the ?query
            ));
        }*/

        // Remove the empty values
        $keysPreserved = array_filter($uriParts);
        return array_values($keysPreserved);
    }

    /** Get content of an URI segment,
     * if segment contains the question mark, empty string is returned.
     *
     * @param $segmentNumber
     * @return string
     */
    public function getUriSegment($segmentNumber)
    {
        $parts = $this->uriExtractParts();
        if (isset($parts[$segmentNumber])) {
            // Make sure the segment does not contain the GET query string
            if (strpos($parts[$segmentNumber], '?', 0) === false) {
                return $parts[$segmentNumber];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function getRequest()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }
}
