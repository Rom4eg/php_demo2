<?php

/**
 * Utils
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils;

use Exception;

/**
 * File system utils
 *
 * @package PhpTools\Utils
 */
class FsUtils
{
    /**
     * Recursively create the full path
     *
     * @param string $dir path to directory
     * @param int $mode octal dir mode
     *
     * @return bool
     */
    public static function makeDir(string $dir, int $mode = 0740): bool
    {
        if (is_dir($dir)) {
            return true;
        }

        mkdir($dir, $mode, true);
        return is_dir($dir);
    }

    /**
     * Delete file
     *
     * @param string $path path to file
     *
     * @return bool
     */
    public static function delete(string $path): bool
    {
        if (is_file($path) && is_readable($path)) {
            return unlink($path);
        }
        return false;
    }

    /**
     * Delete directory recursively
     *
     * @param string $path file path to directory
     *
     * @return void
     */
    public static function removeDir(string $path): void
    {
        $dir = opendir($path);
        if (!$dir) {
            throw new Exception("Unable to remove directory '$path'");
        }

        while (false !== ($file = readdir($dir))) {
            if (substr($file, 0, 1) === '.') {
                continue;
            }

            $full = implode(DIRECTORY_SEPARATOR, [$path, $file]);
            if (is_dir($full)) {
                static::removeDir($full);
            } else {
                static::delete($full);
            }
        }
        closedir($dir);
        rmdir($path);
    }

    public static function findFiles(string $path, bool $rec = false): array
    {
        $dir = opendir($path);
        if (!$dir) {
            throw new Exception("Unable to open directory '$path'");
        }

        $files = [];
        while (false !== ($file = readdir($dir))) {
            if (substr($file, 0, 1) === '.') {
                continue;
            }

            $full = implode(DIRECTORY_SEPARATOR, [$path, $file]);
            if (is_dir($full)) {
                $_files = static::findFiles($full);
                $files = array_merge($files, $_files);
            } else {
                $files[] = $full;
            }
        }
        closedir($dir);
        return array_filter(array_unique($files));
    }
}
