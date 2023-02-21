<?php

/**
 * Engine interface
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Interfaces;

/**
 * Logger engine interface
 *
 * @package PhpTools\Logger\Interfaces
 */
interface ILoggerEngine extends ILogger
{
    /**
     * Getter.
     * Get engine type
     *
     * @return string
     */
    public static function getType(): string;
}
