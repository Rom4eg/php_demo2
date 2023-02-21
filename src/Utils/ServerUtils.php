<?php

/**
 * Utils
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils;

/**
 * Server utils
 *
 * @package PhpTools\Utils
 */
final class ServerUtils
{
    /**
     * Get server variable
     *
     * @param string $key variable name
     * @param mixed $default this value returns if $key deos not exists
     *
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }

        return $default;
    }
}
