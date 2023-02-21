<?php

/**
 * Command
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli\Command;

use Exception;
use Rom4eg\PhpDemo2\Modules\Employee\Import;
use Rom4eg\PhpTools\Utils\Cli\Interfaces\ICommand;

/**
 * Import data from external sources
 *
 * @package Rom4eg\PhpTools\Utils\Cli\Command
 */
final class ImportCommand extends AbstractCommand implements ICommand
{
    /**
     * Alias to use in console
     *
     * @var string
     */
    const ALIAS = "import";

    /**
     * Command description.
     * Shows in help message
     *
     * @vart string
     */
    const DESCRIPTION = "Custom import command";

    /**
     * Long options for getopt() function
     *
     * @var array
     */
    protected array $long_opt = ["file:"];

    /**
     * Short options for getopt() function
     *
     * @var string
     */
    protected string $short_opt = "f::";

    /**
     * Print command usage
     *
     * @return void
     */
    public function help(): void
    {
        echo "Custom import command".PHP_EOL;
        echo PHP_EOL."Usage: demo ".static::getAlias()." --file=<path> [args]".PHP_EOL;

        echo PHP_EOL."Options: ".PHP_EOL;
        echo str_pad("\t--file=<path to file>", 25)."Full path to import file".PHP_EOL;
    }

    /**
     * Execute command
     *
     * @return void
     */
    public function execute(): void
    {
        $file = $this->getOption("file", '');
        if (empty($file)) {
            $this->help();
            return;
        }

        $force = $this->hasOption("f", false);
        $mod = new Import($file, $force);
        $mod->startImport();
    }
}
