<?php

/**
 * Command
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli\Command;

/**
 * Base class for any command
 *
 * @package Rom4eg\PhpTools\Utils\Cli\Command
 */
abstract class AbstractCommand
{
    /**
     * Alias to use in console
     *
     * @var string
     */
    const ALIAS = "undefined";

    /**
     * Command description.
     * Shows in help message
     *
     * @vart string
     */
    const DESCRIPTION = "Command short description";

    /**
     * Short options for getopt() function
     *
     * @var string
     */
    protected string $short_opt = "";

    /**
     * Long options for getopt() function
     *
     * @var array
     */
    protected array $long_opt = [];

    /**
     * Command options
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Get command alias
     *
     * @return string
     */
    public static function getAlias(): string
    {
        return static::ALIAS;
    }

    /**
     * Get command short description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return static::DESCRIPTION;
    }

    /**
     * Get short options for getopt() function
     *
     * @return string
     */
    public function getShortOptions(): string
    {
        return $this->short_opt;
    }

    /**
     * Get long options for getopt() function
     *
     * @return array
     */
    public function getLongOptions(): array
    {
        return $this->long_opt;
    }

    /**
     * Set command options
     *
     * @param array $opts option list e.g. ["debug" => 1]
     *
     * @return void
     */
    public function setOptions(array $opts): void
    {
        $this->options = $opts;
    }

    /**
     * Get option by name
     *
     * @param string $opt option name
     * @param mixed $default return this value if options doesn't exists
     *
     * @return mixed
     */
    protected function getOption(string $opt, $default = null)
    {
        if (array_key_exists($opt, $this->options)) {
            return $this->options[$opt];
        }

        return $default;
    }

    /**
     * Checks if the option is specified.
     *
     * @param string $opt option name
     *
     * @return bool
     */
    protected function hasOption(string $opt): bool
    {
        return array_key_exists($opt, $this->options);
    }

    /**
     * Check for debug mode
     *
     * @return bool
     */
    protected function isDebug(): bool
    {
        return array_key_exists("d", $this->options);
    }
}
