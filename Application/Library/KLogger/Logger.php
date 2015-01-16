<?php

namespace Application\Library\KLogger;

use DateTime;
use RuntimeException;

// use Application\Library\KLogger\Psr\LoggerInterface;
// use Application\Library\KLogger\Psr\LogLevel;

/**
 * This class is an updated version of KLogger.
 * - Added the format functionality
 * - Placed all in one class
 *
 * @author iulian.mironica
 * -------------------------------------------
 *
 * Finally, a light, permissions-checking logging class.
 *
 * Originally written for use with wpSearch
 *
 * Usage:
 * $log = new Katzgrau\KLogger\Logger('/var/log/', Psr\Log\LogLevel::INFO);
 * $log->info('Returned a million search results'); //Prints to the log file
 * $log->error('Oh dear.'); //Prints to the log file
 * $log->debug('x = 5'); //Prints nothing due to current severity threshhold
 *
 * @author  Kenny Katzgrau <katzgrau@gmail.com>
 * @since   July 26, 2008 — Last update July 1, 2012
 * @link    http://codefury.net
 * @version 0.2.0
 */

/**
 * Class documentation
 */
class Logger
{

    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    /**
     * Path to the log file
     * @var string
     */
    private $logFilePath = null;

    /**
     * Current minimum logging threshold
     * @var integer
     */
    private $logLevelThreshold = self::DEBUG;
    private $logLevels = array(
        self::EMERGENCY => 0,
        self::ALERT => 1,
        self::CRITICAL => 2,
        self::ERROR => 3,
        self::WARNING => 4,
        self::NOTICE => 5,
        self::INFO => 6,
        self::DEBUG => 7,
    );

    /**
     * This holds the file handle for this instance's log file
     * @var resource
     */
    private $fileHandle = null;

    /**
     * Valid PHP date() format string for log timestamps
     * @var string
     */
    private $dateFormat = 'Y-m-d G:i:s.u';

    /** A log style passed to the contstructor trough $options['format']
     * Options:
     * %timestamp%      - the timestamp declared above
     * %level%          - level declared above
     * %class%          - clas name
     * %function%       - method/function name
     * %message%        - the message passed as param
     * %line%, %file%   - point to the parent file that triggered method/function
     *
     * @var string
     */
    private $contextInfoFormat = '%timestamp% %level% %message%';

    /**
     * Octal notation for default permissions of the log file
     * @var integer
     */
    private $defaultPermissions = 0777;

    // TODO: options
    // private $options = array();

    /**
     * @param array $options
     * @param string $logLevelThreshold
     */
    public function __construct(
        /*$logDirectory,*/
        array $options = array(),
        $logLevelThreshold = self::DEBUG)
    {
        $this->logLevelThreshold = $logLevelThreshold;

        /* TODO: REMOVE -> Replaced by createLogFileIfNotExists
        $logDirectory = rtrim($logDirectory, '\\/');
        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, $this->defaultPermissions, true);
        }

        $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . 'log_' . date('Y-m-d') . '.txt';
        if (file_exists($this->logFilePath) && !is_writable($this->logFilePath)) {
            throw new RuntimeException('The file could not be written to. Check that appropriate permissions have been set.');
        }

        $this->fileHandle = fopen($this->logFilePath, 'a');
        if (!$this->fileHandle) {
            throw new RuntimeException('The file could not be opened. Check permissions.');
        }*/

        // Set the array options
        if (!empty($options)) {

            // Set the log level
            if (isset($options['level']) && !empty($options['level'])) {
                $this->logLevelThreshold = $options['level'];;
            }

            // Set the log dir
            if (isset($options['directory']) && !empty($options['directory'])) {
                $this->logFilePath = $options['directory'];
            }

            // Set the timestamp
            if (isset($options['timestamp']) && !empty($options['timestamp'])) {
                $this->dateFormat = $options['timestamp'];
            }
            // Set the log format
            if (isset($options['format']) && !empty($options['format'])) {
                $this->contextInfoFormat = $options['format'];
            }
        }
    }

