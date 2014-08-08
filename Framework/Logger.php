<?php

namespace Framework;

use Application\Settings\Config;

/**
 * Description of Logger
 *
 * @author Iulian Mironica
 */
class Logger
{

    private $pathToFile;
    private $fh;

    public function __construct($namespace = 'logger')
    {

        // Read the destination from config file
        if (!isset(Config::$logger['start']) || Config::$logger['start'] === false) {
            return;
        }

        $pathToFile = PATH . DS . APPLICATION_LOG . $namespace . '.txt';

        // Create new file if does not exists
        if (!is_readable($pathToFile)) {
            fopen($pathToFile, 'w');
        }

        $this->pathToFile = $pathToFile;
    }

    public function error($message, $messageType = 3)
    {
        error_log(date(Config::$logger['timestamp']) .
                self::toString(array('' => $message)) . "\n", $messageType, $this->pathToFile);
    }

    public function info($message, $messageType = 3)
    {

    }

    public function debug($message, $messageType = 3)
    {

    }

    public static function toString($data)
    {
        $export = '';
        foreach ($data as $key => $value) {
            $export .= "{$key}: ";
            $export .= preg_replace(array(
                '/=>\s+([a-zA-Z])/im',
                '/array\(\s+\)/im',
                '/^  |\G  /m',
                    ), array(
                '=> $1',
                'array()',
                '    ',
                    ), str_replace('array (', 'array(', var_export($value, true)));
            $export .= PHP_EOL;
        }
        return str_replace(array('\\\\', '\\\''), array('\\', '\''), rtrim($export));
    }

}
