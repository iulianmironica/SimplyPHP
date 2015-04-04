<?php

namespace Framework;

/**
 * Description of FrameworkException
 *
 * @author iulian.mironica
 */
class FrameworkException extends \Exception
{

    // TODO
    public function __construct($message, $code, $previous)
    {
        parent::__construct($message, $code, $previous);
    }

    const logTypeSystem = 0,
        logTypeEmail = 1,
        logTypeFile = 3,
        logTypeSAPI = 4;

    public function logError($message = '', $type = self::logTypeSystem, $recipient = '')
    {
        $message = $this->setMessage() . ' ' . $message;
        error_log($message, $type, $recipient);
    }

    public function setMessage()
    {
        $newLine = '\n';
        return $this->getFile() . ' ' . $this->getLine() . ' ' . $this->getMessage() . $newLine;
    }

}
