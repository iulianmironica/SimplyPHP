<?php

namespace Framework;

/**
 * Description of Session
 *
 * @author Iulian Mironica
 */
class Session
{

    public $variables = array();

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $this->start();
        }
    }

    public function start()
    {
        return session_start();
    }

    public function destroy()
    {
        return session_destroy();
    }

    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

}
