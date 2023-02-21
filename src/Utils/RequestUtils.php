<?php

/**
 * Utils
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils;

/**
 * Request utils
 *
 * @package PhpTools\Utils
 */
final class RequestUtils
{
    /**
     * Get request variable
     *
     * @param string $key variable name
     * @param mixed $default this value returns if $key deos not exists
     *
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $val = $default;
        if (array_key_exists($key, $_REQUEST)) {
            $val = $_REQUEST[$key];
        } else if (array_key_exists($key, $_GET)) {
            $val = $_GET[$key];
        } else if (array_key_exists($key, $_POST)) {
            $val = $_POST[$key];
        }

        return $val;
    }
}
