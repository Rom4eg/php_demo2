<?php

/**
 * Interface
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli\Interfaces;

/**
 * Cli command interface
 *
 * @package Rom4eg\PhpTools\Utils\Cli\Interfaces
 */
interface ICommand
{
    /**
     * Print command usage
     *
     * @return void
     */
    public function help(): void;

    /**
     * Execute command
     *
     * @return void
     */
    public function execute(): void;

    /**
     * Get command alias
     *
     * @return string
     */
    public static function getAlias(): string;

    /**
     * Get command short description.
     *
     * @return string
     */
    public static function getDescription(): string;

    /**
     * Get short options for getopt() function
     *
     * @return string
     */
    public function getShortOptions(): string;

    /**
     * Get long options for getopt() function
     *
     * @return array
     */
    public function getLongOptions(): array;

    /**
     * Set command options
     *
     * @param array $opts option list e.g. ["debug" => 1]
     *
     * @return void
     */
    public function setOptions(array $opts): void;
}
