<?php

/**
 * Enum
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Interfaces;

/**
 * Logger level enum
 *
 * @package PhpTools\Logger\Interfaces
 */
interface ILoggerLevel
{
    /**
     * Disable logging
     *
     * @var int
     */
    const OFF = 0;

    /**
     * Log errors and exceptions
     *
     * @var int
     */
    const ERROR = 100;

    /**
     * Log errors and warnings
     *
     * @var int
     */
    const WARNING = 200;

    /**
     * Log errors, warnings and info messages.
     * On this level success messages is logs too because success is equal to info.
     *
     * @var int
     */
    const INFO = 300;

    /**
     * Log all messages
     *
     * @var int
     */
    const DEBUG = 1000;
}
