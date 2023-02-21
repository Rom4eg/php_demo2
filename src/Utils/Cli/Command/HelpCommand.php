<?php

/**
 * Command
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli\Command;

use Exception;
use Rom4eg\PhpTools\Utils\Cli\CommandFactory;
use Rom4eg\PhpTools\Utils\Cli\Interfaces\ICommand;

/**
 * Print usage
 *
 * @package Rom4eg\PhpTools\Utils\Cli\Command
 */
final class HelpCommand extends AbstractCommand implements ICommand
{
    /**
     * Alias to use in console
     *
     * @var string
     */
    const ALIAS = "help";

    /**
     * Command description.
     * Shows in help message
     *
     * @vart string
     */
    const DESCRIPTION = "Show this message and exit";

    /**
     * Long options for getopt() function
     *
     * @var array
     */
    protected array $long_opt = ["cmd::"];

    /**
     * Print command usage
     *
     * @return void
     */
    public function help(): void
    {
        echo "PhpDemo CLI tools".PHP_EOL;
        echo PHP_EOL."Usage: demo <command> [args]".PHP_EOL;

        $cmds = CommandFactory::getCommands();
        echo PHP_EOL."Commands:".PHP_EOL;
        foreach ($cmds as $cmd => $class) {
            echo "\t".str_pad($cmd, 25).$class::getDescription().PHP_EOL;
        }

        echo "Tips:".PHP_EOL;
        echo PHP_EOL."Use the \"--help\" key with the command to see it's usage".PHP_EOL;
        echo "Also you can use the \"-d\" key to run the command in Debug mode".PHP_EOL;
    }

    /**
     * Execute command
     *
     * @return void
     */
    public function execute(): void
    {
        $opt = $this->getOption("cmd", "help");
        $cmd = $this->getCommand($opt);
        $cmd->help();
    }

    /**
     * Get command instance
     *
     * @param string $alias command alias
     *
     * @return null|ICommand
     */
    protected function getCommand(string $alias): ?ICommand
    {
        $cmd = null;
        try {
            $cmd = CommandFactory::makeCommand($alias);
        } catch (Exception $e) {
            return null;
        }

        return $cmd;
    }
}
