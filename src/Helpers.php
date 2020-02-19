<?php
namespace GUMP;

class Helpers
{
    /**
     * @inheritDoc function_exists
     */
    public static function functionExists($functionName)
    {
        return function_exists($functionName);
    }

    /**
     * @inheritDoc date
     */
    public static function date($format, $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        return date($format, $timestamp);
    }

    /**
     * @inheritDoc checkdnsrr
     */
    public static function checkdnsrr($host, $type = null)
    {
        return checkdnsrr($host, $type);
    }

    /**
     * @inheritDoc gethostbyname
     */
    public static function gethostbyname($hostname)
    {
        return gethostbyname($hostname);
    }

    /**
     * @inheritDoc file_get_contents
     */
    public static function file_get_contents(
        $filename, $use_include_path = false, $context = null, $offset = 0, $maxlen = null
    ) {
        return file_get_contents($filename, $use_include_path, $context, $offset, $maxlen);
    }
}