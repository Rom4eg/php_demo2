<?php

/**
 * File writer
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\File;

use Exception;
use Rom4eg\PhpTools\Utils\File\Interfaces\IFileWriter;

/**
 * File writer
 *
 * @package PhpTools\File
 */
class Writer implements IFileWriter
{
    /**
     * Full path to file
     *
     * @var string
     */
    protected string $path;

    /**
     * Writer mode
     *
     * @var string
     */
    protected string $mode;

    /**
     * Opened file handler
     *
     * @var resource
     */
    protected $fh;

    /**
     * File writing complete
     *
     * @var bool
     */
    protected bool $closed = false;

    /**
     * Constructor.
     *
     * @param string $path path to file
     * @param string $mode writing mode
     */
    public function __construct(string $path, string $mode)
    {
        $this->path = $path;
        $this->mode = $mode;
    }

    /**
     * Destructor.
     * Finalize file writing
     */
    public function __destruct()
    {
        $this->finalize();
    }

    /**
     * Open file forwriting
     *
     * @throws Exception When it was not possible to open the file for writing.
     * @return void
     */
    public function open(): void
    {
        if ($this->closed) {
            throw new Exception("Writing process already complete. ");
        }

        $this->fh = fopen($this->path, $this->mode);
        if (!is_resource($this->fh)) {
            $msg = sprintf("Unable to open file '%s' for writing.", $this->path);
            throw new Exception($msg);
        }
    }

    /**
     * Write to file
     *
     * @param string $content file content
     *
     * @return void
     */
    public function write(string $content): void
    {
        if (!is_resource($this->fh) || $this->closed) {
            throw new Exception("File not opened or finalized");
        }

        flock($this->fh, LOCK_EX);
        $length = fwrite($this->fh, $content);
        flock($this->fh, LOCK_UN);
        if ($length === false) {
            $msg = sprintf("Error while writing to %s", $this->path);
            $this->finalize();
            throw new Exception($msg);
        }
    }

    /**
     * Close and unlock file.
     *
     * @return void
     */
    public function finalize(): void
    {
        if (!is_resource($this->fh)) {
            return ;
        }

        if ($this->closed) {
            throw new Exception("Already finalized.");
        }

        $this->closed = true;
        fflush($this->fh);
        fclose($this->fh);
    }
}
