<?php

/**
 * Helper
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\File;

use Exception;
use Rom4eg\PhpTools\Utils\File\Interfaces\IFileReader;
use Rom4eg\PhpTools\Utils\File\Interfaces\IFileWriter;
use Rom4eg\PhpTools\Utils\FsUtils;

/**
 * File read/write helpers
 *
 * @package PhpTools\File
 */
class File
{

    /**
     * Factory method.
     * Create new file writer.
     *
     * @param string $path path to file
     * @param string $mode writing mode. Append or Replace
     *
     * @return IFileWriter
     */
    public static function writeTo(string $path, string $mode = IFileWriter::MODE_REPLACE): IFileWriter
    {
        FsUtils::makeDir(dirname($path));
        $writer = new Writer($path, $mode);
        $writer->open();
        return $writer;
    }

    /**
     * Factory method.
     * Create newfile reader.
     *
     * @param string $path path to file
     *
     * @throws Exception when file not readable or not exist
     * @return IFileReader
     */
    public static function readFrom(string $path): IFileReader
    {
        if (!is_readable($path)) {
            throw new Exception("File not readable - $path");
        }

        $reader = new Reader($path);
        return $reader;
    }
}
