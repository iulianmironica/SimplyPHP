<?php

namespace Application\Library;

/**
 * @author Iulian Mironica
 */
class Helper
{

    /**
     * @return boolean
     */
    public static function raiseTimeAndMemoryLimits($seconds = 20, $memory = '128')
    {
        try {
            // Raise memory limit
            ini_set('memory_limit', "{$memory}M");
            set_time_limit($seconds);
            return true;
        } catch (Exception $ex) {
            var_dump($ex);
            return false;
        }
    }

}
