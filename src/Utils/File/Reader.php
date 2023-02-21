<?php

/**
 * File reader
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\File;

use Exception;
use Generator;
use Rom4eg\PhpTools\Utils\File\Interfaces\IFileReader;

/**
 * File reader
 *
 * @package PhpTools\File
 */
class Reader implements IFileReader
{
    /**
     * Path to file
     *
     * @var string
     */
    protected string $path;

    /**
     * Opened file resource
     *
     * @var resource
     */
    protected $file;

    /**
     * Constructor
     *
     * @param string $path path to file
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Destructor
     * Close opened file
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Read the entire content
     *
     * @return string
     */
    public function readAll(): string
    {
        $data = file_get_contents($this->path);
        if ($data === false) {
            throw new Exception("Unable to read file ".$this->path);
        }

        return $data;
    }

    /**
     * Read N bytes of current the file
     *
     * @param int $length number of bytes to read
     *
     * @return string
     */
    public function read(int $length): string
    {
        if (!is_resource($this->file)) {
            $this->open();
        }

        flock($this->file, LOCK_SH);
        $content = fread($this->file, $length);
        flock($this->file, LOCK_UN);
        if ($content === false || feof($this->file)) {
            throw new Exception("Unable to read $length bytes of ".$this->path);
        }
        return $content;
    }

    /**
     * Read rows one by one.
     *
     * @return Generator
     */
    public function rowGenerator(): Generator
    {
        if (!is_resource($this->file)) {
            $this->reset();
        }

        flock($this->file, LOCK_SH);
        while (!feof($this->file)) {
            $row = fgets($this->file);
            if ($row === false) {
                $row = "";
            }

            yield $row;
        }
        flock($this->file, LOCK_UN);
        $this->close();
    }

    /**
     * Reads up to $length bytes from the file
     *
     * @param int $length number of bytes to read
     *
     * @return Generator
     */
    public function bytesGenerator(int $length): Generator
    {
        if (!is_resource($this->file)) {
            $this->open();
        }

        flock($this->file, LOCK_SH);
        while (!feof($this->file)) {
            $row = fread($this->file, $length);
            if ($row === false) {
                $row = "";
            }

            yield $row;
        }
        flock($this->file, LOCK_UN);
        $this->close();
    }

    /**
     * Open file for reading
     *
     * @return void
     */
    protected function open(): void
    {
        $this->file = fopen($this->path, "rb");
    }

    /**
     * Return internal pointer to the begining of the file
     *
     * @return void
     */
    public function reset(): void
    {
        if (!is_resource($this->file)) {
            $this->open();
        }
        fseek($this->file, 0);
    }

    /**
     * Close and unlock file
     *
     * @return void
     */
    public function close(): void
    {
        if (!is_resource($this->file)) {
            return ;
        }

        flock($this->file, LOCK_UN);
        fclose($this->file);
    }
}
