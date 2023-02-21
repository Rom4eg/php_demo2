<?php

/**
 * Reader interface
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\File\Interfaces;

use Generator;

/**
 * File reader interface
 *
 * @package PhpTools\File\Interfaces
 */
interface IFileReader
{
    /**
     * Read the entire content
     *
     * @return string
     */
    public function readAll(): string;

    /**
     * Read N bytes of the file
     *
     * @param int $length number of bytes to read
     *
     * @return string
     */
    public function read(int $length): string;

    /**
     * Read rows one by one.
     *
     * @return Generator
     */
    public function rowGenerator(): Generator;

    /**
     * Reads up to $length bytes from the file
     *
     * @param int $length number of bytes to read
     *
     * @return Generator
     */
    public function bytesGenerator(int $length): Generator;

    /**
     * Return internal pointer to the begining of the file
     *
     * @return void
     */
    public function reset(): void;

    /**
     * Close and unlock file
     *
     * @return void
     */
    public function close(): void;
}
