<?php

/**
 * Console entry point
 */

declare(strict_types=1);

require_once __DIR__."/../../../vendor/autoload.php";

use Rom4eg\PhpTools\Utils\Cli\CommandManager;

if (php_sapi_name() != "cli") {
    throw new Exception("Only CLI mode is allowed");
}

const ABSPATH = __DIR__."/..";

const LIBPATH = __DIR__."/..";

// require_once __DIR__."/Interfaces/ICommand.php";
// require_once __DIR__."/CommandFactory.php";
// require_once __DIR__."/CommandManager.php";
// require_once __DIR__."/Command/AbstractCommand.php";

CommandManager::execCli();