    /**
     * Class destructor
     */
    public function __destruct()
    {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
        }
    }

    /**
     * Sets the date format used by all instances of KLogger
     *
     * @param string $dateFormat Valid format string for date()
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * Sets the Log Level Threshold
     *
     * @param string $logLevelThreshold Valid format string for date()
     */
    public function setLogLevelThreshold($logLevelThreshold)
    {
        $this->logLevelThreshold = $logLevelThreshold;
    }

    /**
     * @param $message
     * @param array $context
     */
    public function emergency($message, array $context = array())
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->logLevels[$this->logLevelThreshold] < $this->logLevels[$level]) {
            return;
        }

        /*
        Production environment improvement:
        Check the log file on every log operation as there might be the case when no log
        is going to be performed because of the logLevelThreshold therefor we don't need the file
        to be checked/created unless we're sure we write to it.*/
        $this->createLogFileIfNotExists();

        $level = strtoupper($level);

        // Check if there's a log format
        if (!empty($this->contextInfoFormat)) {
            $eContext = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $message = $this->assembleMessage($eContext[2], $message, $level);
            $output = $this->addContext($level, $message, $context);
        } else {
            $output = $this->addContext($level, $message, $context, true);
        }

        $this->write($output);
    }

    private function createLogFileIfNotExists()
    {
        // The file might be open already
        if (is_null($this->fileHandle)) {

            if (is_null($this->logFilePath)) {
                throw new RuntimeException('The path of the log file is not set.');
            }

            $logDirectory = rtrim($this->logFilePath, '\\/');
            if (!file_exists($logDirectory)) {
                mkdir($logDirectory, $this->defaultPermissions, true);
            }

            // TODO: allow a custom file name
            $fileName = 'Log_' . date('Y-m-d') . '.txt';

            $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $fileName;
            if (file_exists($this->logFilePath) && !is_writable($this->logFilePath)) {
                throw new RuntimeException('The file could not be written to. Check that appropriate permissions have been set.');
            }

            $this->fileHandle = fopen($this->logFilePath, 'a');
            if (!$this->fileHandle) {
                throw new RuntimeException('The file could not be opened. Check permissions.');
            }
        }
    }

    /** Assemble message and the context information if format provided as an
     * option to the constructor.
     *
     * @param array $eContext
     * @param string $message
     * @param string $level
     * @return string
     */
    public function assembleMessage($eContext, $message, $level)
    {
        $result = $this->contextInfoFormat;

        if (isset($eContext['class'])) {
            $result = str_replace("%class%", $eContext['class'], $result);
        }
        if (isset($eContext['function'])) {
            $result = str_replace("%function%", $eContext['function'], $result);
        }
        if (isset($eContext['file'])) {
            $result = str_replace("%file%", $eContext['file'], $result);
        }
        if (isset($eContext['line'])) {
            $result = str_replace("%line%", $eContext['line'], $result);
        }

        /* Does the same thing as above, but it's not so intuitive
          foreach ($eContext[0] as $key => $val) {
          $result = str_replace("%{$key}%", $val, $result);
          }
         */

        $result = str_replace("%timestamp%", $this->getTimestamp(), $result);
        $result = str_replace("%message%", $message, $result);
        $result = str_replace("%level%", $level, $result);

        /** TODO:
         *  Also store the ip.
         */
        return $result;
    }

    /**
     * Gets the correctly formatted Date/Time for the log entry.
     *
     * PHP DateTime is dump, and you have to resort to trickery to get microseconds
     * to work correctly, so here it is.
     *
     * @return string
     */
    private function getTimestamp()
    {
        $originalTime = microtime(true);
        $micro = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);
        $date = new DateTime(date('Y-m-d H:i:s.' . $micro, $originalTime));

        return $date->format($this->dateFormat);
    }

    /** Formats the message for logging.
     *
     * @param string $level
     * @param string $message
     * @param mixed $context
     * @param bool $addFormat
     * @return string
     */
    private function addContext($level, $message, $context, $addFormat = false)
    {
        if (!empty($context)) {
            $message .= PHP_EOL . $this->indent($this->contextToString($context));
        }
        if (false === $addFormat) {
            return $message . PHP_EOL;
        }
        return "[{$this->getTimestamp()}] [{$level}] {$message}" . PHP_EOL;
    }

    /**
     * Indents the given string with the given indent.
     *
     * @param  string $string The string to indent
     * @param  string $indent What to use as the indent.
     * @return string
     */
    private function indent($string, $indent = '    ')
    {
        return $indent . str_replace("\n", "\n" . $indent, $string);
    }

    /**
     * Takes the given context and coverts it to a string.
     *
     * @param  array $context The Context
     * @return string
     */
    private function contextToString($context)
    {
        $export = '';
        foreach ($context as $key => $value) {
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

    /**
     * Writes a line to the log without prepending a status or timestamp
     *
     * @param string $message Line to write to the log
     * @return void
     */
    public function write($message)
    {
        if (!is_null($this->fileHandle)) {
            if (fwrite($this->fileHandle, $message) === false) {
                throw new RuntimeException('The file could not be written to. Check that appropriate permissions have been set.');
            }
        }
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function alert($message, array $context = array())
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function critical($message, array $context = array())
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function error($message, array $context = array())
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function warning($message, array $context = array())
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function notice($message, array $context = array())
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function info($message, array $context = array())
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * @param type $message
     * @param array $context
     */
    public function debug($message, array $context = array())
    {
        $this->log(self::DEBUG, $message, $context);
    }

}
