<?php

namespace Framework;

/**
 * Description of Util
 *
 * @author Iulian Mironica
 */
class Utility
{

    /** Get the absolute file path of a file.
     * @param string $file
     * @param string $location
     * @param string $extension
     * @return string
     */
    public static function getPhpFilePath($file, $location = APPLICATION_CONTROLLER, $extension = '.php')
    {
        // The file might be a subfolder, return it as it is
        if (strstr($file, DS)) {
            return PATH . $location . $file . $extension;
        }

        return PATH . $location . self::prepairFileName($file, '') . $extension;
    }

    /**
     * @param string $fileName
     * @param string $concatenation
     * @return string
     */
    public static function prepairFileName($fileName, $concatenation = false)
    {
        if (!empty($concatenation)) {
            return ucfirst(strtolower(trim($fileName))) . $concatenation;
        } else {
            // TODO refactor
            $fileName = trim($fileName);
            // This will return 3 matches, whole string the file and the
            // concatenation if it has an uppercase word, false otherwise
            preg_match("/([A-Z]*[a-z]+)([A-Z][a-z]+)/", $fileName, $match);
            if (isset($match[1]) && isset($match[2])) {
                return ucfirst(strtolower($match[1])) . $match[2];
            }
            return ucfirst(strtolower($fileName));
        }
    }

    /**
     * @param type $message
     * @param type $data
     */
    public static function showError($message, $data = null)
    {
        var_dump($message);
        var_dump($data);
        die();
    }

    public static function showNotFoundMessage($message)
    {
        die($message);
    }

    /** TODO: Refactor.
     * Redirects to a local or external url.
     *
     * @param string $uri
     * @param bool $refresh
     * @param int $responseCode
     */
    public static function redirect($uri = '', $refresh = false, $responseCode = 302)
    {
        if (empty($uri)) {
            // Redirect to base url
            $uri = self::baseUrl();
        }

        // Allow resources to work with flexible protocol
        // if (!preg_match('#^(https?|s?ftp)://#i', $uri)) {
        if (!preg_match('#^((https?:{1}|s?ftp:{1})|\/\/)#i', $uri)) {
            $uri = self::baseUrl($uri);
        }

        if ($refresh) {
            header("Refresh:0;url=" . $uri);
        } else {
            header("Location: " . $uri, TRUE, $responseCode);
        }

        exit();
    }

    /**
     * @param string $url
     * @return string
     */
    public static function baseUrl($url = null)
    {
        $uri = \Application\Settings\Config::ROUTER_SCHEME . BASE_URL;
        if (!empty($url)) {
            return $uri .= $url;
        } else {
            return $uri;
        }
    }
}
