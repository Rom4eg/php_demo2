<?php

/**
 * Writer interface
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\File\Interfaces;

/**
 * File writer interface
 *
 * @package PhpTools\File\Interfaces
 */
interface IFileWriter
{
    /**
     * Writing mode.
     * Truncate file content.
     *
     * @var string
     */
    const MODE_REPLACE = 'wb';

    /**
     * Writing mode.
     * Append to the end of the file
     *
     * @var string
     */
    const MODE_APPEND = "ab";

    /**
     * Open file for writing
     *
     * @return void
     */
    public function open(): void;

    /**
     * Write to file
     *
     * @param string $content file content
     *
     * @return void
     */
    public function write(string $content): void;

    /**
     * Close and unlock file.
     *
     * @return void
     */
    public function finalize(): void;
}
