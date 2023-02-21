<?php

/**
 * Base log engine
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger\Engine;

/**
 * Base logger engine
 *
 * @package PhpTools\Logger\Engine
 */
abstract class AbstractEngine
{
    /**
     * Logger type
     *
     * @var string
     */
    const TYPE = "";

    /**
     * Getter.
     * Get engine type
     *
     * @return string
     */
    public static function getType(): string
    {
        return static::TYPE;
    }
}
