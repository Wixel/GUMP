<?php
namespace GUMP;

class Helpers
{
    public static function date($format, $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        return date($format, $timestamp);
    }


}