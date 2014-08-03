<?php

/**
 * Description of Logger
 *
 * @author Iulian Mironica
 */
class Logger {

    private $pathToFile;
    private $fh;

    public function __construct($namespace = 'logger') {

        // Read the destination from config file
        if (!isset(FrameworkSettings::$logger['start']) || FrameworkSettings::$logger['start'] === false) {
            return;
        }

        $pathToFile = PATH . DS . APPLICATION_LOG . $namespace . '.txt';

        // Create new file if does not exists
        if (!is_readable($pathToFile)) {
            fopen($pathToFile, 'w');
        }

        $this->pathToFile = $pathToFile;
    }

    public function error($message, $messageType = 3) {
        error_log($this->prepairData(array(
            date(FrameworkSettings::$logger['timestamp']),
            $message,
            "\n"
                )), $messageType, $this->pathToFile);
    }

    public function info($message, $messageType = 3) {
        error_log(array(
            date(FrameworkSettings::$logger['timestamp']) => $message
                ), $messageType, $this->pathToFile);
    }

    public function debug($message, $messageType = 3) {
        error_log(array(
            date(FrameworkSettings::$logger['timestamp']) => $message
                ), $messageType, $this->pathToFile);
    }

    public static function prepairData($value) {

        $glue = ' ';
        $include_keys = true;
        $glued_string = ' ';

        if (is_array($value)) {
            array_walk_recursive($value, function($value, $key) use ($glue, $include_keys, &$glued_string) {
                $include_keys AND $glued_string .= $key . $glue;
                $glued_string .= $value . $glue;
            });
            return (string) $glued_string;
        } else {

            if (is_string($value)) {
                return $value;
            }
            if (is_numeric($value)) {
                return (string) $value;
            }
            if (is_object($value) && method_exists($value, '__toString')) {
                return (string) $value;
            }
        }
    }

    public function write($string) {
        $this->fh = fopen($this->pathToFile, 'w');
        fwrite($this->fh, $string . "\n");
    }

    public function __toString() {

    }

}
