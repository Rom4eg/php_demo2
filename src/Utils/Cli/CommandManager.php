<?php

/**
 * Manager
 */

declare(strict_types=1);

namespace Rom4eg\PhpTools\Utils\Cli;

/**
 * Command manager
 *
 * @package Rom4eg\PhpTools\Utils\Cli
 */
final class CommandManager
{

    /**
     * Run CLI command
     *
     * @return void
     */
    public static function execCli(): void
    {
        $opt = getopt("d::", ["cmd::", "help::"]);
        if (array_key_exists("help", $opt) || !array_key_exists("cmd", $opt)) {
            $cmd = CommandFactory::makeCommand("help");
        } else {
            $cmd = CommandFactory::makeCommand($opt['cmd']);
        }

        $short = $cmd->getShortOptions();
        $long = $cmd->getLongOptions();
        $cmd_opt = getopt($short, $long);
        $opts = array_merge($opt, $cmd_opt);
        $cmd->setOptions($opts);
        $cmd->execute();
    }
}
