<?php

/**
 * Factory
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Logger;

use Exception;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILogger;
use Rom4eg\PhpTools\Utils\Logger\Interfaces\ILoggerEngine;

/**
 * Logger factory
 *
 * @package PhpTools\Logger
 */
final class LoggerFactory
{

    /**
     * All logger engines already found
     *
     * @var bool
     */
    protected static bool $discovered = false;

    /**
     * Logger map
     *
     * @var array
     */
    protected static array $map = [];

    /**
     * Find all logger engines
     *
     * @return void
     */
    protected static function discover(): void
    {
        if (static::$discovered) {
            return ;
        }

        $path = __DIR__.DIRECTORY_SEPARATOR."Engine";
        foreach (scandir($path) as $file) {
            if (strpos($file, ".") === 0) {
                continue ;
            }

            $cls = basename($file, ".php");
            $ns = __NAMESPACE__."\\Engine\\$cls";
            if (is_a($ns, ILogger::class, true)) {
                $key = $ns::getType();
                static::$map[$key] = $ns;
            }
        }

        static::$discovered = true;
    }

    /**
     * Create new logger of selected type
     *
     * @param string $type logger engine type
     * @param array $args logger engine params
     *
     * @throws Exception
     * @return Logger
     */
    public static function make(string $type, array $args = []): Logger
    {
        static::discover();
        if (array_key_exists($type, static::$map)) {
            $engine = new static::$map[$type](...$args);
            return new Logger($engine);
        }

        throw new Exception("Logger engine '$type' not found");
    }

    /**
     * Add new or replace existed logger type
     *
     * @param string $cls logger class
     *
     * @throws Exception
     * @return void
     */
    public static function registerEngine(string $cls): void
    {
        if (is_a($cls, ILoggerEngine::class, true)) {
            $key = $cls::getType();
            static::$map[$key] = $cls;
            return ;
        }

        throw new Exception("Class $cls must implement ".ILoggerEngine::class." interface");
    }
}
