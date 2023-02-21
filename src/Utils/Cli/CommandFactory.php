<?php

/**
 * Factory
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli;

use Exception;
use Rom4eg\PhpTools\Utils\Cli\Interfaces\ICommand;

/**
 * Command factory
 *
 * @package Rom4eg\PhpTools\Utils\Cli
 */
final class CommandFactory
{
    /**
     * Indicates that all commands already found
     *
     * @var bool
     */
    protected static bool $discovered = false;

    /**
     * Available commands
     *
     * @var array
     */
    protected static array $commands_map = [];

    /**
     * Search commands
     *
     * @return void
     */
    protected static function discover(): void
    {
        if (static::$discovered) {
            return ;
        }

        foreach (scandir(__DIR__."/Command") as $file) {
            if (strpos($file, ".") === 0) {
                continue ;
            }

            $class = basename($file, ".php");
            $full_name = "Rom4eg\\PhpTools\\Utils\\Cli\\Command\\$class";

            if (!class_exists($full_name)) {
                include_once __DIR__."/Command/$file";
            }

            if (is_a($full_name, ICommand::class, true)) {
                $alias = $full_name::getAlias();
                static::$commands_map[$alias] = $full_name;
            }
        }
        static::$discovered = true;
    }

    /**
     * Create command object
     *
     * @param string $cmd command alias
     * @param array $args constructor arguments
     *
     * @return ICommand
     */
    public static function makeCommand(string $cmd, array $args = []): ICommand
    {
        static::discover();

        if (array_key_exists($cmd, static::$commands_map)) {
            return new static::$commands_map[$cmd]($args);
        }

        throw new Exception("Command $cmd not found");
    }

    /**
     * Get all commands
     *
     * @return array
     */
    public static function getCommands(): array
    {
        static::discover();
        return static::$commands_map;
    }

    /**
     * Get command class
     *
     * @param string $alias command alias
     *
     * @return string
     */
    public static function getCommand(string $alias): string
    {
        static::discover();
        if (array_key_exists($alias, static::$commands_map)) {
            return static::$commands_map[$alias];
        }

        throw new Exception("Command $alias not found");
    }
}
