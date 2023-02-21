<?php

/**
 * Tools
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils;

/**
 * Array helpers
 *
 * @package PhpTools\Utils
 */
final class ArrayUtils
{
    /**
     * Safely get value from multidimension array.
     * The path is like in JS objects e.g. my_data.first_level.second_level.value_field.
     *
     * @param string $path js objects notation.
     * @param array $data source array.
     * @param mixed $default default value if path doesn't match to data struct.
     *
     * @return mixed
     */
    public static function path(string $path, array $data, $default = null)
    {
        $parts = explode('.', $path);
        $value = $data;

        while (count($parts)) {
            $key = array_shift($parts);
            if (!array_key_exists($key, $value)) {
                return $default;
            }
            $value = $value[$key];
        }
        return $value;
    }

    /**
     * Short version of json_encode.
     *
     * @param array $data The value being encoded.
     * @param bool $pretty_print prettify and unescape result.
     *
     * @return string
     */
    public static function jsonSerialize(array $data, bool $pretty_print = false): string
    {
        $params = 0;
        if ($pretty_print) {
            $params = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        }

        return json_encode($data, $params);
    }
}
