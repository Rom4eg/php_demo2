<?php

/**
 * Base interface
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Interfaces;

use Throwable;

/**
 * Logger interface
 *
 * @package PhpTools\Logger\Interfaces
 */
interface ILogger
{
    /**
     * Print message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function log(string $msg): void;

    /**
     * Print error message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function error(string $msg): void;

    /**
     * Print warning message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function warning(string $msg): void;

    /**
     * Print info message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function info(string $msg): void;

    /**
     * Print success message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function success(string $msg): void;

    /**
     * Print debug info
     *
     * @param string $msg message
     * @param array $data debug data
     *
     * @return void
     */
    public function debug(string $msg, array $data = []): void;

    /**
     * Print deprecated message
     *
     * @param string $msg message
     *
     * @return void
     */
    public function deprecated(string $msg): void;

    /**
     * Format and print exception
     *
     * @param Throwable $err exception
     *
     * @return void
     */
    public function exception(Throwable $err): void;

    /**
     * Print progress status.
     * This will print a progress bar which is always at the bottom.
     *
     * @param string $msg progress text/bar etc.
     *
     * @return void
     */
    public function progress(string $msg): void;

    /**
     * Finalize progress.
     * Print latest progress status and release brogress bar
     *
     * @return void
     */
    public function stopProgress(): void;

    /**
     * Get path to the current logger location
     *
     * @return string
     */
    public function getLogPath(): string;
}
